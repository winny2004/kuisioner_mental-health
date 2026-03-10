<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        // Calculate category based on percentage of max possible score
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

        // Save quiz result
        QuizResult::create([
            'user_id' => Auth::id(),
            'quiz_type' => $type,
            'total_score' => $totalScore,
            'max_score' => $maxScore,
            'category' => $category,
            'feedback' => $feedback,
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
}
