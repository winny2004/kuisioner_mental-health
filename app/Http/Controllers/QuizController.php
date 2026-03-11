<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Question;
use App\Models\Answer;
use App\Models\QuizResult;

class QuizController extends Controller
{
    public function index()
    {
        return view('quiz.index');
    }

    public function start($type)
    {
        if (!in_array($type, ['family_social', 'self_efficacy'])) {
            abort(404);
        }

        $questions = Question::byType($type)->active()->ordered()->get();
        
        return view('quiz.start', compact('questions', 'type'));
    }

    public function submit(Request $request, $type)
    {
        $request->validate([
            'answers' => 'required|array',
        ]);

        $questions = Question::byType($type)->active()->ordered()->get();
        $totalScore = 0;
        $maxScore = 0;

        // Calculate DASS-21 scores (questions 1-7: stress, 8-14: anxiety, 15-21: depression)
        $dass21Stress = 0;
        $dass21Anxiety = 0;
        $dass21Depression = 0;
        
        // Calculate MSPSS scores (family social support)
        $familySocialScore = 0;
        $familySocialCount = 0;

        foreach ($questions as $question) {
            $minScore = $question->getMinScore();
            $maxScoreForQuestion = $question->getMaxScore();
            $maxScore += $maxScoreForQuestion;

            $validationRules = [
                'answers.' . $question->id => "required|integer|min:{$minScore}|max:{$maxScoreForQuestion}",
            ];
            $request->validate($validationRules);

            $score = $request->answers[$question->id] ?? $minScore;
            $totalScore += $score;

            Answer::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'question_id' => $question->id,
                ],
                [
                    'score' => $score,
                ]
            );

            // Calculate DASS-21 section scores
            if ($type === 'family_social' && $question->scale_type === 'dass21') {
                // DASS-21 questions: stress (1-7), anxiety (8-14), depression (15-21)
                if ($question->order >= 1 && $question->order <= 7) {
                    $dass21Stress += $score;
                } elseif ($question->order >= 8 && $question->order <= 14) {
                    $dass21Anxiety += $score;
                } elseif ($question->order >= 15 && $question->order <= 21) {
                    $dass21Depression += $score;
                }
            }
            
            // Calculate Family Social Support (MSPSS) score
            if ($type === 'family_social' && $question->scale_type === 'likert_5') {
                $familySocialScore += $score;
                $familySocialCount++;
            }
        }

        // Multiply DASS-21 scores by 2 to get standard scores
        $dass21Stress *= 2;
        $dass21Anxiety *= 2;
        $dass21Depression *= 2;
        
        // Calculate average family social score
        $avgFamilySocialScore = $familySocialCount > 0 ? $familySocialScore / $familySocialCount : 0;

        // Get ML prediction for family_social quiz
        $mlPrediction = null;
        if ($type === 'family_social') {
            $mlPrediction = $this->getMLPrediction($dass21Depression, $dass21Anxiety, $dass21Stress, $avgFamilySocialScore);
        }

        // Calculate category based on ML prediction or percentage
        if ($type === 'family_social' && $mlPrediction && $mlPrediction['success']) {
            $category = $mlPrediction['prediction'];
            $feedback = $this->getMLFeedback($mlPrediction);
        } else {
            // Fallback to percentage-based calculation
            $percentage = ($totalScore / $maxScore) * 100;
            
            if ($percentage >= 75) {
                $category = 'tinggi';
                $feedback = 'Kondisi ' . $this->getTypeLabel($type) . ' Anda sangat baik. Pertahankan!';
            } elseif ($percentage >= 50) {
                $category = 'sedang';
                $feedback = 'Kondisi ' . $this->getTypeLabel($type) . ' Anda cukup baik. Tingkatkan lagi!';
            } else {
                $category = 'rendah';
                $feedback = 'Kondisi ' . $this->getTypeLabel($type) . ' Anda perlu ditingkatkan. Jangan menyerah!';
            }
        }

        // Save quiz result with ML prediction data
        QuizResult::create([
            'user_id' => Auth::id(),
            'quiz_type' => $type,
            'total_score' => $totalScore,
            'max_score' => $maxScore,
            'category' => $category,
            'feedback' => $feedback,
            'dass21_stress' => $dass21Stress ?? null,
            'dass21_anxiety' => $dass21Anxiety ?? null,
            'dass21_depression' => $dass21Depression ?? null,
            'ml_prediction' => $mlPrediction && $mlPrediction['success'] ? $mlPrediction : null,
            'completed_at' => now(),
        ]);

        return redirect()->route('quiz.result', ['type' => $type])
            ->with('success', 'Kuisioner berhasil diselesaikan!');
    }

    public function result($type)
    {
        $result = QuizResult::where('user_id', Auth::id())
            ->where('quiz_type', $type)
            ->latest()
            ->first();

        if (!$result) {
            return redirect()->route('quiz.index');
        }

        // Get section breakdown for family_social quiz
        $sectionBreakdown = null;
        if ($type === 'family_social') {
            $questions = Question::byType($type)->active()->ordered()->get();
            $userAnswers = Answer::where('user_id', Auth::id())
                ->whereIn('question_id', $questions->pluck('id'))
                ->get()
                ->keyBy('question_id');

            $familySocialQuestions = $questions->where('scale_type', 'likert_5');
            $dass21Questions = $questions->where('scale_type', 'dass21');

            $familySocialScore = 0;
            $familySocialMax = 0;
            $dass21Score = 0;
            $dass21Max = 0;

            foreach ($familySocialQuestions as $question) {
                $familySocialMax += $question->getMaxScore();
                if (isset($userAnswers[$question->id])) {
                    $familySocialScore += $userAnswers[$question->id]->score;
                }
            }

            foreach ($dass21Questions as $question) {
                $dass21Max += $question->getMaxScore();
                if (isset($userAnswers[$question->id])) {
                    $dass21Score += $userAnswers[$question->id]->score;
                }
            }

            $sectionBreakdown = [
                'family_social' => [
                    'score' => $familySocialScore,
                    'max' => $familySocialMax,
                    'percentage' => $familySocialMax > 0 ? ($familySocialScore / $familySocialMax) * 100 : 0,
                    'count' => $familySocialQuestions->count(),
                ],
                'dass21' => [
                    'score' => $dass21Score,
                    'max' => $dass21Max,
                    'percentage' => $dass21Max > 0 ? ($dass21Score / $dass21Max) * 100 : 0,
                    'count' => $dass21Questions->count(),
                ],
            ];
        }

        return view('quiz.result', compact('result', 'type', 'sectionBreakdown'));
    }

    public function history()
    {
        $results = QuizResult::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('quiz.history', compact('results'));
    }

    private function getTypeLabel($type)
    {
        return match($type) {
            'family_social' => 'Family Social Factor',
            'self_efficacy' => 'Self Efficacy',
            default => 'Kuisioner',
        };
    }

    public function getMLPrediction($depressionScore, $anxietyScore, $stressScore, $familyScore)
    {
        try {
            $apiUrl = config('services.ml_api.url', 'http://localhost:5000/predict');
            
            // Calculate MSPSS subscores (simplified - use same score for all)
            $soScore = $familyScore;
            $friendsScore = $familyScore;
            
            $data = [
                'Depression_Score' => (float) $depressionScore,
                'Anxiety_Score' => (float) $anxietyScore,
                'Stress_Score' => (float) $stressScore,
                'SO_Score' => (float) $soScore,
                'Family_Score' => (float) $familyScore,
                'Friends_Score' => (float) $friendsScore,
            ];
            
            $response = Http::timeout(10)->post($apiUrl, $data);
            
            if ($response->successful()) {
                return [
                    'success' => true,
                    'prediction' => $response->json()['prediction'] ?? null,
                    'confidence' => $response->json()['confidence'] ?? null,
                    'probabilities' => $response->json()['probabilities'] ?? [],
                ];
            }
            
            return ['success' => false, 'error' => 'ML API not responding'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    protected function getMLFeedback($mlPrediction)
    {
        $prediction = $mlPrediction['prediction'];
        $confidence = $mlPrediction['confidence'] ?? 0;
        
        $feedbacks = [
            'Normal' => 'Kondisi mental Anda dalam kategori normal. Pertahankan pola hidup sehat Anda!',
            'Depression' => 'Terdeteksi indikasi depresi. Disarankan untuk berkonsultasi dengan profesional atau mencari dukungan dari orang terdekat.',
            'Anxiety' => 'Terdeteksi indikasi kecemasan. Cobalah teknik relaksasi dan meditasi. Jangan ragu meminta bantuan profesional.',
            'Stress' => 'Terdeteksi indikasi stress. Kelola waktu dengan baik, istirahat yang cukup, dan lakukan aktivitas yang menyenangkan.',
        ];
        
        $baseFeedback = $feedbacks[$prediction] ?? 'Hasil analisis kesehatan mental Anda.';
        
        return $baseFeedback . ' (Akurasi: ' . round(($confidence * 100), 2) . '%)';
    }

    public function getDASS21Label($score, $type)
    {
        $labels = [
            'depression' => [
                [0, 9, 'Normal'],
                [10, 13, 'Mild'],
                [14, 20, 'Moderate'],
                [21, 27, 'Severe'],
                [28, 42, 'Extremely Severe'],
            ],
            'anxiety' => [
                [0, 7, 'Normal'],
                [8, 9, 'Mild'],
                [10, 14, 'Moderate'],
                [15, 19, 'Severe'],
                [20, 42, 'Extremely Severe'],
            ],
            'stress' => [
                [0, 14, 'Normal'],
                [15, 18, 'Mild'],
                [19, 25, 'Moderate'],
                [26, 33, 'Severe'],
                [34, 42, 'Extremely Severe'],
            ],
        ];

        $ranges = $labels[$type] ?? [];
        foreach ($ranges as $range) {
            if ($score >= $range[0] && $score <= $range[1]) {
                return $range[2];
            }
        }
        
        return 'Unknown';
    }
}
