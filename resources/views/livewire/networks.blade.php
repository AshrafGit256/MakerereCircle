<div class="my-5 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold">Networks</h1>
        <p class="text-gray-600">Connect with people in the Makerere Circle community</p>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                <input wire:model.live.debounce.300ms="search" type="text" id="search" placeholder="Name, username, bio..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            
            <div>
                <label for="course" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Course</label>
                <input wire:model.live.debounce.300ms="course" type="text" id="course" placeholder="Course of study" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
                <input wire:model.live.debounce.300ms="title" type="text" id="title" placeholder="Professional title" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            
            <div>
                <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Location</label>
                <input wire:model.live.debounce.300ms="location" type="text" id="location" placeholder="City, country" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            <div>
                <label for="employmentStatus" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Employment Status</label>
                <select wire:model.live="employmentStatus" id="employmentStatus" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">All Statuses</option>
                    <option value="Student">Student</option>
                    <option value="Employed">Employed</option>
                    <option value="Unemployed">Unemployed</option>
                    <option value="Self-employed">Self-employed</option>
                    <option value="Intern">Intern</option>
                    <option value="Freelancer">Freelancer</option>
                </select>
            </div>
            
            <div>
                <label for="educationLevel" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Education Level</label>
                <select wire:model.live="educationLevel" id="educationLevel" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">All Levels</option>
                    <option value="Certificate">Certificate</option>
                    <option value="Diploma">Diploma</option>
                    <option value="Undergraduate">Undergraduate</option>
                    <option value="Postgraduate">Postgraduate</option>
                    <option value="PhD">PhD</option>
                </select>
            </div>
            
            <div>
                <label for="skills" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Skills</label>
                <input wire:model.live.debounce.300ms="skills" type="text" id="skills" placeholder="Skills" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            
            <div>
                <label for="schools" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Schools</label>
                <input wire:model.live.debounce.300ms="schools" type="text" id="schools" placeholder="Previous schools" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            
            <div>
                <label for="talents" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Talents/Leadership</label>
                <input wire:model.live.debounce.300ms="talents" type="text" id="talents" placeholder="Talents or leadership" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
        </div>
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex flex-wrap gap-2">
                <button wire:click="clearFilters" class="text-sm text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100">
                    Clear all filters
                </button>
                
                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                    <span>{{ $users->total() }} users found</span>
                </div>
            </div>
            
            <div class="flex items-center">
                <label for="sortBy" class="mr-2 text-sm text-gray-700 dark:text-gray-300">Sort by:</label>
                <select wire:model.live="sortBy" id="sortBy" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm">
                    <option value="name">Name</option>
                    <option value="created_at">Joined Date</option>
                    <option value="course">Course</option>
                    <option value="education_level">Education Level</option>
                </select>
                
                <button wire:click="sortBy('{{ $sortBy }}')" class="ml-2 p-1 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600">
                    @if($sortDirection === 'asc')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    @endif
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 rounded-lg p-4 text-center shadow">
            <p class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ $stats['total'] }}</p>
            <p class="text-sm text-blue-600 dark:text-blue-400">Total Users</p>
        </div>
        <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 rounded-lg p-4 text-center shadow">
            <p class="text-2xl font-bold text-green-700 dark:text-green-300">{{ $stats['students'] }}</p>
            <p class="text-sm text-green-600 dark:text-green-400">Students</p>
        </div>
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900 dark:to-purple-800 rounded-lg p-4 text-center shadow">
            <p class="text-2xl font-bold text-purple-700 dark:text-purple-300">{{ $stats['employed'] }}</p>
            <p class="text-sm text-purple-600 dark:text-purple-400">Employed</p>
        </div>
        <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900 dark:to-red-800 rounded-lg p-4 text-center shadow">
            <p class="text-2xl font-bold text-red-700 dark:text-red-300">{{ $stats['unemployed'] }}</p>
            <p class="text-sm text-red-600 dark:text-red-400">Unemployed</p>
        </div>
    </div>

    <!-- Users Grid -->
    @if($users->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($users as $user)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-5">
                        <div class="flex items-center mb-4">
                            <div class="relative">
                                <x-avatar src="{{ $user->getImage() }}" class="w-16 h-16 rounded-full object-cover border-2 border-white shadow" />
                                @if($user->employment_status == 'Employed')
                                    <div class="absolute bottom-0 right-0 w-5 h-5 bg-green-500 rounded-full border-2 border-white"></div>
                                @elseif($user->employment_status == 'Student')
                                    <div class="absolute bottom-0 right-0 w-5 h-5 bg-blue-500 rounded-full border-2 border-white"></div>
                                @endif
                            </div>
                            <div class="ml-4">
                                <h2 class="text-lg font-bold text-gray-900 dark:text-white truncate">{{ $user->name }}</h2>
                                <p class="text-gray-600 dark:text-gray-300 text-sm">{{ '@' . $user->username }}</p>
                                @if($user->title)
                                    <p class="text-sm text-gray-700 dark:text-gray-300 italic truncate">{{ $user->title }}</p>
                                @endif
                            </div>
                        </div>
                        
                        @if($user->bio || $user->course || $user->education_level || $user->employment_status)
                        <div class="mb-4">
                            @if($user->bio)
                                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">{{ Str::limit($user->bio, 100) }}</p>
                            @endif
                            
                            <div class="mt-3 flex flex-wrap gap-2">
                                @if($user->course)
                                    <span class="inline-flex items-center text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                            <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                        </svg>
                                        {{ Str::limit($user->course, 15) }}
                                    </span>
                                @endif
                                
                                @if($user->education_level)
                                    <span class="inline-flex items-center text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                        </svg>
                                        {{ $user->education_level }}
                                    </span>
                                @endif
                                
                                @if($user->employment_status)
                                    <span class="inline-flex items-center text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        {{ Str::limit($user->employment_status, 12) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        @endif
                        
                        <div class="mt-4">
                            <a href="{{ route('profile.home', $user->username) }}"
                               class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                View Profile
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">No users found</h3>
            <p class="mt-1 text-gray-500 dark:text-gray-400">Try adjusting your search or filter criteria.</p>
            <button wire:click="clearFilters" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Clear all filters
            </button>
        </div>
    @endif
</div>