<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlaskApiService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.flask.url', 'http://127.0.0.1:5000');
    }

    /**
     * Send quiz data to Flask API for prediction
     */
    public function predictMentalHealth(array $quizData)
    {
        try {
            // DEBUG: Log outgoing request
            \Log::info('Flask API Request:', $quizData);

            $response = Http::timeout(10)->post("{$this->baseUrl}/api/predict", $quizData);

            // DEBUG: Log response
            \Log::info('Flask API Response Status: ' . $response->status());
            \Log::info('Flask API Response Body:', $response->json() ?: []);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            }

            Log::error('Flask API Error: ' . $response->status() . ' - ' . $response->body());
            return [
                'success' => false,
                'error' => 'API request failed',
                'status' => $response->status(),
            ];

        } catch (\Exception $e) {
            Log::error('Flask API Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check Flask API health
     */
    public function checkHealth()
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/api/health");

            return [
                'success' => $response->successful(),
                'data' => $response->json(),
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Transform Laravel quiz data to Flask API format
     */
    public function transformQuizData($answers, $questions)
    {
        $data = [];
        $questionMap = $questions->keyBy('id');

        // Separate questions by scale_type
        $mSPSSQuestions = $questions->where('scale_type', 'likert_5')->values();
        $dassQuestions = $questions->where('scale_type', 'dass21')->values();

        // Sort by order to ensure correct mapping
        $mSPSSQuestions = $mSPSSQuestions->sortBy('order')->values();
        $dassQuestions = $dassQuestions->sortBy('order')->values();

        // Map MSPSS answers (FS1-FS12)
        foreach ($mSPSSQuestions as $index => $question) {
            $score = $answers[$question->id] ?? 3; // Default Netral
            $fsNumber = $index + 1; // FS1-FS12
            $data["fs{$fsNumber}"] = $this->mapLikertToText($score);
        }

        // Map DASS-21 answers (DAS1-DAS21)
        foreach ($dassQuestions as $index => $question) {
            $score = $answers[$question->id] ?? 0; // Default 0
            $dasNumber = $index + 1; // DAS1-DAS21
            $data["das{$dasNumber}"] = intval($score);
        }

        // Fill missing values with defaults (in case questions are less than expected)
        for ($i = 1; $i <= 12; $i++) {
            if (!isset($data["fs{$i}"])) {
                $data["fs{$i}"] = "Netral";
            }
        }

        for ($i = 1; $i <= 21; $i++) {
            if (!isset($data["das{$i}"])) {
                $data["das{$i}"] = 0;
            }
        }

        return $data;
    }

    /**
     * Map numeric score to Likert text for MSPSS
     */
    private function mapLikertToText($score)
    {
        return match(intval($score)) {
            1 => 'Sangat tidak setuju',
            2 => 'Tidak setuju',
            3 => 'Netral',
            4 => 'Setuju',
            5 => 'Sangat setuju',
            default => 'Netral',
        };
    }

    public function transformSelfEfficacyData($answers, $questions)
    {
        $data = [];

        // =========================
        // SELF EFFICACY (10 soal)
        // =========================

        $seQuestions = $questions
            ->where('section', 'self_efficacy')
            ->sortBy('order')
            ->values();

        foreach ($seQuestions as $index => $question) {

            $score = intval($answers[$question->id] ?? 1);

            $seNumber = $index + 1;

            $data["SE" . str_pad($seNumber, 2, '0', STR_PAD_LEFT)] = $score;
        }

        // =========================
        // WELL BEING (18 soal)
        // =========================

        $wbQuestions = $questions
            ->where('section', 'well_being')
            ->sortBy('order')
            ->values();

        foreach ($wbQuestions as $index => $question) {

            $score = intval($answers[$question->id] ?? 1);

            $qNumber = $index + 1;

            $data["Q{$qNumber}"] = $score;
        }

        return $data;
    }
}
