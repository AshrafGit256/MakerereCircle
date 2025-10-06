<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'course_unit_id',
        'lecturer_id',
        'timetable_id',
        'lecture_content',
        'content_file_path',
        'status',
        'total_questions',
        'time_limit',
        'max_attempts',
        'is_active',
        'scheduled_at',
        'started_at',
        'ended_at',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'settings' => 'array',
    ];

    // Relationships
    public function courseUnit(): BelongsTo
    {
        return $this->belongsTo(CourseUnit::class);
    }

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }

    public function timetable(): BelongsTo
    {
        return $this->belongsTo(Timetable::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    // Helpers
    public function isPublished(): bool
    {
        return $this->status === 'published' || $this->status === 'active';
    }

    public function canAttemptBy(User $user): bool
    {
        if (!$this->is_active || !$this->isPublished()) {
            return false;
        }
        // If max attempts is limited, ensure user hasn't exceeded
        $attempts = $this->attempts()->where('user_id', $user->id)->count();
        if ($this->max_attempts > 0 && $attempts >= $this->max_attempts) {
            return false;
        }
        return true;
    }
}
