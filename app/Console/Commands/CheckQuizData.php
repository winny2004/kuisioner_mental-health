<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\QuizResult;
use App\Models\User;

class CheckQuizData extends Command
{
    protected $signature = 'quiz:check {--user= : Check data for specific user ID}';

    protected $description = 'Check quiz results data in database';

    public function handle()
    {
        $this->info('📊 Quiz Results Data Check');
        $this->newLine();

        $userId = $this->option('user');

        if ($userId) {
            $results = QuizResult::where('user_id', $userId)->latest()->get();
        } else {
            $results = QuizResult::latest()->get();
        }

        if ($results->isEmpty()) {
            $this->warn('⚠️  No quiz data found in database.');
            return Command::SUCCESS;
        }

        $this->table(
            ['ID', 'User', 'Type', 'Category', 'Completed At'],
            $results->map(function ($result) {
                return [
                    $result->id,
                    $result->user_id,
                    $result->quiz_type,
                    $result->category,
                    $result->completed_at->format('d M Y, H:i:s'),
                ];
            })->toArray()
        );

        $this->newLine();
        $this->info("Total Results: {$results->count()}");

        // Show user quiz counts
        if (!$userId) {
            $this->newLine();
            $this->info('👥 User Quiz Counts:');
            $users = User::has('quizResults')->get();

            foreach ($users as $user) {
                $familyCount = $user->getQuizCountByType('family_social');
                $selfCount = $user->getQuizCountByType('self_efficacy');
                $totalCount = $user->getTotalQuizCount();

                $this->line("  User {$user->id} ({$user->name}):");
                $this->line("    - Family Social: {$familyCount}");
                $this->line("    - Self Efficacy: {$selfCount}");
                $this->line("    - Total: {$totalCount}");
                $this->newLine();
            }
        }

        return Command::SUCCESS;
    }
}
