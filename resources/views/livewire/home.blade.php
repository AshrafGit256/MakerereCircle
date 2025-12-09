
<!-- 1) Load mark.js -->
<script src="https://cdn.jsdelivr.net/npm/mark.js/dist/mark.min.js"></script>

<div
    x-data="{
    canLoadMore: @entangle('canLoadMore'),
    searchQuery: '',
    markInstance: null,

    init() {
      // infinite-scroll
      window.addEventListener('scroll', () => {
        let scrollTop    = window.scrollY || window.pageYOffset;
        let divHeight    = window.innerHeight || document.documentElement.clientHeight;
        let scrollHeight = document.documentElement.scrollHeight;

        if (scrollTop + divHeight >= scrollHeight - 1 && this.canLoadMore) {
          @this.loadMore();
        }
      });

      // 2) Initialize mark.js on this element
      this.markInstance = new Mark($el);

      // 3) Watch for changes to searchQuery
      this.$watch('searchQuery', (val) => {
        // clear old highlights
        this.markInstance.unmark({
          done: () => {
            if (val.trim() !== '') {
              // highlight new matches
              this.markInstance.mark(val, {
                separateWordSearch: false,
                element: 'mark',
                className: 'bg-yellow-300'
              });
            }
          }
        });
      });
    }
  }"
    x-init="init()"
    class="w-full h-full">
    <!-- Mobile Header -->
    <header class="md:hidden sticky top-0 z-50 bg-white p-2">
        <div class="grid grid-cols-12 gap-2 items-center">
            <div class="col-span-3">
                <img src="{{ asset('assets/MakSocial10.png') }}" class="h-12 w-full" alt="logo">
            </div>

            <div class="col-span-8">
                <input
                    x-model="searchQuery"
                    type="text"
                    placeholder="Search posts, stories, suggestions..."
                    class="w-full bg-gray-100 rounded-lg pl-3 py-2 focus:outline-none focus:bg-white focus:shadow-sm transition" />
            </div>

            <div class="col-span-1 flex justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.9" stroke="currentColor" class="w-6 h-6 text-gray-600">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312
                   2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3
                   8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                </svg>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="grid lg:grid-cols-12 gap-8 md:mt-3 p-2">
        <!-- Left: Stories & Posts -->
        <aside class="lg:col-span-8">

            <!-- Top toolbar: Network, Events, Market -->
            <section class="mb-4">
                <div class="grid grid-cols-3 gap-3">
                    <a href="{{ route('network') }}" class="flex items-center justify-center gap-2 bg-white border rounded-lg py-3 hover:bg-gray-50">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-700">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 1-6 .28 9.094 9.094 0 0 1-6-.28M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <span class="text-sm font-semibold">Network</span>
                    </a>
                    <a href="#" class="flex items-center justify-center gap-2 bg-white border rounded-lg py-3 hover:bg-gray-50">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-700">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 8.25h18M4.5 7.5h15A1.5 1.5 0 0 1 21 9v9.75A1.5 1.5 0 0 1 19.5 20.25h-15A1.5 1.5 0 0 1 3 18.75V9A1.5 1.5 0 0 1 4.5 7.5Z" />
                        </svg>
                        <span class="text-sm font-semibold">Events</span>
                    </a>
                    <a href="{{ route('market') }}" class="flex items-center justify-center gap-2 bg-white border rounded-lg py-3 hover:bg-gray-50">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-700">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm9 0a3 3 0 1 0 0 6 3 3 0 0 0 0-6ZM3.75 6h16.5l-1.5 6.75H6.06L3.75 6Z" />
                        </svg>
                        <span class="text-sm font-semibold">Market</span>
                    </a>
                </div>
            </section>

            <!-- Quick Composer (scrolls with feed) -->
            @auth
            <section class="bg-white border rounded-lg p-3 mb-4">

                <form wire:submit.prevent="quickPost" class="space-y-2">
                    <div class="flex items-start gap-3">
                        <x-avatar class="w-10 h-10" src="{{ auth()->user()->getImage() }}" />
                        <textarea
                            wire:model.defer="newPostText"
                            rows="2"
                            placeholder="What’s happening?"
                            class="w-full resize-none bg-transparent outline-none border border-gray-200 focus:border-blue-400 rounded-md p-2 text-sm"></textarea>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <label class="cursor-pointer text-green-600 text-sm font-medium">
                                <input type="file" class="hidden" wire:model="newPostImages" multiple accept=".jpg,.jpeg,.png" />
                                + Media (multiple images for voting)
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

                    @if(!empty($newPostImages))
                        <div class="mt-2 grid grid-cols-2 gap-2">
                            @php $labels = range('A','Z'); @endphp
                            @foreach($newPostImages as $i => $img)
                                <div class="border rounded-md p-1 flex items-center gap-2">
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] font-bold">{{ $labels[$i] }}</span>
                                    <img src="{{ $img->temporaryUrl() }}" class="h-16 w-16 object-cover rounded"/>
                                    <span class="text-xs text-gray-600">Option {{ $labels[$i] }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </form>
            </section>
            @endauth




            <!-- Posts -->
            <section class="space-y-3">
                @if($posts)
                @foreach ($posts as $post)
                <div
                    class="post-item"
                    id="post-{{ $post->id }}"
                    x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())">
                    <livewire:post.item wire:key="post-{{ $post->id }}" :post="$post" />
                </div>
                @endforeach

                @else
                <p class="font-bol flex justify-center">No Posts</p>

                @endif

                {{-- Loading indicator for infinite scroll --}}
                @if($canLoadMore)
                <div class="flex justify-center py-8" wire:loading.delay>
                    <div class="flex items-center gap-2 text-gray-500">
                        <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-500"></div>
                        <span class="text-sm">Loading more posts...</span>
                    </div>
                </div>
                @else
                <div class="flex justify-center py-8">
                    <div class="text-center text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <p class="text-sm">You've seen all posts! 🎉</p>
                    </div>
                </div>
                @endif
            </section>
        </aside>

        <!-- Right: Desktop Search & Suggestions -->
        <aside class="lg:col-span-4 hidden lg:block p-4">
            <!-- Desktop Search Input -->
            <div class="relative mb-6">
                <input
                    x-model="searchQuery"
                    type="text"
                    placeholder="Search all content..."
                    class="w-full bg-gray-100 rounded-full pl-10 pr-4 py-2 text-sm placeholder-gray-500
                 focus:outline-none focus:bg-white focus:shadow-md transition" />
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400 pointer-events-none"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-4.35-4.35M16.65 16.65A7.5 7.5 0 1116.65
                   2.5a7.5 7.5 0 010 14.15z" />
                </svg>
            </div>



            <section class="mt-6">
                <h4 class="font-bold text-gray-700 mb-4 text-xl">Happening Now 🔴</h4>

                <div class="space-y-8">
                    @foreach ([
                    [
                    'title' => 'Live kickoff of the Makerere University run competitions',
                    'location' => 'Freedom Square',
                    'views' => 230,
                    'start_time' => 'Started 10 min ago',
                    'status' => 'Live',
                    'video' => asset('assets/MakRun.mp4'),
                    ],

                    ] as $live)
                    <div class="bg-white border shadow rounded-xl overflow-hidden">
                        <!-- Video Area -->
                        <video
                            src="{{ $live['video'] }}"
                            controls
                            autoplay
                            muted
                            loop
                            playsinline
                            class="w-full h-48 sm:h-64 object-cover"></video>

                        <!-- <video
                            src="{{ $live['video'] }}"
                            controls
                            autoplay
                            muted
                            loop
                            playsinline
                            class="w-full h-48 sm:h-64 object-contain"></video> -->
                        <!-- Info Area -->
                        <div class=" p-4">
                            <div class="flex items-center justify-between">
                                <h5 class="text-lg font-semibold text-gray-800">{{ $live['title'] }}</h5>
                                <span class="text-red-500 text-sm font-medium animate-pulse">{{ $live['status'] }}</span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1"><i class="fas fa-map-marker-alt mr-1"></i>{{ $live['location'] }}</p>
                            <div class="flex justify-between items-center text-sm text-gray-500 mt-2">
                                <span><i class="fas fa-eye mr-1"></i>{{ number_format($live['views']) }} watching</span>
                                <span class="text-gray-400">{{ $live['start_time'] }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>


            <section class="mt-6">
                <h4 class="font-bold text-gray-700 mb-4 text-lg">Trending Events on Campus</h4>
                <ul class="space-y-4">

                    @foreach([
                    [
                    'title' => 'Tech & Innovation Expo 2025',
                    'location' => 'Makerere CEDAT Grounds',
                    'attendees' => 950,
                    'slug' => 'tech-expo-2025',
                    'time' => 'Today · 2:00 PM',
                    'category' => '#Technology',
                    ],
                    [
                    'title' => 'Freshers Welcome Bash',
                    'location' => 'Nsibirwa Hall Courtyard',
                    'attendees' => 1340,
                    'slug' => 'freshers-bash',
                    'time' => 'Tomorrow · 7:00 PM',
                    'category' => '#Entertainment',
                    ],
                    [
                    'title' => 'Girls in STEM Hackathon',
                    'location' => 'CoCIS Innovation Hub',
                    'attendees' => 530,
                    'slug' => 'girls-in-stem',
                    'time' => 'Fri · 10:00 AM',
                    'category' => '#Empowerment',
                    ],
                    [
                    'title' => 'Makerere Mental Health Week',
                    'location' => 'Freedom Square',
                    'attendees' => 870,
                    'slug' => 'mental-health-week',
                    'time' => 'All Week',
                    'category' => '#Wellbeing',
                    ],
                    [
                    'title' => 'Annual Cultural Gala',
                    'location' => 'Main Hall',
                    'attendees' => 2100,
                    'slug' => 'cultural-gala',
                    'time' => 'Saturday · 4:00 PM',
                    'category' => '#Cedat',
                    ],
                    ] as $event)
                    <li>
                        <a href="{{ url('/events/' . $event['slug']) }}"
                            class="block p-4 bg-white rounded-lg hover:bg-gray-50 border shadow-sm transition">

                            <div class="flex justify-between items-start mb-1">
                                <h5 class="font-semibold text-sm text-gray-800 truncate">{{ $event['title'] }}</h5>
                                <span class="text-xs text-gray-400">{{ $event['time'] }}</span>
                            </div>

                            <p class="text-xs text-gray-500 mb-1">{{ $event['location'] }}</p>

                            <div class="flex justify-between items-center text-xs text-gray-400">
                                <span>{{ number_format($event['attendees']) }} going</span>
                                <span
                                    @click.prevent="searchQuery = '{{ $event['category'] }}'"
                                    class="text-blue-500 cursor-pointer hover:underline">
                                    {{ $event['category'] }}
                                </span>

                            </div>
                        </a>
                    </li>
                    @endforeach

                </ul>
            </section>

            <!-- Groups Section -->
            <section class="mt-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-bold text-gray-700 text-lg">Popular Groups</h4>
                    <a href="{{ route('groups.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        See all
                    </a>
                </div>
                
                @if(isset($groups) && $groups->count() > 0)
                    <ul class="space-y-3">
                        @foreach($groups as $group)
                            <li class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-md transition">
                                <img src="{{ $group->image ?? asset('assets/dist/img/cedat.jpg') }}"
                                     alt="{{ $group->name }}"
                                     class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                
                                <div class="flex-1">
                                    <a href="{{ route('groups.show', $group->slug) }}"
                                       class="font-semibold text-sm text-gray-800 block truncate">
                                        {{ $group->name }}
                                    </a>
                                    <p class="text-xs text-gray-500">{{ $group->members->count() }} members</p>
                                </div>
                                
                                @auth
                                    @if($group->members()->where('user_id', auth()->id())->exists())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Joined
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Join
                                        </span>
                                    @endif
                                @endauth
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500 text-sm">No groups available yet.</p>
                @endif
            </section>

            <!-- Suggestions (limit to 5, no “Followed by”) -->
            @php
            $titles = ['Dr.', 'Prof.', 'Eng.', 'Chairman'];
            @endphp

            <section class="mt-4">
                <h4 class="font-bold text-gray-700 mb-2">Suggestions for you</h4>
                <ul class="space-y-3">
                    @foreach ($suggestedUsers->take(5) as $user)
                    @php
                    $title = $titles[array_rand($titles)];
                    $followers = rand(300, 2500);
                    @endphp
                    <li class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-md transition"
                        x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())">

                        <!-- Avatar -->
                        <a href="{{ route('profile.home', $user->username) }}">
                            <x-avatar wire:ignore
                                src="{{ $user->getImage() }}"
                                class="w-12 h-12 rounded-full border border-gray-300 object-cover" />
                        </a>

                        <!-- User info -->
                        <div class="flex-1 grid grid-cols-7 gap-2 items-center">
                            <div class="col-span-5">
                                <a href="{{ route('profile.home', $user->username) }}"
                                    class="font-semibold truncate text-sm text-gray-800 flex items-center gap-1">
                                    {{ $title }} {{ $user->name }}
                                    @if($user->is_admin)
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-4 h-4 text-blue-500" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M22.25 12c0 5.65-4.6 10.25-10.25 10.25S1.75 17.65 1.75 12 6.35 1.75 12 1.75 22.25 6.35 22.25 12zm-11.53 4.53l6.16-6.16-1.06-1.06-5.1 5.1-2.1-2.1-1.06 1.06 3.16 3.16z" />
                                    </svg>
                                    @elseif($user->is_verified)
                                    <x-verified-badge :user="$user" size="sm" />
                                    @endif
                                </a>
                                <p class="text-xs text-gray-500 mt-0.5">{{ number_format($followers) }} followers</p>
                            </div>

                            <!-- Follow/Unfollow -->
                            <div class="col-span-2 text-right">
                                @if (auth()->user()->isFollowing($user))
                                <button wire:click="toggleFollow({{ $user->id }})"
                                    class="text-sm font-bold text-blue-600 hover:underline">Following</button>
                                @else
                                <button wire:click="toggleFollow({{ $user->id }})"
                                    class="text-sm font-bold text-blue-600 hover:underline">Follow</button>
                                @endif
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </section>

            <section class="mt-6">
                <h4 class="font-bold text-gray-700 mb-4 text-lg">Explore Colleges & Places at Makerere</h4>
                <ul class="space-y-4">
                    @foreach ([
                    [
                    'name' => 'College of Engineering, Design, Art and Technology (CEDAT)',
                    'slug' => 'cedat',
                    'users' => 1260,
                    'desc' => 'Fostering innovation and applied engineering solutions for Uganda and beyond.',
                    'image' => 'cedat.jpg',
                    ],
                    [
                    'name' => 'College of Health Sciences',
                    'slug' => 'chs',
                    'users' => 980,
                    'desc' => 'Training the next generation of medical and healthcare professionals.',
                    'image' => 'chs.jpg',
                    ],
                    [
                    'name' => 'Makerere Main Library',
                    'slug' => 'library',
                    'users' => 720,
                    'desc' => 'Your go-to hub for academic resources and silent study spaces.',
                    'image' => 'library.jpg',
                    ],
                    [
                    'name' => 'College of Business and Management Sciences (COBAMS)',
                    'slug' => 'cobams',
                    'users' => 1540,
                    'desc' => 'Driving Uganda’s business transformation through practical knowledge.',
                    'image' => 'cobams.jpg',
                    ],
                    [
                    'name' => 'School of Law',
                    'slug' => 'school-of-law',
                    'users' => 435,
                    'desc' => 'Pioneering legal education and advocacy for justice and reform.',
                    'image' => 'school_of_law.jpg',
                    ],
                    ] as $place)
                    <li>
                        <a href="{{ route('profile.home', $user->username) }}"
                            class="flex items-center gap-4 p-4 bg-white shadow-sm rounded-lg border hover:bg-gray-50 transition">
                            <!-- Profile Photo -->
                            <img src="{{ asset('assets/dist/img/' . $place['image']) }}"
                                alt="{{ $place['name'] }}"
                                class="w-20 h-20 rounded-full object-cover border border-gray-200">

                            <!-- Info -->
                            <div class="flex-1">
                                <h5 class="font-semibold text-gray-800 text-base">
                                    {{ $place['name'] }}
                                </h5>
                                <p class="text-sm text-gray-500">{{ number_format($place['users']) }} users</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $place['desc'] }}</p>
                            </div>

                            <!-- Action Icon -->
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-gray-400 group-hover:text-blue-500"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M12.293 3.293a1 1 0 011.414 0l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414-1.414L15.586 10H3a1 1 0 110-2h12.586l-3.293-3.293a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </section>

            <section class="mt-6">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-bold text-gray-700 text-lg">Groups</h4>
                    <a href="{{ route('groups.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        View All
                    </a>
                </div>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h5 class="text-sm font-medium text-blue-800">Create your own study groups</h5>
                            <p class="text-xs text-blue-600 mt-1">Connect with classmates for study sessions, project work, and discussions.</p>
                        </div>
                        <div class="ml-auto">
                            <a href="{{ route('groups.index') }}" class="text-blue-600 hover:text-blue-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mt-12">
                <h4 class="font-bold text-gray-700 mb-4 text-lg">Relevant Associations 🤝</h4>

                <ul class="space-y-4">
                    @foreach ([
                    [
                    'name' => 'AI in Agriculture Forum',
                    'description' => 'A global organization promoting AI integration in sustainable agriculture.',
                    'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a7/React-icon.svg/512px-React-icon.svg.png',
                    'link' => 'https://example.com/ai-agriculture',
                    ],
                    [
                    'name' => 'FarmTech Uganda',
                    'description' => 'A national body supporting agri-tech innovation and rural startups.',
                    'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/2f/Google_2015_logo.svg/512px-Google_2015_logo.svg.png',
                    'link' => 'https://example.com/farmtech-uganda',
                    ],
                    [
                    'name' => 'Precision Agriculture Association',
                    'description' => 'Promotes data-driven farming practices for better yields and efficiency.',
                    'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5f/Ubuntu_logo_2022.svg/512px-Ubuntu_logo_2022.svg.png',
                    'link' => 'https://example.com/precision-agriculture',
                    ],
                    ] as $assoc)
                    <li>
                        <a href="{{ $assoc['link'] }}"
                            target="_blank"
                            class="flex items-center gap-4 p-4 bg-white shadow-sm rounded-lg border hover:bg-gray-50 transition">
                            <!-- Logo -->
                            <img src="{{ $assoc['logo'] }}"
                                alt="{{ $assoc['name'] }} Logo"
                                class="w-16 h-16 rounded-full object-contain border border-gray-200 bg-white">

                            <!-- Info -->
                            <div class="flex-1">
                                <h5 class="font-semibold text-gray-800 text-base">
                                    {{ $assoc['name'] }}
                                </h5>
                                <p class="text-xs text-gray-500 mt-1">
                                    {!! html_entity_decode($assoc['description']) !!}
                                </p>
                            </div>

                            <!-- External Link Icon -->
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-gray-400 group-hover:text-blue-500"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M12.293 3.293a1 1 0 011.414 0l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414-1.414L15.586 10H3a1 1 0 110-2h12.586l-3.293-3.293a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </section>

            <!-- Footer Links -->
            <section class="mt-10 text-xs text-gray-600 space-y-2">
                <ol class="flex flex-wrap gap-2">
                    <li><a href="#" class="hover:underline">About</a></li>
                    <li><a href="#" class="hover:underline">Help</a></li>
                    <li><a href="#" class="hover:underline">API</a></li>
                    <li><a href="#" class="hover:underline">Jobs</a></li>
                    <li><a href="#" class="hover:underline">Privacy</a></li>
                    <li><a href="#" class="hover:underline">Terms</a></li>
                    <li><a href="#" class="hover:underline">Locations</a></li>
                </ol>
                <p>© 2025 Makerere <b>Circle</b> Inc.</p>
            </section>
        </aside>
    </main>
</div>