<div class="container mx-auto py-6">
    <!-- Header Card -->
    <div class="bg-white rounded-lg shadow-md mb-6 overflow-hidden">
        <div class="relative h-56 bg-cover bg-center" style="background-image: url('{{ $college->image ? asset('storage/' . $college->image) : asset('images/default-college.jpg') }}');">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
            <div class="absolute bottom-0 left-0 p-6 text-white">
                <h1 class="text-3xl md:text-4xl font-extrabold drop-shadow">{{ $college->name }}</h1>
                <p class="text-sm text-white/90 mt-1">{{ $members->count() }} members</p>
            </div>
        </div>
        <div class="p-5">
            <p class="text-gray-700 leading-relaxed">{{ $college->description }}</p>
        </div>
    </div>

    <!-- Main layout: Feed + Right Sidebar -->
    <div class="grid grid-cols-12 gap-6">
        <!-- Feed (left, spans more) -->
        <div class="col-span-12 lg:col-span-8 space-y-6">
            @auth
            <!-- Quick Composer scoped to this college -->
            <section class="bg-white border rounded-lg p-3">
                <form wire:submit.prevent="quickPost" class="space-y-2">
                    <div class="flex items-start gap-3">
                        <x-avatar class="w-10 h-10" src="{{ auth()->user()->getImage() }}" />
                        <textarea
                            wire:model.defer="newPostText"
                            rows="2"
                            placeholder="Share something with {{ $college->name }}..."
                            class="w-full resize-none bg-transparent outline-none border border-gray-200 focus:border-blue-400 rounded-md p-2 text-sm"></textarea>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <label class="cursor-pointer text-green-600 text-sm font-medium">
                                <input type="file" class="hidden" wire:model="newPostImages" multiple accept=".jpg,.jpeg,.png" />
                                + Media
                            </label>
                            <div wire:loading wire:target="newPostImages" class="text-xs text-gray-500">Uploading…</div>
                            @if(!empty($newPostImages))
                                <span class="text-xs text-gray-600">{{ count($newPostImages) }} selected</span>
                            @endif
                        </div>
                        <button
                            type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-1.5 rounded-md disabled:opacity-50"
                            wire:loading.attr="disabled"
                            wire:target="quickPost,newPostImages"
                        >Post</button>
                    </div>
                </form>
            </section>
            @endauth

            <!-- Leaders -->
            <section class="bg-white rounded-lg border p-4">
                <header class="flex items-center justify-between mb-3">
                    <h2 class="text-lg font-semibold">College Leaders</h2>
                    <span class="text-xs text-gray-500">{{ $leaders->count() }} profiles</span>
                </header>
                @if($leaders->count())
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($leaders->take(6) as $leader)
                        <div class="flex items-center gap-3 p-2 rounded hover:bg-gray-50">
                            <img src="{{ $leader->profile_photo_url ?? asset('images/default-user.jpg') }}" class="w-10 h-10 rounded-full object-cover border" />
                            <div class="flex-1 min-w-0">
                                <div class="font-medium text-sm truncate">{{ $leader->name }}</div>
                                <div class="text-xs text-gray-500 truncate">{{ $leader->title ?? 'Leader' }}</div>
                            </div>
                            @auth
                                @if(auth()->user()->isFollowing($leader))
                                    <button wire:click="toggleFollow({{ $leader->id }})" class="text-xs font-semibold text-blue-600">Following</button>
                                @else
                                    <button wire:click="toggleFollow({{ $leader->id }})" class="text-xs font-semibold text-blue-600">Follow</button>
                                @endif
                            @endauth
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">No leaders identified yet.</p>
                @endif
            </section>

            <!-- Posts Feed -->
            <section class="bg-white rounded-lg border p-0">
                <header class="px-4 py-3 border-b">
                    <h2 class="text-lg font-semibold">College Posts</h2>
                </header>
                <div class="space-y-4 p-4">
                    @foreach($posts as $post)
                        @livewire('post.item', ['post' => $post], key($post->id))
                    @endforeach
                </div>
                <div class="px-4 pb-4">
                    {{ $posts->links() }}
                </div>
            </section>

            <!-- College Chat Section -->
            <section class="bg-white rounded-lg border p-4">
                <header class="flex items-center justify-between mb-3">
                    <h2 class="text-lg font-semibold">College Chat</h2>
                    <span class="text-xs text-gray-500">Real-time discussions</span>
                </header>
                <div>
                    @livewire('chat.chat-list', ['collegeId' => $college->id])
                </div>
            </section>
        </div>

        <!-- Right Sidebar -->
        <aside class="col-span-12 lg:col-span-4 space-y-6">
            <!-- People to follow -->
            <section class="bg-white rounded-lg border p-4">
                <header class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold">People to follow in {{ $college->name }}</h3>
                    <a href="#" class="text-xs text-blue-600 hover:underline">See all</a>
                </header>
                <ul class="space-y-3">
                    @foreach($suggestedUsers->take(6) as $user)
                    <li class="flex items-center gap-3">
                        <a href="{{ route('profile.home', $user->username) }}">
                            <x-avatar src="{{ $user->getImage() }}" class="w-10 h-10" />
                        </a>
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('profile.home', $user->username) }}" class="font-medium text-sm truncate">{{ $user->name }}</a>
                            <p class="text-xs text-gray-500 truncate">{{ $user->title ?? 'Student' }}</p>
                        </div>
                        @auth
                            @if(auth()->user()->isFollowing($user))
                                <button wire:click="toggleFollow({{ $user->id }})" class="text-xs font-semibold text-blue-600">Following</button>
                            @else
                                <button wire:click="toggleFollow({{ $user->id }})" class="text-xs font-semibold text-blue-600">Follow</button>
                            @endif
                        @endauth
                    </li>
                    @endforeach
                </ul>
            </section>

            <!-- Trending in this college -->
            <section class="bg-white rounded-lg border p-4">
                <h3 class="font-semibold mb-3">Trending in {{ $college->name }}</h3>
                @if($trendingTags->count())
                    <ul class="space-y-2 text-sm">
                        @foreach($trendingTags as $item)
                            <li class="flex items-center justify-between">
                                <button class="text-blue-600 hover:underline">#{{ $item['tag'] }}</button>
                                <span class="text-xs text-gray-500">{{ $item['count'] }} posts</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-gray-500">No trending tags yet.</p>
                @endif
            </section>

            <!-- New members -->
            <section class="bg-white rounded-lg border p-4">
                <h3 class="font-semibold mb-3">New Members</h3>
                <ul class="space-y-3">
                    @forelse($newMembers as $user)
                        <li class="flex items-center gap-3">
                            <x-avatar src="{{ $user->getImage() }}" class="w-8 h-8" />
                            <div class="min-w-0">
                                <div class="text-sm font-medium truncate">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500">Joined {{ $user->created_at->diffForHumans() }}</div>
                            </div>
                        </li>
                    @empty
                        <p class="text-sm text-gray-500">No new members in the past two weeks.</p>
                    @endforelse
                </ul>
            </section>

            <!-- Quick Links specific to college -->
            <section class="bg-white rounded-lg border p-4">
                <h3 class="font-semibold mb-3">Quick Links</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="text-blue-600 hover:underline">Departmental Announcements</a></li>
                    <li><a href="#" class="text-blue-600 hover:underline">Timetable & Exams</a></li>
                    <li><a href="#" class="text-blue-600 hover:underline">Student Associations</a></li>
                    <li><a href="#" class="text-blue-600 hover:underline">Labs & Facilities</a></li>
                </ul>
            </section>

            <!-- About card -->
            <section class="bg-white rounded-lg border p-4">
                <h3 class="font-semibold mb-2">About {{ $college->name }}</h3>
                <p class="text-sm text-gray-600">A dedicated space for students and staff to share updates, ask questions, collaborate, and stay in the loop.</p>
            </section>
        </aside>
    </div>
</div>
