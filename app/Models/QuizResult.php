<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizResult extends Model
{
    protected $fillable = ['user_id', 'quiz_type', 'total_score', 'max_score', 'category', 'feedback', 'prediction_data', 'completed_at'];

    protected $casts = [
        'completed_at' => 'datetime',
        'prediction_data' => 'array',
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
            'Normal' => 'Normal',
            'Depresi' => 'Depresi',
            'Cemas' => 'Cemas',
            'Stres' => 'Stres',
            default => 'Unknown',
        };
    }
}
