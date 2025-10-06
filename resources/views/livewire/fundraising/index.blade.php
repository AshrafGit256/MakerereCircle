<div class="max-w-7xl mx-auto p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Fundraising Campaigns</h1>
            <p class="text-gray-600">Support meaningful causes and make a difference in your community</p>
        </div>
        @auth
            <button wire:click="openCreateModal" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                Start a Fundraiser
            </button>
        @endauth
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search fundraisers..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Category -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select wire:model.live="category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Sort By -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                <select wire:model.live="sortBy" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="most_funded">Most Funded</option>
                    <option value="least_funded">Least Funded</option>
                    <option value="ending_soon">Ending Soon</option>
                    <option value="featured">Featured</option>
                </select>
            </div>

            <!-- Show Completed -->
            <div class="flex items-end">
                <label class="flex items-center">
                    <input type="checkbox" wire:model.live="showCompleted" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-700">Show Completed</span>
                </label>
            </div>
        </div>
    </div>

    <!-- Fundraisers Grid -->
    @if($fundraisers->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($fundraisers as $fundraiser)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <!-- Image -->
                    <div class="relative h-48 bg-gray-200">
                        @if($fundraiser->images && count($fundraiser->images) > 0)
                            <img src="{{ asset('storage/' . $fundraiser->images[0]) }}" alt="{{ $fundraiser->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                <div class="text-white text-4xl">🎯</div>
                            </div>
                        @endif

                        @if($fundraiser->is_featured)
                            <div class="absolute top-3 left-3 bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                                ⭐ Featured
                            </div>
                        @endif

                        @if($fundraiser->isCompleted())
                            <div class="absolute top-3 right-3 bg-green-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                                ✅ Completed
                            </div>
                        @elseif($fundraiser->isExpired())
                            <div class="absolute top-3 right-3 bg-red-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                                ⏰ Expired
                            </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-3">
                            <h3 class="text-lg font-semibold text-gray-800 line-clamp-2">{{ $fundraiser->title }}</h3>
                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full ml-2 flex-shrink-0">
                                {{ $categories[$fundraiser->category] ?? 'Other' }}
                            </span>
                        </div>

                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $fundraiser->description }}</p>

                        <!-- Progress -->
                        <div class="mb-4">
                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                <span>{{ number_format($fundraiser->current_amount) }} UGX raised</span>
                                <span>{{ $fundraiser->getProgressPercentage() }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ $fundraiser->getProgressPercentage() }}%"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500 mt-1">
                                <span>Goal: {{ number_format($fundraiser->target_amount) }} UGX</span>
                                @if($fundraiser->end_date)
                                    <span>{{ $fundraiser->getDaysRemaining() }} days left</span>
                                @endif
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="flex justify-between text-sm text-gray-600 mb-4">
                            <span>{{ $fundraiser->getTotalDonors() }} donors</span>
                            <span>{{ $fundraiser->user->name }}</span>
                        </div>

                        <!-- Action Button -->
                        <a href="{{ route('fundraising.show', $fundraiser) }}" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors text-center block">
                            View Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $fundraisers->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <div class="text-6xl mb-4">🎯</div>
            <h3 class="text-2xl font-semibold text-gray-800 mb-2">No fundraisers found</h3>
            <p class="text-gray-600 mb-6">
                @if($search || $category)
                    Try adjusting your search or filter criteria.
                @else
                    Be the first to start a fundraiser and make a difference!
                @endif
            </p>
            @auth
                <button wire:click="openCreateModal" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                    Start Your Fundraiser
                </button>
            @else
                <a href="{{ route('login') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors inline-block">
                    Login to Start Fundraising
                </a>
            @endauth
        </div>
    @endif

    <!-- Impact Stats -->
    <div class="bg-gradient-to-r from-green-500 to-blue-600 rounded-lg p-8 text-white mt-12">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold mb-2">Our Community Impact</h2>
            <p class="text-green-100">Together, we're making a real difference</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
            <div>
                <div class="text-3xl font-bold mb-2">{{ \App\Models\Fundraiser::count() }}</div>
                <div class="text-green-100">Total Campaigns</div>
            </div>
            <div>
                <div class="text-3xl font-bold mb-2">{{ number_format(\App\Models\Fundraiser::sum('current_amount')) }}</div>
                <div class="text-green-100">Total Raised (UGX)</div>
            </div>
            <div>
                <div class="text-3xl font-bold mb-2">{{ \App\Models\Donation::where('status', 'completed')->count() }}</div>
                <div class="text-green-100">Successful Donations</div>
            </div>
            <div>
                <div class="text-3xl font-bold mb-2">{{ \App\Models\Fundraiser::whereRaw('current_amount >= target_amount')->count() }}</div>
                <div class="text-green-100">Completed Goals</div>
            </div>
        </div>
    </div>
</div>
