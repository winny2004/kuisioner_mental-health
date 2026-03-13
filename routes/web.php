<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuizController;

// Landing page
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Authentication routes (guest only)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Protected routes (auth only)
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Quiz routes
    Route::prefix('quiz')->name('quiz.')->group(function () {
        Route::get('/', [QuizController::class, 'index'])->name('index');
        Route::get('/start/{type}', [QuizController::class, 'start'])->name('start');
        Route::post('/submit/{type}', [QuizController::class, 'submit'])->name('submit');
        Route::get('/result/{type}', [QuizController::class, 'result'])->name('result');
        Route::get('/result/{id}/{type}', [QuizController::class, 'resultById'])->name('resultById');
        Route::get('/history', [QuizController::class, 'history'])->name('history');
        Route::get('/history-data/{type?}', [QuizController::class, 'getHistoryData'])->name('historyData');
    });
});
