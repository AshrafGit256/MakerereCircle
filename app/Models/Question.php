<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question_text',
        'question_type',
        'options',
        'correct_answer',
        'explanation',
        'points',
        'time_limit',
        'order',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'options' => 'array',
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function isCorrect(?string $answer): bool
    {
        if ($answer === null) return false;
        if ($this->question_type === 'true_false') {
            return strtolower(trim($answer)) === strtolower(trim($this->correct_answer));
        }
        return (string)$answer === (string)$this->correct_answer;
    }
}
