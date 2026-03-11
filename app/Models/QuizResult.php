<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizResult extends Model
{
    protected $fillable = ['user_id', 'quiz_type', 'total_score', 'max_score', 'category', 'feedback', 'completed_at', 'dass21_stress', 'dass21_anxiety', 'dass21_depression', 'ml_prediction'];

    protected $casts = [
        'completed_at' => 'datetime',
        'ml_prediction' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getCategoryLabel(): string
    {
        return match($this->category) {
            'tinggi' => 'Tinggi',
            'sedang' => 'Sedang',
            'rendah' => 'Rendah',
            default => 'Unknown',
        };
    }
}
