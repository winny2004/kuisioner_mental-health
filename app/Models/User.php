<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get all quiz results for this user
     */
    public function quizResults()
    {
        return $this->hasMany(\App\Models\QuizResult::class);
    }

    /**
     * Count quiz attempts by type
     */
    public function getQuizCountByType($type)
    {
        return $this->quizResults()
            ->where('quiz_type', $type)
            ->count();
    }

    /**
     * Get total quiz count (all types)
     */
    public function getTotalQuizCount()
    {
        return $this->quizResults()->count();
    }
}
