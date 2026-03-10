<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = ['type', 'scale_type', 'question_text', 'order', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getScaleOptions(): array
    {
        return match($this->scale_type) {
            'likert_5' => [
                ['value' => 1, 'label' => 'Sangat Tidak Setuju'],
                ['value' => 2, 'label' => 'Tidak Setuju'],
                ['value' => 3, 'label' => 'Netral'],
                ['value' => 4, 'label' => 'Setuju'],
                ['value' => 5, 'label' => 'Sangat Setuju'],
            ],
            'likert_4' => [
                ['value' => 1, 'label' => 'Sangat Tidak Setuju'],
                ['value' => 2, 'label' => 'Tidak Setuju'],
                ['value' => 3, 'label' => 'Setuju'],
                ['value' => 4, 'label' => 'Sangat Setuju'],
            ],
            'likert_7' => [
                ['value' => 1, 'label' => 'Sangat Setuju'],
                ['value' => 2, 'label' => 'Setuju'],
                ['value' => 3, 'label' => 'Sedikit Setuju'],
                ['value' => 4, 'label' => 'Netral'],
                ['value' => 5, 'label' => 'Sedikit Tidak Setuju'],
                ['value' => 6, 'label' => 'Tidak Setuju'],
                ['value' => 7, 'label' => 'Sangat Tidak Setuju'],
            ],
            'dass21' => [
                ['value' => 0, 'label' => 'Tidak berlaku bagi saya sama sekali'],
                ['value' => 1, 'label' => 'Berlaku bagi saya sampai tingkat tertentu, atau sebagian waktu'],
                ['value' => 2, 'label' => 'Berlaku bagi saya sampai tingkat tertentu, atau sebagian besar waktu'],
                ['value' => 3, 'label' => 'Berlaku bagi saya sangat banyak, atau sebagian besar waktu'],
            ],
            default => [],
        };
    }

    public function getMinScore(): int
    {
        return match($this->scale_type) {
            'likert_5' => 1,
            'likert_4' => 1,
            'likert_7' => 1,
            'dass21' => 0,
            default => 1,
        };
    }

    public function getMaxScore(): int
    {
        return match($this->scale_type) {
            'likert_5' => 5,
            'likert_4' => 4,
            'likert_7' => 7,
            'dass21' => 3,
            default => 4,
        };
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
