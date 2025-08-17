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
        <form wire:submit.prevent="post" class="space-y-3">
            <textarea
                wire:model.defer="body"
                rows="3"
                placeholder="Share something with {{ $group->name }}..."
                class="w-full border rounded-lg p-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            ></textarea>
            
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <label class="cursor-pointer text-blue-600 text-sm font-medium flex items-center">
                        <input type="file" class="hidden" wire:model="media" accept=".jpg,.jpeg,.png,.mp4,.mov" />
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Media
                    </label>
                    
                    <button type="button" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                </div>
                
                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition"
                    wire:loading.attr="disabled"
                    wire:target="post,media"
                >
                    <span wire:loading.remove wire:target="post,media">Post</span>
                    <span wire:loading wire:target="post,media">Posting...</span>
                </button>
            </div>
            
            @if($media)
            <div class="mt-3">
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
