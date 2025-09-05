<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'criteria_type',
        'criteria_value',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function userBadges(): HasMany
    {
        return $this->hasMany(UserBadge::class);
    }

    // Check if user has earned this badge
    public function checkCriteria($user)
    {
        switch ($this->criteria_type) {
            case 'posts_count':
                return $user->posts()->count() >= $this->criteria_value;
            case 'comments_count':
                return $user->comments()->count() >= $this->criteria_value;
            case 'points_total':
                return UserPoint::getUserTotalPoints($user->id) >= $this->criteria_value;
            case 'polls_count':
                return $user->posts()->where('type', 'poll')->count() >= $this->criteria_value;
            case 'streak_days':
                $streak = UserStreak::where('user_id', $user->id)->first();
                return $streak && $streak->current_streak >= $this->criteria_value;
            default:
                return false;
        }
    }

    // Award badge to user if criteria met
    public function awardToUser($user)
    {
        if (!$this->checkCriteria($user)) {
            return false;
        }

        // Check if user already has this badge
        if ($user->badges()->where('badge_id', $this->id)->exists()) {
            return false;
        }

        UserBadge::create([
            'user_id' => $user->id,
            'badge_id' => $this->id,
            'earned_at' => now()
        ]);

        return true;
    }
}
