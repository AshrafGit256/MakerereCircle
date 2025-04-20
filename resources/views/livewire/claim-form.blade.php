<div class="p-4 bg-white rounded-lg">
  <h2 class="text-lg font-semibold mb-4">Claim Lost Item</h2>

  @if (session()->has('message'))
    <div class="px-3 py-2 bg-green-100 text-green-800 rounded mb-4">
      {{ session('message') }}
    </div>
  @endif

  <form wire:submit.prevent="submit" class="space-y-3">
    <div>
      <label class="block text-sm">Your Name</label>
      <input wire:model.defer="name" type="text" class="w-full border rounded px-2 py-1" />
      @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    <div>
      <label class="block text-sm">Email Address</label>
      <input wire:model.defer="email" type="email" class="w-full border rounded px-2 py-1" />
      @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    <div>
      <label class="block text-sm">Item Description</label>
      <textarea wire:model.defer="description" rows="3" class="w-full border rounded px-2 py-1"></textarea>
      @error('description') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    <div>
      <label class="block text-sm">Where You Lost It</label>
      <input wire:model.defer="location" type="text" class="w-full border rounded px-2 py-1" />
      @error('location') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="text-right">
      <button type="submit" class="px-4 py-1 bg-blue-600 text-white rounded">Submit Claim</button>
    </div>
  </form>
</div>
