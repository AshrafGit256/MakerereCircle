<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'user_id',
        'attempt_number',
        'status',
        'current_question',
        'total_questions',
        'correct_answers',
        'total_score',
        'max_possible_score',
        'time_taken',
        'started_at',
        'completed_at',
        'answers',
        'question_times',
        'device_fingerprint',
        'ip_address',
        'feedback',
    ];

    protected $casts = [
        'answers' => 'array',
        'question_times' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function addAnswer(int $questionId, string $answer, int $timeSpent, bool $correct, int $pointsEarned): void
    {
        $answers = $this->answers ?? [];
        $answers[$questionId] = [
            'answer' => $answer,
            'time' => $timeSpent,
            'correct' => $correct,
            'points' => $pointsEarned,
            'answered_at' => now()->toISOString(),
        ];
        $this->answers = $answers;

        $times = $this->question_times ?? [];
        $times[$questionId] = $timeSpent;
        $this->question_times = $times;

        if ($correct) {
            $this->correct_answers++;
        }
        $this->total_score += $pointsEarned;
        $this->time_taken += $timeSpent;
        $this->current_question++;
    }
}
