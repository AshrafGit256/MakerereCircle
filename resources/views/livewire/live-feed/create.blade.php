<div class="bg-white lg:h-[400px] flex flex-col border gap-y-4 px-5">

    {{-- Top header --}}
    <header class="w-full py-2 border-b">
        <div class="flex justify-between">

            <button wire:click="$dispatch('closeModal')"  class="font-bold">
                X
            </button>

            <div class="text-lg font-bold">
                Start Live Feed
            </div>

            <button wire:loading.attr='disabled' wire:click='submit' class="text-blue-500 font-bold">
                Go Live
            </button>
        </div>
    </header>

    <main class="flex-1 flex flex-col gap-4 p-4">

        {{-- Description --}}
        <div>
            <textarea
                wire:model="description"
                placeholder="Describe your live feed..."
                class="border-0 focus:border-0 px-0 w-full rounded-lg bg-white h-24 focus:outline-none focus:ring-0"
                required
            ></textarea>
        </div>

        {{-- Stream URL --}}
        <div>
            <input
                type="url"
                wire:model="stream_url"
                placeholder="YouTube Live Stream URL"
                class="border-0 focus:border-0 px-0 w-full rounded-lg bg-white focus:outline-none focus:ring-0"
                required
            >
        </div>

        {{-- Location --}}
        <div>
            <input
                type="text"
                wire:model="location"
                placeholder="Location (optional)"
                class="border-0 focus:border-0 px-0 w-full rounded-lg bg-white focus:outline-none focus:ring-0"
            >
        </div>

        {{-- Preview --}}
        @if(!empty($stream_url))
            <div class="mt-4">
                @php
                    $videoId = '';
                    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $stream_url, $matches)) {
                        $videoId = $matches[1];
                    }
                @endphp
                @if($videoId)
                    <iframe width="100%" height="200" src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                @endif
            </div>
        @endif

    </main>
</div>
