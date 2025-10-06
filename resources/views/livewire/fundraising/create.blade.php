<div class="bg-white rounded-lg shadow-lg max-h-[90vh] overflow-y-auto">
    <div class="p-6 border-b">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">Create Fundraiser</h2>
            <button wire:click="$dispatch('closeModal')" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    <form wire:submit="submit" class="p-6 space-y-6">
        <!-- Basic Information -->
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-gray-800">Basic Information</h3>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Fundraiser Title *</label>
                <input type="text" wire:model="title" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Help John get medical treatment">
                @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                <textarea wire:model="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Describe the cause and how the funds will be used..."></textarea>
                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Target Amount (UGX) *</label>
                    <input type="number" wire:model="target_amount" min="1000" step="1000" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="50000">
                    @error('target_amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                    <select wire:model="category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('category') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">End Date (Optional)</label>
                <input type="date" wire:model="end_date" min="{{ date('Y-m-d', strtotime('+1 day')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('end_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Beneficiary Information -->
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-gray-800">Beneficiary Information</h3>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Beneficiary Name</label>
                <input type="text" wire:model="beneficiary_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Name of the person/family benefiting">
                @error('beneficiary_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Beneficiary Story</label>
                <textarea wire:model="beneficiary_story" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Share the personal story behind this fundraiser..."></textarea>
                @error('beneficiary_story') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                    <input type="tel" wire:model="contact_phone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="+256700000000">
                    @error('contact_phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
                    <input type="email" wire:model="contact_email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="contact@example.com">
                    @error('contact_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Images -->
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-gray-800">Images</h3>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Upload Images (Max 5)</label>
                <input type="file" wire:model="images" multiple accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-sm text-gray-500 mt-1">Upload images related to your fundraiser (JPG, PNG, max 2MB each)</p>
                @error('images.*') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            @if($images)
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($images as $index => $image)
                        <div class="relative">
                            <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="w-full h-24 object-cover rounded-md">
                            <button type="button" wire:click="$set('images', array_filter($images, fn($_, $i) => $i !== $index))" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">
                                ×
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Settings -->
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-gray-800">Settings</h3>

            <div class="flex items-center">
                <input type="checkbox" wire:model="is_featured" id="is_featured" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <label for="is_featured" class="ml-2 text-sm text-gray-700">Feature this fundraiser (requires admin approval)</label>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex justify-end space-x-4 pt-6 border-t">
            <button type="button" wire:click="$dispatch('closeModal')" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
                Cancel
            </button>
            <button type="submit" wire:loading.attr="disabled" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50">
                <span wire:loading.remove>Create Fundraiser</span>
                <span wire:loading>Creating...</span>
            </button>
        </div>
    </form>
</div>
