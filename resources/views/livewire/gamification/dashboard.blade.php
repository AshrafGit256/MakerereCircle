<div class="max-w-6xl mx-auto p-6 space-y-8">
    {{-- Header --}}
    <div class="text-center">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">🎯 Gamification Dashboard</h1>
        <p class="text-gray-600">Track your progress and earn rewards!</p>
    </div>

    {{-- Stats Overview --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        {{-- Total Points --}}
        <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-lg p-6 text-white text-center">
            <div class="text-3xl font-bold">{{ number_format($totalPoints) }}</div>
            <div class="text-yellow-100">Total Points</div>
            <div class="text-xs mt-2">⭐ Earned through activity</div>
        </div>

        {{-- Current Streak --}}
        <div class="bg-gradient-to-r from-red-400 to-red-600 rounded-lg p-6 text-white text-center">
            <div class="text-3xl font-bold">{{ $currentStreak }}</div>
            <div class="text-red-100">Day Streak</div>
            <div class="text-xs mt-2">{{ $streakStatus }}</div>
        </div>

        {{-- Longest Streak --}}
        <div class="bg-gradient-to-r from-blue-400 to-blue-600 rounded-lg p-6 text-white text-center">
            <div class="text-3xl font-bold">{{ $longestStreak }}</div>
            <div class="text-blue-100">Best Streak</div>
            <div class="text-xs mt-2">🔥 Personal record</div>
        </div>

        {{-- Badges Earned --}}
        <div class="bg-gradient-to-r from-green-400 to-green-600 rounded-lg p-6 text-white text-center">
            <div class="text-3xl font-bold">{{ $earnedBadges->count() }}</div>
            <div class="text-green-100">Badges Earned</div>
            <div class="text-xs mt-2">🏆 Achievements unlocked</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Earned Badges --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 flex items-center">
                <span class="mr-2">🏆</span> Earned Badges
            </h2>

            @if($earnedBadges->count() > 0)
                <div class="grid grid-cols-2 gap-4">
                    @foreach($earnedBadges as $badge)
                        <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-lg p-4 text-center">
                            <div class="text-2xl mb-2">{{ $badge->icon }}</div>
                            <div class="font-semibold text-green-800">{{ $badge->name }}</div>
                            <div class="text-sm text-green-600">{{ $badge->description }}</div>
                            <div class="text-xs text-green-500 mt-1">
                                Earned {{ $badge->pivot->earned_at->diffForHumans() }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-500 py-8">
                    <div class="text-4xl mb-4">🎯</div>
                    <p>No badges earned yet!</p>
                    <p class="text-sm">Keep engaging to unlock achievements</p>
                </div>
            @endif
        </div>

        {{-- Available Badges --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 flex items-center">
                <span class="mr-2">🎯</span> Available Badges
            </h2>

            @if($availableBadges->count() > 0)
                <div class="space-y-3">
                    @foreach($availableBadges as $badge)
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="text-2xl">{{ $badge->icon }}</div>
                                    <div>
                                        <div class="font-semibold text-gray-800">{{ $badge->name }}</div>
                                        <div class="text-sm text-gray-600">{{ $badge->description }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-500">
                                        @switch($badge->criteria_type)
                                            @case('posts_count')
                                                {{ $user->posts()->count() }}/{{ $badge->criteria_value }} posts
                                                @break
                                            @case('comments_count')
                                                {{ $user->comments()->count() }}/{{ $badge->criteria_value }} comments
                                                @break
                                            @case('points_total')
                                                {{ $totalPoints }}/{{ $badge->criteria_value }} points
                                                @break
                                            @case('polls_count')
                                                {{ $user->posts()->where('type', 'poll')->count() }}/{{ $badge->criteria_value }} polls
                                                @break
                                            @case('streak_days')
                                                {{ $currentStreak }}/{{ $badge->criteria_value }} day streak
                                                @break
                                        @endswitch
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-500 py-8">
                    <div class="text-4xl mb-4">🎉</div>
                    <p>All badges earned!</p>
                    <p class="text-sm">You're a platform champion!</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4 flex items-center">
            <span class="mr-2">📈</span> Recent Activity
        </h2>

        @if($recentPoints->count() > 0)
            <div class="space-y-3">
                @foreach($recentPoints as $point)
                    <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-b-0">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                @switch($point->action_type)
                                    @case('post_created')
                                        📝
                                        @break
                                    @case('comment_given')
                                        💬
                                        @break
                                    @case('like_given')
                                        ❤️
                                        @break
                                    @case('like_received')
                                        👍
                                        @break
                                    @case('comment_received')
                                        💭
                                        @break
                                    @default
                                        ⭐
                                @endswitch
                            </div>
                            <div>
                                <div class="font-medium text-gray-800">{{ $point->description }}</div>
                                <div class="text-sm text-gray-500">{{ $point->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-semibold text-green-600">+{{ $point->points }}</div>
                            <div class="text-sm text-gray-500">{{ $point->total_points }} total</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-gray-500 py-8">
                <div class="text-4xl mb-4">🚀</div>
                <p>No activity yet!</p>
                <p class="text-sm">Start engaging to earn your first points</p>
            </div>
        @endif
    </div>

    {{-- Points System Explanation --}}
    <div class="bg-blue-50 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-800 mb-4">💡 How to Earn Points</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="bg-white rounded-lg p-4">
                <div class="text-2xl mb-2">📝</div>
                <div class="font-semibold">Create Post</div>
                <div class="text-sm text-gray-600">+10 points (base)</div>
                <div class="text-xs text-gray-500">+5 bonus for YouTube links</div>
                <div class="text-xs text-gray-500">+5 bonus for polls</div>
            </div>
            <div class="bg-white rounded-lg p-4">
                <div class="text-2xl mb-2">💬</div>
                <div class="font-semibold">Leave Comment</div>
                <div class="text-sm text-gray-600">+2 points</div>
                <div class="text-xs text-gray-500">Help build community</div>
            </div>
            <div class="bg-white rounded-lg p-4">
                <div class="text-2xl mb-2">❤️</div>
                <div class="font-semibold">Like Content</div>
                <div class="text-sm text-gray-600">+1 point</div>
                <div class="text-xs text-gray-500">Show appreciation</div>
            </div>
            <div class="bg-white rounded-lg p-4">
                <div class="text-2xl mb-2">👍</div>
                <div class="font-semibold">Receive Like</div>
                <div class="text-sm text-gray-600">+2 points</div>
                <div class="text-xs text-gray-500">Content is appreciated</div>
            </div>
            <div class="bg-white rounded-lg p-4">
                <div class="text-2xl mb-2">💭</div>
                <div class="font-semibold">Receive Comment</div>
                <div class="text-sm text-gray-600">+3 points</div>
                <div class="text-xs text-gray-500">Spark conversations</div>
            </div>
            <div class="bg-white rounded-lg p-4">
                <div class="text-2xl mb-2">🔥</div>
                <div class="font-semibold">Daily Login</div>
                <div class="text-sm text-gray-600">+5 points</div>
                <div class="text-xs text-gray-500">Maintain your streak</div>
            </div>
        </div>
    </div>
</div>
