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
                    @for ($i = 0; $i < 10; $i++)
                        <li
                        class="flex flex-col items-center w-20 gap-1 p-2"
                        x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())">
                        <x-avatar wire:ignore story
                            src="https://randomuser.me/api/portraits/men/{{ rand(1, 99) }}.jpg"
                            class="h-14 w-14 rounded-full border-2 border-pink-500" />
                        <p class="text-xs font-medium truncate" wire:ignore>{{ fake()->name }}</p>
                        </li>
                        @endfor
                </ul>
            </section>

            <!-- Posts -->
            <section class="space-y-4">
                @foreach ($posts as $post)
                <div
                    class="post-item"
                    id="post-{{ $post->id }}"
                    x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())">
                    <livewire:post.item wire:key="post-{{ $post->id }}" :post="$post" />
                </div>
                @endforeach
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
            <section class="mt-4">
                <h4 class="font-bold text-gray-700 mb-2">Suggestions for you</h4>
                <ul class="space-y-3">
                    @foreach ($suggestedUsers->take(5) as $user)
                    <li
                        class="flex items-center gap-3"
                        x-show="!searchQuery || $el.innerText.toLowerCase().includes(searchQuery.toLowerCase())">
                        <a href="{{ route('profile.home', $user) }}">
                            <x-avatar wire:ignore
                                src="https://randomuser.me/api/portraits/men/{{ rand(1, 99) }}.jpg"
                                class="w-12 h-12 rounded-full" />
                        </a>
                        <div class="flex-1 grid grid-cols-7 gap-2 items-center">
                            <div class="col-span-5 flex items-center">
                                <a href="{{ route('profile.home', $user->id) }}"
                                    class="font-semibold truncate text-sm flex items-center">
                                    {{ $user->name }}
                                    @if($user->is_admin)
                                    <!-- verified badge -->
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-4 h-4 ml-1 text-blue-500 flex-shrink-0"
                                        viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M22.25 12c0 5.65-4.6 10.25-10.25 10.25S1.75 17.65 1.75 12 6.35 1.75 12 1.75 22.25 6.35 22.25 12zm-11.53 4.53l6.16-6.16-1.06-1.06-5.1 5.1-2.1-2.1-1.06 1.06 3.16 3.16z" />
                                    </svg>
                                    @endif
                                </a>
                            </div>
                            <div class="col-span-2 text-right">
                                @if (auth()->user()->isFollowing($user))
                                <button wire:click="toggleFollow({{ $user->id }})"
                                    class="font-bold text-blue-500 text-sm">Following</button>
                                @else
                                <button wire:click="toggleFollow({{ $user->id }})"
                                    class="font-bold text-blue-500 text-sm">Follow</button>
                                @endif
                            </div>
                        </div>
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