<div class="my-5 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold">Networks</h1>
        <p class="text-gray-600">Connect with people in the Makerere Circle community</p>
    </div>

    @if($users->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($users as $user)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow duration-300">
                    <div class="p-5">
                        <div class="flex items-center mb-4">
                            <x-avatar src="{{ $user->getImage() }}" class="w-16 h-16 rounded-full" />
                            <div class="ml-4">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $user->name }}</h2>
                                <p class="text-gray-600 dark:text-gray-300">{{ '@' . $user->username }}</p>
                            </div>
                        </div>
                        
                        @if($user->title)
                            <div class="mb-3">
                                <p class="text-sm text-gray-700 dark:text-gray-300 italic">{{ $user->title }}</p>
                            </div>
                        @endif
                        
                        <div class="mt-4">
                            <a href="{{ route('profile.home', $user->username) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                View Profile
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">No users found</h3>
            <p class="mt-1 text-gray-500 dark:text-gray-400">There are currently no users in the network.</p>
        </div>
    @endif
</div>