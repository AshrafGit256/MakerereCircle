<div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
    <!-- Header Image -->
    @if($fundraiser->images && count($fundraiser->images) > 0)
        <div class="relative h-64 bg-gray-200">
            <img src="{{ asset('storage/' . $fundraiser->images[0]) }}" alt="{{ $fundraiser->title }}" class="w-full h-full object-cover">
            @if($fundraiser->is_featured)
                <div class="absolute top-4 left-4 bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                    ⭐ Featured
                </div>
            @endif
        </div>
    @endif

    <div class="p-6">
        <!-- Title and Actions -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $fundraiser->title }}</h1>
                <div class="flex items-center text-gray-600">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm">{{ $fundraiser->user->name }}</span>
                    <span class="mx-2">•</span>
                    <span class="text-sm">{{ $fundraiser->category }}</span>
                </div>
            </div>
            <button wire:click="openDonateModal" class="bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors">
                Donate Now
            </button>
        </div>

        <!-- Progress Section -->
        <div class="bg-gray-50 rounded-lg p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Fundraising Progress</h2>
                <span class="text-sm text-gray-600">{{ $progressPercentage }}% funded</span>
            </div>

            <div class="mb-4">
                <div class="flex justify-between text-sm text-gray-600 mb-2">
                    <span>Raised: {{ number_format($fundraiser->current_amount) }} UGX</span>
                    <span>Goal: {{ number_format($fundraiser->target_amount) }} UGX</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-green-600 h-3 rounded-full transition-all duration-500" style="width: {{ $progressPercentage }}%"></div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                <div>
                    <div class="text-2xl font-bold text-green-600">{{ number_format($fundraiser->current_amount) }}</div>
                    <div class="text-sm text-gray-600">Raised</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-blue-600">{{ $totalDonors }}</div>
                    <div class="text-sm text-gray-600">Donors</div>
                </div>
                @if($daysRemaining !== null)
                    <div>
                        <div class="text-2xl font-bold text-orange-600">{{ $daysRemaining }}</div>
                        <div class="text-sm text-gray-600">Days Left</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Description -->
        <div class="mb-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-3">About This Fundraiser</h3>
            <p class="text-gray-700 leading-relaxed">{{ $fundraiser->description }}</p>
        </div>

        <!-- Beneficiary Story -->
        @if($fundraiser->beneficiary_story)
            <div class="bg-blue-50 rounded-lg p-6 mb-6">
                <h3 class="text-xl font-semibold text-blue-800 mb-3">Beneficiary Story</h3>
                <div class="flex items-start">
                    @if($fundraiser->beneficiary_name)
                        <div class="flex-shrink-0 mr-4">
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-semibold text-lg">{{ substr($fundraiser->beneficiary_name, 0, 1) }}</span>
                            </div>
                        </div>
                    @endif
                    <div>
                        @if($fundraiser->beneficiary_name)
                            <h4 class="font-semibold text-blue-800 mb-2">{{ $fundraiser->beneficiary_name }}</h4>
                        @endif
                        <p class="text-blue-700 leading-relaxed">{{ $fundraiser->beneficiary_story }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Recent Donations -->
        @if($recentDonations->count() > 0)
            <div class="mb-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Recent Donations</h3>
                <div class="space-y-3">
                    @foreach($recentDonations as $donation)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-800">{{ $donation->getDonorName() }}</div>
                                    <div class="text-sm text-gray-600">{{ $donation->created_at->diffForHumans() }}</div>
                                    @if($donation->message)
                                        <div class="text-sm text-gray-700 mt-1 italic">"{{ Str::limit($donation->message, 100) }}"</div>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold text-green-600">{{ number_format($donation->amount) }} UGX</div>
                                <div class="text-sm text-gray-500">{{ $donation->getPaymentMethodName() }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Gallery -->
        @if($fundraiser->images && count($fundraiser->images) > 1)
            <div class="mb-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Gallery</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($fundraiser->images as $index => $image)
                        @if($index > 0) <!-- Skip first image as it's shown in header -->
                            <img src="{{ asset('storage/' . $image) }}" alt="Fundraiser image" class="w-full h-32 object-cover rounded-lg cursor-pointer hover:opacity-80 transition-opacity">
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Contact Information -->
        @if($fundraiser->contact_phone || $fundraiser->contact_email)
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-3">Contact Information</h3>
                <div class="space-y-2">
                    @if($fundraiser->contact_phone)
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                            </svg>
                            <span class="text-gray-700">{{ $fundraiser->contact_phone }}</span>
                        </div>
                    @endif
                    @if($fundraiser->contact_email)
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                            </svg>
                            <span class="text-gray-700">{{ $fundraiser->contact_email }}</span>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Fixed Donate Button for Mobile -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t p-4">
        <button wire:click="openDonateModal" class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors">
            Donate Now
        </button>
    </div>
</div>
