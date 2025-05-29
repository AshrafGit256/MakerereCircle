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
                <img src="{{ asset('assets/logo.png') }}" class="h-12 w-full" alt="logo">
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
    <main class="grid lg:grid-cols-12 gap-8 md:mt-10 p-2">
        <!-- Left: Stories & Posts -->
        <aside class="lg:col-span-8 overflow-hidden">
            <!-- Stories -->
            <section class="mb-4">
                <ul class="flex overflow-x-auto scrollbar-hide items-center gap-2 py-2">
                    @for ($i = 0; $i < 20; $i++)
                        <li
                        class="flex flex-col items-center w-20 gap-1 p-2"
                        x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())">
                        <x-avatar wire:ignore story
                            src="https://randomuser.me/api/portraits/men/{{ rand(1, 99) }}.jpg"
                            class="h-18 w-18 rounded-full border-2 border-blue-300" />
                        <p class="text-xs font-medium truncate w-full text-center" wire:ignore>{{ fake()->name }}</p>
                        </li>
                        @endfor
                </ul>
            </section>

            <!-- Posts -->
            <section class="space-y-4">
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

            <!-- Suggestions (limit to 5, no “Followed by”) -->
            @php
            $titles = ['Dr.', 'Prof.', 'Eng.', 'Chancellor', 'VC'];
            @endphp

            <section class="mt-6">
                <h4 class="font-bold text-gray-700 mb-4 text-lg">Live Now 🔴</h4>
                <ul class="space-y-4">
                    @foreach ([
                    [
                    'title' => 'Startup Pitch Session - Innovation Garage',
                    'location' => 'Block B, Room 2, CEDAT',
                    'views' => 230,
                    'start_time' => 'Started 10 min ago',
                    'status' => 'Live',
                    'thumb' => 'https://img.freepik.com/free-photo/teamwork-people-connecting-using-technology_53876-108213.jpg',
                    ],
                    [
                    'title' => 'Live Basketball: Nsibirwa vs Africa Hall',
                    'location' => 'University Sports Arena',
                    'views' => 1100,
                    'start_time' => 'Started 30 min ago',
                    'status' => 'Live',
                    'thumb' => 'https://img.freepik.com/free-photo/basketball-game-court_23-2149107740.jpg',
                    ],
                    ] as $live)
                    <li class="flex gap-4 p-4 bg-white rounded-lg border shadow hover:bg-gray-50 transition">
                        <!-- Thumbnail -->
                        <img src="{{ $live['thumb'] }}"
                            alt="Live event"
                            class="w-20 h-20 rounded-md object-cover border">

                        <!-- Info -->
                        <div class="flex-1">
                            <h5 class="font-semibold text-gray-800 text-sm">{{ $live['title'] }}</h5>
                            <p class="text-xs text-gray-500 mt-0.5"><i class="fas fa-map-marker-alt mr-1"></i>{{ $live['location'] }}</p>
                            <div class="flex justify-between items-center mt-2 text-xs text-gray-500">
                                <span><i class="fas fa-eye mr-1"></i>{{ number_format($live['views']) }} watching</span>
                                <span class="text-red-500 font-semibold animate-pulse">{{ $live['status'] }}</span>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">{{ $live['start_time'] }}</p>
                        </div>
                    </li>
                    @endforeach
                </ul>
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
                    'category' => '#Culture',
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
                                <span class="text-blue-500">{{ $event['category'] }}</span>
                            </div>
                        </a>
                    </li>
                    @endforeach

                </ul>
            </section>

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
                        <a href="{{ route('profile.home', $user->id) }}">
                            <x-avatar wire:ignore
                                src="https://randomuser.me/api/portraits/men/{{ rand(1, 99) }}.jpg"
                                class="w-12 h-12 rounded-full border border-gray-300 object-cover" />
                        </a>

                        <!-- User info -->
                        <div class="flex-1 grid grid-cols-7 gap-2 items-center">
                            <div class="col-span-5">
                                <a href="{{ route('profile.home', $user->id) }}"
                                    class="font-semibold truncate text-sm text-gray-800 flex items-center gap-1">
                                    {{ $title }} {{ $user->name }}
                                    @if($user->is_admin)
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-4 h-4 text-blue-500" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M22.25 12c0 5.65-4.6 10.25-10.25 10.25S1.75 17.65 1.75 12 6.35 1.75 12 1.75 22.25 6.35 22.25 12zm-11.53 4.53l6.16-6.16-1.06-1.06-5.1 5.1-2.1-2.1-1.06 1.06 3.16 3.16z" />
                                    </svg>
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
                        <a href="{{ url('/college/' . $place['slug'] . '/home') }}"
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
                <p>© 2025 LOST AND FOUND MGT SYSTEM</p>
            </section>
        </aside>
    </main>
</div>