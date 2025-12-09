<div>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 dark:from-gray-900 dark:to-gray-800">
        <!-- Header Section -->
        <div class="bg-white dark:bg-gray-800 shadow-lg">
            <div class="container mx-auto px-4 py-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                            Campus Connect
                        </h1>
                        <p class="text-gray-600 dark:text-gray-300 mt-2 text-lg">
                            Find your people. Build your future. Connect with purpose.
                        </p>
                    </div>

                    <!-- Quick Stats -->
                    <div class="flex gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['total'] }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Mates Online</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['hiring'] ?? 0 }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Hiring Now</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $stats['projects'] ?? 0 }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Projects</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mx-auto px-4 py-8">
            <!-- Search & Filter Section -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 mb-8 border border-gray-100 dark:border-gray-700">
                <!-- Smart Search -->
                <div class="mb-6">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input wire:model.live.debounce.300ms="search"
                            type="text"
                            class="w-full pl-10 pr-4 py-3 rounded-xl border-2 border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-900 transition-all"
                            placeholder="Search for OBs, OGs, coursemates, project partners...">
                    </div>
                </div>

                <!-- Filter Categories -->
                <div class="mb-6">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                        <span class="font-semibold text-gray-700 dark:text-gray-300">Quick Filters</span>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <button wire:click="setFilter('hiring')"
                            class="px-4 py-2 rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 hover:bg-green-200 dark:hover:bg-green-800 transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Hiring Now
                        </button>
                        <button wire:click="setFilter('project_partners')"
                            class="px-4 py-2 rounded-full bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 hover:bg-purple-200 dark:hover:bg-purple-800 transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Project Partners
                        </button>
                        <button wire:click="setFilter('mentors')"
                            class="px-4 py-2 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 hover:bg-yellow-200 dark:hover:bg-yellow-800 transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            </svg>
                            Alumni Mentors
                        </button>
                        <button wire:click="setFilter('region_mates')"
                            class="px-4 py-2 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 hover:bg-blue-200 dark:hover:bg-blue-800 transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Region Mates
                        </button>
                        <button wire:click="setFilter('same_school')"
                            class="px-4 py-2 rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 hover:bg-red-200 dark:hover:bg-red-800 transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Same School
                        </button>
                    </div>
                </div>

                <!-- Advanced Filters (Collapsible) -->
                <div x-data="{ open: false }" class="mb-4">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition-all">
                        <span class="font-medium text-gray-700 dark:text-gray-300">Advanced Filters</span>
                        <svg :class="{'rotate-180': open}" class="w-5 h-5 text-gray-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" x-collapse class="mt-4 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <!-- Demographic Filters -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Age Range</label>
                            <div class="flex gap-2">
                                <input wire:model.live="minAge" type="number" placeholder="Min"
                                    class="w-1/2 rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700">
                                <input wire:model.live="maxAge" type="number" placeholder="Max"
                                    class="w-1/2 rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Gender</label>
                            <select wire:model.live="gender"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700">
                                <option value="">All Genders</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <!-- Academic Filters -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Year of Study</label>
                            <select wire:model.live="yearOfStudy"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700">
                                <option value="">Any Year</option>
                                <option value="1">Year 1</option>
                                <option value="2">Year 2</option>
                                <option value="3">Year 3</option>
                                <option value="4">Year 4</option>
                                <option value="5+">Year 5+</option>
                                <option value="alumni">Alumni</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Semester</label>
                            <select wire:model.live="semester"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700">
                                <option value="">Any Semester</option>
                                <option value="1">Semester 1</option>
                                <option value="2">Semester 2</option>
                                <option value="summer">Summer</option>
                            </select>
                        </div>

                        <!-- Professional Filters -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role</label>
                            <select wire:model.live="role"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700">
                                <option value="">All Roles</option>
                                <option value="student">Student</option>
                                <option value="lecturer">Lecturer</option>
                                <option value="staff">Staff</option>
                                <option value="alumni">Alumni</option>
                                <option value="industry_partner">Industry Partner</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Looking For</label>
                            <select wire:model.live="lookingFor"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700">
                                <option value="">Anything</option>
                                <option value="employment">Employment</option>
                                <option value="internship">Internship</option>
                                <option value="mentorship">Mentorship</option>
                                <option value="collaboration">Collaboration</option>
                                <option value="study_group">Study Group</option>
                            </select>
                        </div>

                        <!-- Interest/Skill Tags -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Skills & Interests</label>
                            <input wire:model.live.debounce.300ms="interests"
                                type="text"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700"
                                placeholder="Type skills (comma separated)">
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach(explode(',', $selectedInterests) as $interest)
                                @if(trim($interest))
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                    {{ trim($interest) }}
                                    <button wire:click="removeInterest('{{ trim($interest) }}')" class="ml-1">
                                        ×
                                    </button>
                                </span>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        <span class="font-medium">{{ $users->total() }}</span> connections found
                    </div>
                    <div class="flex gap-3">
                        <button wire:click="clearFilters"
                            class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                            Clear All
                        </button>
                        <button wire:click="saveSearch"
                            class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                            </svg>
                            Save Search
                        </button>
                    </div>
                </div>
            </div>

            <!-- Connection Cards -->
            @if($users->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($users as $user)
                <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 dark:border-gray-700 hover:-translate-y-2">
                    <!-- User Header -->
                    <div class="relative p-6">
                        <!-- Status Indicator -->
                        <div class="absolute top-4 right-4">
                            @if($user->is_online)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                Online
                            </span>
                            @endif
                        </div>

                        <!-- User Info -->
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <x-avatar src="{{ $user->getImage() }}"
                                    class="w-16 h-16 rounded-full object-cover border-4 border-white shadow-lg" />
                                <!-- Verification Badge -->
                                @if($user->is_verified)
                                <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center border-2 border-white">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-lg text-gray-900 dark:text-white truncate">
                                    {{ $user->name }}
                                </h3>
                                <p class="text-blue-600 dark:text-blue-400 text-sm">
                                    {{ '@' . $user->username }}
                                </p>
                                @if($user->title)
                                <p class="text-gray-600 dark:text-gray-300 text-sm italic truncate">
                                    {{ $user->title }}
                                </p>
                                @endif
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="mt-4 grid grid-cols-3 gap-2 text-center">
                            <div class="p-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="text-xs text-gray-500 dark:text-gray-400">Course</div>
                                <div class="font-medium text-sm">{{ $user->course ?? 'Not set' }}</div>
                            </div>
                            <div class="p-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="text-xs text-gray-500 dark:text-gray-400">Year</div>
                                <div class="font-medium text-sm">{{ $user->year_of_study ?? '-' }}</div>
                            </div>
                            <div class="p-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="text-xs text-gray-500 dark:text-gray-400">Role</div>
                                <div class="font-medium text-sm">{{ ucfirst($user->role ?? 'student') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Connection Points -->
                    <div class="px-6 pb-4">
                        <!-- Matching Tags -->
                        @if($user->matching_tags)
                        <div class="mb-4">
                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">You both have:</div>
                            <div class="flex flex-wrap gap-1">
                                @foreach(array_slice(explode(',', $user->matching_tags), 0, 3) as $tag)
                                <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-xs">
                                    {{ trim($tag) }}
                                </span>
                                @endforeach
                                @if(count(explode(',', $user->matching_tags)) > 3)
                                <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-full text-xs">
                                    +{{ count(explode(',', $user->matching_tags)) - 3 }} more
                                </span>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            <a href="{{ route('profile.home', $user->username) }}"
                                class="flex-1 text-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all font-medium">
                                View Profile
                            </a>
                            <button wire:click="connectWith('{{ $user->id }}')"
                                class="px-4 py-2 border border-blue-500 text-blue-500 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-all">
                                Connect
                            </button>
                        </div>
                    </div>

                    <!-- Hover Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl pointer-events-none"></div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $users->links('vendor.pagination.tailwind') }}
            </div>
            @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="inline-block p-6 bg-gradient-to-br from-blue-100 to-purple-100 dark:from-gray-800 dark:to-gray-700 rounded-2xl mb-6">
                    <svg class="w-24 h-24 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">No connections found</h3>
                <p class="text-gray-600 dark:text-gray-300 mb-6 max-w-md mx-auto">
                    Try adjusting your filters or be the first to connect with new people in your network.
                </p>
                <div class="flex gap-4 justify-center">
                    <button wire:click="clearFilters"
                        class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all font-medium">
                        Clear All Filters
                    </button>
                    <button wire:click="$dispatch('open-modal', 'suggest-connections')"
                        class="px-6 py-3 border-2 border-blue-500 text-blue-500 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-all font-medium">
                        Get Suggestions
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Suggestions Modal -->
    @push('modals')
    <div x-data="{ open: false }"
        x-on:open-modal.window="open = true"
        x-show="open"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/50" x-on:click="open = false"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full p-6">
                <h3 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Suggested Connections</h3>
                <!-- Suggestions content here -->
                <div class="mt-6 flex justify-end gap-3">
                    <button x-on:click="open = false"
                        class="px-4 py-2 border border-gray-300 rounded-lg">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endpush

    <style>
        .connection-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }

        .dark .connection-card {
            background: rgba(30, 41, 59, 0.95);
        }

        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        [x-cloak] {
            display: none !important;
        }
    </style>

    <!-- Alpine.js for interactivity -->
    <script src="//unpkg.com/alpinejs" defer></script>
</div>