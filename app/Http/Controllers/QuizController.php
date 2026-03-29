<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Question;
use App\Models\Answer;
use App\Models\QuizResult;
use App\Services\FlaskApiService;

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
        }

        // Calculate category based on percentage of max possible score (fallback)
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

        // Get Flask API prediction for family_social quiz
        $predictionData = null;
        $aiPrediction = null;

        if ($type === 'family_social') {
            try {
                $flaskService = new FlaskApiService();
                $quizData = $flaskService->transformQuizData($request->answers, $questions);
                $predictionResult = $flaskService->predictMentalHealth($quizData);

                if ($predictionResult['success']) {
                    $predictionData = $predictionResult['data'];
                    $aiPrediction = $predictionData['prediction'] ?? null;

                    // Translate AI prediction to Indonesian for category
                    // Gunakan prediction utama (Stress, Depression, Anxiety, Normal)
                    $categoryMap = [
                        'Normal' => 'Normal',
                        'Depression' => 'Depresi',
                        'Anxiety' => 'Cemas',
                        'Stress' => 'Stres'
                    ];

                    if ($aiPrediction && isset($categoryMap[$aiPrediction])) {
                        $category = $categoryMap[$aiPrediction];
                    }

                    // Override feedback with AI prediction
                    $feedback = $this->generateAIFeedback($predictionData);
                }
            } catch (\Exception $e) {
                \Log::error('Flask API Error: ' . $e->getMessage());
                // Continue with default feedback if API fails
            }
        }

        if ($type === 'self_efficacy') {
            try {
                $flaskService = new FlaskApiService();
                /* SELF EFFICACY ML */
                $quizData = $flaskService->transformSelfEfficacyData(
                    $request->answers,
                    $questions
                );
                $predictionResult = $flaskService->predictMentalHealth($quizData);

                if ($predictionResult['success']) {
                    $predictionData = $predictionResult['data'];
                    $aiPrediction = $predictionData['prediction'] ?? null;

                    if ($aiPrediction) {
                        $category = $aiPrediction; 
                    }

                    $feedback = $predictionData['explanation'] ?? $feedback;

                }

            } catch (\Exception $e) {
                \Log::error('Flask API Error Self-Efficacy: ' . $e->getMessage());
            }

        }

        // Save quiz result
        $result = QuizResult::create([
            'user_id' => Auth::id(),
            'quiz_type' => $type,
            'total_score' => $totalScore,
            'max_score' => $maxScore,
            'category' => $category,
            'feedback' => $feedback,
            'prediction_data' => $predictionData,
            'completed_at' => now(),
        ]);

        return redirect()->route('quiz.resultById', ['id' => $result->id, 'type' => $type])
            ->with('success', 'Kuisioner berhasil diselesaikan!');
    }

    public function result($type)
    {
        $result = QuizResult::where('user_id', Auth::id())
            ->where('quiz_type', $type)
            ->latest('completed_at')
            ->first();

        if (!$result) {
            return redirect()->route('quiz.index');
        }

        return $this->renderResultView($result, $type);
    }

    public function resultById($id, $type)
    {
        $result = QuizResult::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('quiz_type', $type)
            ->first();

        if (!$result) {
            return redirect()->route('quiz.index');
        }

        return $this->renderResultView($result, $type);
    }

    private function renderResultView($result, $type)
    {
        // Get section breakdown for self_efficacy quiz only
        // For family_social, we use AI prediction data instead
        $sectionBreakdown = null;
        if ($type === 'self_efficacy') {
            $questions = Question::byType($type)->active()->ordered()->get();
            $userAnswers = Answer::where('user_id', Auth::id())
                ->whereIn('question_id', $questions->pluck('id'))
                ->get()
                ->keyBy('question_id');

            $selfEfficacyQuestions = $questions->where('scale_type', 'likert_4');
            $wellBeingQuestions = $questions->where('scale_type', 'likert_7');

            $selfEfficacyScore = 0;
            $selfEfficacyMax = 0;
            $wellBeingScore = 0;
            $wellBeingMax = 0;

            foreach ($selfEfficacyQuestions as $question) {
                $selfEfficacyMax += $question->getMaxScore();
                if (isset($userAnswers[$question->id])) {
                    $selfEfficacyScore += $userAnswers[$question->id]->score;
                }
            }

            foreach ($wellBeingQuestions as $question) {
                $wellBeingMax += $question->getMaxScore();
                if (isset($userAnswers[$question->id])) {
                    $wellBeingScore += $userAnswers[$question->id]->score;
                }
            }

            $sectionBreakdown = [
                'self_efficacy' => [
                    'score' => $selfEfficacyScore,
                    'max' => $selfEfficacyMax,
                    'percentage' => $selfEfficacyMax > 0 ? ($selfEfficacyScore / $selfEfficacyMax) * 100 : 0,
                    'count' => $selfEfficacyQuestions->count(),
                ],
                'well_being' => [
                    'score' => $wellBeingScore,
                    'max' => $wellBeingMax,
                    'percentage' => $wellBeingMax > 0 ? ($wellBeingScore / $wellBeingMax) * 100 : 0,
                    'count' => $wellBeingQuestions->count(),
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

    /**
     * Get quiz history data for API (used in result page)
     */
    public function getHistoryData($type = null)
    {
        $query = QuizResult::where('user_id', Auth::id());

        if ($type) {
            $query->where('quiz_type', $type);
        }

        return $query->latest()->limit(10)->get()->map(function ($result) {
            return [
                'id' => $result->id,
                'date' => $result->formatted_date,
                'time' => $result->formatted_time,
                'datetime' => $result->formatted_datetime,
                'type' => $result->quiz_type,
                'type_label' => $result->type_label,
                'icon' => $result->icon,
                'result' => $result->category,
                'result_label' => $result->history_result,
                'category' => $result->history_category,
            ];
        });
    }

    private function getTypeLabel($type)
    {
        return match($type) {
            'family_social' => 'Family Social Factor',
            'self_efficacy' => 'Self Efficacy',
            default => 'Kuisioner',
        };
    }

    /**
     * Generate feedback based on AI prediction
     */
    private function generateAIFeedback($predictionData)
    {
        $prediction = $predictionData['prediction'] ?? 'Unknown';
        $manualCalc = $predictionData['manual_calculation'] ?? 'Unknown';
        $scores = $predictionData['scores'] ?? [];

        $feedback = "Berdasarkan analisis AI, kondisi mental health Anda terdeteksi: **{$prediction}**.\n\n";

        // Add detailed information based on prediction
        switch ($prediction) {
            case 'Normal':
                $feedback .= "Kondisi mental Anda berada dalam kategori normal. Pertahankan pola hidup sehat dan dukungan sosial yang baik!";
                break;
            case 'Depression':
                $feedback .= "Terdeteksi indikasi gejala depresi. Jangan ragu untuk mencari bantuan profesional. Anda tidak sendirian.";
                break;
            case 'Anxiety':
                $feedback .= "Terdeteksi indikasi gejala kecemasan. Teknik relaksasi dan meditasi dapat membantu. Konsultasikan dengan profesional jika diperlukan.";
                break;
            case 'Stress':
                $feedback .= "Terdeteksi tingkat stres yang perlu diperhatikan. Luangkan waktu untuk self-care dan aktivitas yang menenangkan.";
                break;
        }

        return $feedback;
    }
}
