<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\QuizResult;
use App\Models\Answer;

class ResetQuizData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quiz:reset {--all : Reset all quiz data} {--user= : Reset data for specific user ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset quiz results and answers for testing purposes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Resetting Quiz Data...');
        $this->newLine();

        // Confirm before proceeding
        if (!$this->confirm('⚠️  This will delete quiz results and answers. Continue?')) {
            $this->warn('Operation cancelled.');
            return Command::SUCCESS;
        }

        $userId = $this->option('user');
        $resetAll = $this->option('all');

        if ($resetAll) {
            // Reset all data
            $resultsCount = QuizResult::count();
            $answersCount = Answer::count();

            QuizResult::truncate();
            Answer::truncate();

            $this->info("✅ Deleted {$resultsCount} quiz results");
            $this->info("✅ Deleted {$answersCount} answers");
            $this->newLine();
            $this->info('✨ All quiz data has been reset successfully!');

        } elseif ($userId) {
            // Reset for specific user
            $userResults = QuizResult::where('user_id', $userId)->get();
            $resultsCount = $userResults->count();

            if ($resultsCount === 0) {
                $this->warn("⚠️  No quiz data found for user ID: {$userId}");
                return Command::SUCCESS;
            }

            // Delete answers for this user
            $answersCount = Answer::where('user_id', $userId)->delete();

            // Delete results for this user
            QuizResult::where('user_id', $userId)->delete();

            $this->info("✅ Deleted {$resultsCount} quiz results for user ID: {$userId}");
            $this->info("✅ Deleted {$answersCount} answers for user ID: {$userId}");
            $this->newLine();
            $this->info('✨ Quiz data for user has been reset successfully!');

        } else {
            // Reset for latest user
            $latestResult = QuizResult::latest()->first();

            if (!$latestResult) {
                $this->warn('⚠️  No quiz data found in database.');
                return Command::SUCCESS;
            }

            $userId = $latestResult->user_id;
            $resultsCount = QuizResult::where('user_id', $userId)->count();

            // Delete answers for this user
            $answersCount = Answer::where('user_id', $userId)->delete();

            // Delete results for this user
            QuizResult::where('user_id', $userId)->delete();

            $this->info("✅ Deleted {$resultsCount} quiz results for user ID: {$userId}");
            $this->info("✅ Deleted {$answersCount} answers for user ID: {$userId}");
            $this->newLine();
            $this->info('✨ Quiz data for user has been reset successfully!');
        }

        $this->newLine();
        $this->info('💡 You can now retake the quiz!');

        return Command::SUCCESS;
    }
}
