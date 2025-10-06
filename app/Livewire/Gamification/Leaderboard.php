<?php

namespace App\Livewire\Gamification;

use App\Models\User;
use App\Models\UserPoint;
use App\Models\UserStreak;
use Livewire\Component;

class Leaderboard extends Component
{
    public $topByPoints = [];
    public $topByStreaks = [];
    public $topByPosts = [];
    public $topByComments = [];
    public $currentUserRank = [];

    public function mount()
    {
        $this->loadLeaderboardData();
    }

    public function loadLeaderboardData()
    {
        $currentUserId = auth()->id();

        // Top by points
        $this->topByPoints = User::select('users.*')
            ->leftJoin('user_points', function($join) {
                $join->on('users.id', '=', 'user_points.user_id')
                     ->whereRaw('user_points.total_points = (
                         SELECT MAX(up.total_points)
                         FROM user_points up
                         WHERE up.user_id = user_points.user_id
                     )');
            })
            ->orderByRaw('COALESCE(user_points.total_points, 0) DESC')
            ->orderBy('users.created_at', 'asc')
            ->limit(10)
            ->get()
            ->map(function($user) {
                $user->total_points = $user->getTotalPoints();
                return $user;
            });

        // Top by streaks
        $this->topByStreaks = User::select('users.*')
            ->leftJoin('user_streaks', 'users.id', '=', 'user_streaks.user_id')
            ->orderByRaw('COALESCE(user_streaks.current_streak, 0) DESC')
            ->orderBy('users.created_at', 'asc')
            ->limit(10)
            ->get()
            ->map(function($user) {
                $user->current_streak = $user->getCurrentStreak();
                return $user;
            });

        // Top by posts
        $this->topByPosts = User::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->orderBy('created_at', 'asc')
            ->limit(10)
            ->get();

        // Top by comments
        $this->topByComments = User::withCount('comments')
            ->orderBy('comments_count', 'desc')
            ->orderBy('created_at', 'asc')
            ->limit(10)
            ->get();

        // Current user ranks
        if ($currentUserId) {
            $this->currentUserRank = [
                'points' => $this->getUserRank($currentUserId, 'points'),
                'streaks' => $this->getUserRank($currentUserId, 'streaks'),
                'posts' => $this->getUserRank($currentUserId, 'posts'),
                'comments' => $this->getUserRank($currentUserId, 'comments'),
            ];
        }
    }

    private function getUserRank($userId, $type)
    {
        switch ($type) {
            case 'points':
                $userPoints = User::find($userId)->getTotalPoints();
                return User::whereRaw('
                    (SELECT COALESCE(MAX(total_points), 0) FROM user_points WHERE user_id = users.id) > ?
                    OR (
                        (SELECT COALESCE(MAX(total_points), 0) FROM user_points WHERE user_id = users.id) = ?
                        AND users.created_at < (SELECT created_at FROM users WHERE id = ?)
                    )
                ', [$userPoints, $userPoints, $userId])->count() + 1;

            case 'streaks':
                $userStreak = User::find($userId)->getCurrentStreak();
                return User::leftJoin('user_streaks', 'users.id', '=', 'user_streaks.user_id')
                    ->whereRaw('COALESCE(user_streaks.current_streak, 0) > ?
                        OR (
                            COALESCE(user_streaks.current_streak, 0) = ?
                            AND users.created_at < (SELECT created_at FROM users WHERE id = ?)
                        )', [$userStreak, $userStreak, $userId])
                    ->count() + 1;

            case 'posts':
                $userPosts = User::find($userId)->posts()->count();
                return User::whereRaw('
                    (SELECT COUNT(*) FROM posts WHERE user_id = users.id) > ?
                    OR (
                        (SELECT COUNT(*) FROM posts WHERE user_id = users.id) = ?
                        AND users.created_at < (SELECT created_at FROM users WHERE id = ?)
                    )
                ', [$userPosts, $userPosts, $userId])->count() + 1;

            case 'comments':
                $userComments = User::find($userId)->comments()->count();
                return User::whereRaw('
                    (SELECT COUNT(*) FROM comments WHERE user_id = users.id) > ?
                    OR (
                        (SELECT COUNT(*) FROM comments WHERE user_id = users.id) = ?
                        AND users.created_at < (SELECT created_at FROM users WHERE id = ?)
                    )
                ', [$userComments, $userComments, $userId])->count() + 1;
        }

        return 0;
    }

    public function render()
    {
        return view('livewire.gamification.leaderboard');
    }
}
