<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class UserStreak extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'current_streak',
        'longest_streak',
        'last_activity_date',
        'streak_data'
    ];

    protected $casts = [
        'last_activity_date' => 'date',
        'streak_data' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Update streak when user performs activity
    public function updateStreak()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        if ($this->last_activity_date) {
            if ($this->last_activity_date->isSameDay($today)) {
                // Already active today, no change
                return;
            } elseif ($this->last_activity_date->isSameDay($yesterday)) {
                // Consecutive day, increment streak
                $this->current_streak++;
            } else {
                // Streak broken, reset to 1
                $this->current_streak = 1;
            }
        } else {
            // First activity, start streak at 1
            $this->current_streak = 1;
        }

        // Update longest streak if current is higher
        if ($this->current_streak > $this->longest_streak) {
            $this->longest_streak = $this->current_streak;
        }

        $this->last_activity_date = $today;
        $this->save();
    }

    // Check if streak is still active (within last 24 hours)
    public function isStreakActive()
    {
        if (!$this->last_activity_date) {
            return false;
        }

        $hoursSinceLastActivity = Carbon::now()->diffInHours($this->last_activity_date);
        return $hoursSinceLastActivity <= 24;
    }

    // Get streak status message
    public function getStreakStatus()
    {
        if (!$this->isStreakActive()) {
            return 'Streak expired - post something to restart!';
        }

        $hoursLeft = 24 - Carbon::now()->diffInHours($this->last_activity_date);

        if ($hoursLeft > 1) {
            return "{$hoursLeft} hours left to maintain streak";
        } else {
            return "Less than 1 hour left to maintain streak";
        }
    }

    // Static method to get or create streak for user
    public static function getOrCreateForUser($userId)
    {
        return self::firstOrCreate(
            ['user_id' => $userId],
            [
                'current_streak' => 0,
                'longest_streak' => 0,
                'last_activity_date' => null,
                'streak_data' => []
            ]
        );
    }
}
