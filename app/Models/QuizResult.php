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

    /**
     * Get formatted date for history
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->completed_at->format('d M Y');
    }

    /**
     * Get formatted time for history
     */
    public function getFormattedTimeAttribute(): string
    {
        return $this->completed_at->format('H:i');
    }

    /**
     * Get formatted date and time for display
     */
    public function getFormattedDateTimeAttribute(): string
    {
        return $this->completed_at->format('d M Y, H:i');
    }

    /**
     * Get icon based on quiz type
     */
    public function getIconAttribute(): string
    {
        return match($this->quiz_type) {
            'family_social' => '👨‍👩‍👧‍👦',
            'self_efficacy' => '💪',
            default => '📝',
        };
    }

    /**
     * Get type label
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->quiz_type) {
            'family_social' => 'Family Social Factor',
            'self_efficacy' => 'Self Efficacy Factor',
            default => 'Unknown',
        };
    }

    /**
     * Get category for history (abnormal or tinggi)
     */
    public function getHistoryCategoryAttribute(): string
    {
        // Jika category adalah Normal/tinggi/Depresi/Cemas/Stres langsung
        return match($this->category) {
            'Normal', 'tinggi' => 'tinggi',
            'sedang', 'rendah' => 'abnormal',
            'Depresi', 'Cemas', 'Stres' => 'abnormal',
            default => 'abnormal',
        };
    }

    /**
     * Get display result for history (use category name)
     */
    public function getHistoryResultAttribute(): string
    {
        return $this->category;
    }
}
