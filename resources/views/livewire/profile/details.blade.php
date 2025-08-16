<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Profile Header -->
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-6 text-white">
            <div class="flex flex-col md:flex-row items-center">
                <x-avatar src="{{ $user->getImage() }}" class="w-24 h-24 rounded-full border-4 border-white shadow-lg" />
                <div class="mt-4 md:mt-0 md:ml-6 text-center md:text-left">
                    <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
                    @if($user->title)
                        <p class="text-lg opacity-90">{{ $user->title }}</p>
                    @endif
                    @if($user->username)
                        <p class="opacity-75">{{ '@' . $user->username }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Profile Details -->
        <div class="p-6">
            <!-- Bio Section -->
            @if($user->bio)
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-3">About</h2>
                <p class="text-gray-600">{{ $user->bio }}</p>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Personal Information -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Personal Information</h2>
                    <div class="space-y-3">
                        @if($user->birthdate)
                        <div class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <p class="text-sm text-gray-500">Birthdate</p>
                                <p class="font-medium">{{ \Carbon\Carbon::parse($user->birthdate)->format('F j, Y') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($user->location)
                        <div class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <div>
                                <p class="text-sm text-gray-500">Location</p>
                                <p class="font-medium">{{ $user->location }}</p>
                            </div>
                        </div>
                        @endif

                        @if($user->employment_status)
                        <div class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <p class="text-sm text-gray-500">Employment Status</p>
                                <p class="font-medium">{{ $user->employment_status }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Education & Skills -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Education & Skills</h2>
                    <div class="space-y-3">
                        @if($user->course)
                        <div class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            </svg>
                            <div>
                                <p class="text-sm text-gray-500">Course of Study</p>
                                <p class="font-medium">{{ $user->course }}</p>
                            </div>
                        </div>
                        @endif

                        @if($user->education_level)
                        <div class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <div>
                                <p class="text-sm text-gray-500">Education Level</p>
                                <p class="font-medium">{{ $user->education_level }}</p>
                            </div>
                        </div>
                        @endif

                        @if($user->skills)
                        <div class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                            <div>
                                <p class="text-sm text-gray-500">Skills</p>
                                <p class="font-medium">{{ $user->skills }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Additional Sections -->
            @if($user->schools || $user->talents)
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                @if($user->schools)
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Schools Attended</h2>
                    <p class="text-gray-600">{{ $user->schools }}</p>
                </div>
                @endif

                @if($user->talents)
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Talents & Leadership</h2>
                    <p class="text-gray-600">{{ $user->talents }}</p>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>