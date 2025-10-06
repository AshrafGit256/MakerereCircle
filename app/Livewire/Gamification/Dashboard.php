<?php

namespace App\Livewire\Gamification;

use App\Models\UserPoint;
use App\Models\Badge;
use App\Models\UserStreak;
use Livewire\Component;

class Dashboard extends Component
{
    public $user;
    public $totalPoints;
    public $currentStreak;
    public $longestStreak;
    public $recentPoints;
    public $earnedBadges;
    public $availableBadges;
    public $streakStatus;

    public function mount()
    {
        $this->user = auth()->user();
        $this->loadUserData();
    }

    public function loadUserData()
    {
        // Load user stats
        $this->totalPoints = $this->user->getTotalPoints();
        $this->currentStreak = $this->user->getCurrentStreak();
        $this->longestStreak = $this->user->getLongestStreak();

        // Load streak status
        $streak = UserStreak::getOrCreateForUser($this->user->id);
        $this->streakStatus = $streak->getStreakStatus();

        // Load recent points (last 10)
        $this->recentPoints = $this->user->points()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Load earned badges
        $this->earnedBadges = $this->user->badges()
            ->withPivot('earned_at')
            ->orderBy('user_badges.earned_at', 'desc')
            ->get();

        // Load available badges (not yet earned)
        $earnedBadgeIds = $this->earnedBadges->pluck('id');
        $this->availableBadges = Badge::where('is_active', true)
            ->whereNotIn('id', $earnedBadgeIds)
            ->get();
    }

    public function render()
    {
        return view('livewire.gamification.dashboard');
    }
}
