<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get latest quiz result
        $latestResult = $user->quizResults()
            ->latest('completed_at')
            ->first();
        
        // Get total quiz count
        $totalQuizCount = $user->getTotalQuizCount();
        
        return view('home', [
            'latestResult' => $latestResult,
            'totalQuizCount' => $totalQuizCount,
        ]);
    }
}
