<div class="bg-white rounded-lg shadow-lg max-h-[90vh] overflow-y-auto">
    <div class="p-6 border-b">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">Make a Donation</h2>
            <button wire:click="$dispatch('closeModal')" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    <div class="p-6">
        <!-- Fundraiser Info -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-gray-800">{{ $fundraiser->title }}</h3>
            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($fundraiser->description, 100) }}</p>
            <div class="mt-3">
                <div class="flex justify-between text-sm text-gray-600 mb-1">
                    <span>Raised: {{ number_format($fundraiser->current_amount) }} UGX</span>
                    <span>Goal: {{ number_format($fundraiser->target_amount) }} UGX</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $fundraiser->getProgressPercentage() }}%"></div>
                </div>
                <p class="text-sm text-gray-600 mt-1">{{ $fundraiser->getProgressPercentage() }}% funded</p>
            </div>
        </div>

        <form wire:submit="submit" class="space-y-6">
            <!-- Donation Amount -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Donation Amount (UGX) *</label>
                <div class="grid grid-cols-3 gap-2 mb-3">
                    <button type="button" wire:click="$set('amount', 5000)" class="px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50 {{ $amount == 5000 ? 'bg-blue-100 border-blue-500' : '' }}">
                        5,000
                    </button>
                    <button type="button" wire:click="$set('amount', 10000)" class="px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50 {{ $amount == 10000 ? 'bg-blue-100 border-blue-500' : '' }}">
                        10,000
                    </button>
                    <button type="button" wire:click="$set('amount', 25000)" class="px-3 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50 {{ $amount == 25000 ? 'bg-blue-100 border-blue-500' : '' }}">
                        25,000
                    </button>
                </div>
                <input type="number" wire:model="amount" min="1000" step="1000" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter custom amount">
                @error('amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Payment Method -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method *</label>
                <div class="space-y-2">
                    @foreach($paymentMethods as $method)
                        <label class="flex items-center p-3 border border-gray-300 rounded-md cursor-pointer hover:bg-gray-50">
                            <input type="radio" wire:model.live="payment_method" value="{{ $method->slug }}" class="text-blue-600 focus:ring-blue-500">
                            <div class="ml-3 flex items-center">
                                @if($method->logo_url)
                                    <img src="{{ asset($method->logo_url) }}" alt="{{ $method->name }}" class="w-8 h-8 mr-3">
                                @endif
                                <div>
                                    <div class="font-medium text-gray-900">{{ $method->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $method->type === 'mobile_money' ? 'Mobile Money' : 'Bank Transfer' }}</div>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('payment_method') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Mobile Money Fields -->
            @if($payment_method && \App\Models\PaymentMethod::where('slug', $payment_method)->where('type', 'mobile_money')->exists())
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                    <input type="tel" wire:model="phone_number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="256700000000">
                    <p class="text-sm text-gray-500 mt-1">Enter your mobile number (without +)</p>
                    @error('phone_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            @endif

            <!-- Bank Transfer Fields -->
            @if($payment_method && \App\Models\PaymentMethod::where('slug', $payment_method)->where('type', 'bank_transfer')->exists())
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Account Number *</label>
                    <input type="text" wire:model="account_number" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your account number">
                    @error('account_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            @endif

            <!-- Message -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Message (Optional)</label>
                <textarea wire:model="message" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Leave an encouraging message..."></textarea>
                @error('message') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Anonymous Option -->
            <div class="flex items-center">
                <input type="checkbox" wire:model="is_anonymous" id="is_anonymous" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label for="is_anonymous" class="ml-2 text-sm text-gray-700">Make this donation anonymous</label>
            </div>

            <!-- Submit -->
            <div class="flex justify-end space-x-4 pt-6 border-t">
                <button type="button" wire:click="$dispatch('closeModal')" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" wire:loading.attr="disabled" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:opacity-50">
                    <span wire:loading.remove>Donate {{ number_format($amount) }} UGX</span>
                    <span wire:loading>Processing...</span>
                </button>
            </div>
        </form>

        <!-- Security Notice -->
        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <h4 class="text-sm font-medium text-blue-800">Secure Payment</h4>
                    <p class="text-sm text-blue-700 mt-1">Your donation is processed securely. All transactions are monitored and verified.</p>
                </div>
            </div>
        </div>
    </div>
</div>
