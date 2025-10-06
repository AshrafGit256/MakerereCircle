<div class="max-w-6xl mx-auto p-6 space-y-8">
    {{-- Header --}}
    <div class="text-center">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">🏆 Leaderboard</h1>
        <p class="text-gray-600">See how you rank against other users!</p>
    </div>

    {{-- Current User Stats --}}
    @if(auth()->check() && !empty($currentUserRank))
        <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg p-6 text-white">
            <h2 class="text-xl font-semibold mb-4">Your Rankings</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold">#{{ $currentUserRank['points'] ?? 'N/A' }}</div>
                    <div class="text-purple-100">Points</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold">#{{ $currentUserRank['streaks'] ?? 'N/A' }}</div>
                    <div class="text-purple-100">Streaks</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold">#{{ $currentUserRank['posts'] ?? 'N/A' }}</div>
                    <div class="text-purple-100">Posts</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold">#{{ $currentUserRank['comments'] ?? 'N/A' }}</div>
                    <div class="text-purple-100">Comments</div>
                </div>
            </div>
        </div>
    @endif

    {{-- Leaderboards Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        {{-- Top by Points --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 flex items-center">
                <span class="mr-2">⭐</span> Top by Points
            </h2>

            @if($topByPoints->count() > 0)
                <div class="space-y-3">
                    @foreach($topByPoints as $index => $user)
                        <div class="flex items-center justify-between p-3 rounded-lg {{ $user->id === auth()->id() ? 'bg-blue-50 border border-blue-200' : 'bg-gray-50' }}">
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full font-bold
                                    {{ $index === 0 ? 'bg-yellow-400 text-yellow-900' :
                                       ($index === 1 ? 'bg-gray-400 text-gray-900' :
                                       ($index === 2 ? 'bg-orange-400 text-orange-900' : 'bg-gray-200 text-gray-700')) }}">
                                    {{ $index + 1 }}
                                </div>
                                <x-avatar src="{{ $user->getImage() }}" class="w-10 h-10" />
                                <div>
                                    <div class="font-semibold text-gray-800">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->total_points }} points</div>
                                </div>
                            </div>
                            @if($user->id === auth()->id())
                                <span class="text-blue-600 font-semibold">You</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-500 py-8">
                    <div class="text-4xl mb-4">⭐</div>
                    <p>No users with points yet!</p>
                    <p class="text-sm">Start engaging to earn points</p>
                </div>
            @endif
        </div>

        {{-- Top by Streaks --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 flex items-center">
                <span class="mr-2">🔥</span> Top by Streaks
            </h2>

            @if($topByStreaks->count() > 0)
                <div class="space-y-3">
                    @foreach($topByStreaks as $index => $user)
                        <div class="flex items-center justify-between p-3 rounded-lg {{ $user->id === auth()->id() ? 'bg-red-50 border border-red-200' : 'bg-gray-50' }}">
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full font-bold
                                    {{ $index === 0 ? 'bg-yellow-400 text-yellow-900' :
                                       ($index === 1 ? 'bg-gray-400 text-gray-900' :
                                       ($index === 2 ? 'bg-orange-400 text-orange-900' : 'bg-gray-200 text-gray-700')) }}">
                                    {{ $index + 1 }}
                                </div>
                                <x-avatar src="{{ $user->getImage() }}" class="w-10 h-10" />
                                <div>
                                    <div class="font-semibold text-gray-800">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->current_streak }} day streak</div>
                                </div>
                            </div>
                            @if($user->id === auth()->id())
                                <span class="text-red-600 font-semibold">You</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-500 py-8">
                    <div class="text-4xl mb-4">🔥</div>
                    <p>No active streaks yet!</p>
                    <p class="text-sm">Keep logging in daily</p>
                </div>
            @endif
        </div>

        {{-- Top by Posts --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 flex items-center">
                <span class="mr-2">📝</span> Top by Posts
            </h2>

            @if($topByPosts->count() > 0)
                <div class="space-y-3">
                    @foreach($topByPosts as $index => $user)
                        <div class="flex items-center justify-between p-3 rounded-lg {{ $user->id === auth()->id() ? 'bg-green-50 border border-green-200' : 'bg-gray-50' }}">
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full font-bold
                                    {{ $index === 0 ? 'bg-yellow-400 text-yellow-900' :
                                       ($index === 1 ? 'bg-gray-400 text-gray-900' :
                                       ($index === 2 ? 'bg-orange-400 text-orange-900' : 'bg-gray-200 text-gray-700')) }}">
                                    {{ $index + 1 }}
                                </div>
                                <x-avatar src="{{ $user->getImage() }}" class="w-10 h-10" />
                                <div>
                                    <div class="font-semibold text-gray-800">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->posts_count }} posts</div>
                                </div>
                            </div>
                            @if($user->id === auth()->id())
                                <span class="text-green-600 font-semibold">You</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-500 py-8">
                    <div class="text-4xl mb-4">📝</div>
                    <p>No posts yet!</p>
                    <p class="text-sm">Be the first to create content</p>
                </div>
            @endif
        </div>

        {{-- Top by Comments --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4 flex items-center">
                <span class="mr-2">💬</span> Top by Comments
            </h2>

            @if($topByComments->count() > 0)
                <div class="space-y-3">
                    @foreach($topByComments as $index => $user)
                        <div class="flex items-center justify-between p-3 rounded-lg {{ $user->id === auth()->id() ? 'bg-purple-50 border border-purple-200' : 'bg-gray-50' }}">
                            <div class="flex items-center space-x-3">
                                <div class="flex items-center justify-center w-8 h-8 rounded-full font-bold
                                    {{ $index === 0 ? 'bg-yellow-400 text-yellow-900' :
                                       ($index === 1 ? 'bg-gray-400 text-gray-900' :
                                       ($index === 2 ? 'bg-orange-400 text-orange-900' : 'bg-gray-200 text-gray-700')) }}">
                                    {{ $index + 1 }}
                                </div>
                                <x-avatar src="{{ $user->getImage() }}" class="w-10 h-10" />
                                <div>
                                    <div class="font-semibold text-gray-800">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->comments_count }} comments</div>
                                </div>
                            </div>
                            @if($user->id === auth()->id())
                                <span class="text-purple-600 font-semibold">You</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-500 py-8">
                    <div class="text-4xl mb-4">💬</div>
                    <p>No comments yet!</p>
                    <p class="text-sm">Start conversations</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Achievement Categories --}}
    <div class="bg-blue-50 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-800 mb-4">🎯 How Rankings Work</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg p-4 text-center">
                <div class="text-2xl mb-2">⭐</div>
                <div class="font-semibold text-blue-800">Points</div>
                <div class="text-sm text-gray-600">Earned through activity</div>
                <div class="text-xs text-gray-500 mt-1">Posts, comments, likes</div>
            </div>
            <div class="bg-white rounded-lg p-4 text-center">
                <div class="text-2xl mb-2">🔥</div>
                <div class="font-semibold text-blue-800">Streaks</div>
                <div class="text-sm text-gray-600">Daily login consistency</div>
                <div class="text-xs text-gray-500 mt-1">Keep logging in!</div>
            </div>
            <div class="bg-white rounded-lg p-4 text-center">
                <div class="text-2xl mb-2">📝</div>
                <div class="font-semibold text-blue-800">Posts</div>
                <div class="text-sm text-gray-600">Content creation</div>
                <div class="text-xs text-gray-500 mt-1">Share your thoughts</div>
            </div>
            <div class="bg-white rounded-lg p-4 text-center">
                <div class="text-2xl mb-2">💬</div>
                <div class="font-semibold text-blue-800">Comments</div>
                <div class="text-sm text-gray-600">Community engagement</div>
                <div class="text-xs text-gray-500 mt-1">Join discussions</div>
            </div>
        </div>
    </div>
</div>
