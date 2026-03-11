<?php
// app/Http/Controllers/DASSPredictionController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DASSPredictionController extends Controller
{
    private $apiUrl = 'http://localhost:5000/predict'; // Sesuaikan dengan URL API Python Anda
    
    public function predict(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'depression_score' => 'required|numeric|min:0|max:42',
            'anxiety_score' => 'required|numeric|min:0|max:42',
            'stress_score' => 'required|numeric|min:0|max:42',
            'so_score' => 'required|numeric|min:1|max:5',
            'family_score' => 'required|numeric|min:1|max:5',
            'friends_score' => 'required|numeric|min:1|max:5',
        ]);
        
        // Format data sesuai dengan yang diharapkan model
        $data = [
            'Depression_Score' => (float) $request->depression_score,
            'Anxiety_Score' => (float) $request->anxiety_score,
            'Stress_Score' => (float) $request->stress_score,
            'SO_Score' => (float) $request->so_score,
            'Family_Score' => (float) $request->family_score,
            'Friends_Score' => (float) $request->friends_score,
        ];
        
        try {
            // Kirim request ke API Python
            $response = Http::post($this->apiUrl, $data);
            
            if ($response->successful()) {
                $result = $response->json();
                
                // Simpan ke database jika perlu
                // $this->savePrediction($data, $result);
                
                return response()->json([
                    'success' => true,
                    'prediction' => $result['prediction'],
                    'confidence' => $result['confidence'],
                    'probabilities' => $result['probabilities']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mendapatkan prediksi dari model'
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function showForm()
    {
        return view('dass-prediction.form');
    }
}