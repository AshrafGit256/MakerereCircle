<div class="max-w-3xl mx-auto p-3 space-y-4">
    <header class="bg-white border rounded-xl p-4 flex items-center gap-4">
        <img src="{{ $group->image ?? asset('assets/dist/img/cedat.jpg') }}" class="w-16 h-16 rounded-full object-cover border" />
        <div class="flex-1">
            <h1 class="text-xl font-bold text-gray-800">{{ $group->name }}</h1>
            <p class="text-sm text-gray-500">{{ $group->description }}</p>
        </div>
        @auth
        <button wire:click="toggleMembership" class="px-4 py-2 rounded-md text-sm font-semibold border bg-gray-100 hover:bg-gray-200">
            {{ $group->members()->where('user_id', auth()->id())->exists() ? 'Leave' : 'Join' }}
        </button>
        @endauth
    </header>

    @auth
    <section class="bg-white border rounded-xl p-4">
        <form wire:submit.prevent="post" class="space-y-2">
            <textarea wire:model.defer="body" rows="2" placeholder="Share to {{ $group->name }}..." class="w-full border rounded-md p-2 text-sm"></textarea>
            <div class="flex items-center justify-between">
                <label class="cursor-pointer text-blue-600 text-sm font-medium">
                    <input type="file" class="hidden" wire:model="media" accept=".jpg,.jpeg,.png,.mp4,.mov" />
                    + Media
                </label>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-1.5 rounded-md" wire:loading.attr="disabled" wire:target="post,media">Post</button>
            </div>
            @if($media)
            <div class="mt-2">
                @php
                    $mime = method_exists($media, 'getMimeType') ? $media->getMimeType() : '';
                    $isVideo = strpos($mime, 'video') !== false;
                @endphp
                @if($isVideo)
                    <video class="w-full max-h-60 rounded-md border" controls>
                        <source src="{{ $media->temporaryUrl() }}" />
                    </video>
                @else
                    <img src="{{ $media->temporaryUrl() }}" class="w-full max-h-60 object-contain rounded-md border" />
                @endif
            </div>
            @endif
        </form>
    </section>
    @endauth

    <section class="space-y-3">
        @forelse ($posts as $post)
            <livewire:post.item :post="$post" :wire:key="'group-post-'.$post->id" />
        @empty
            <p class="text-center text-gray-500">No posts yet. Be the first to share something!</p>
        @endforelse
    </section>
</div>
