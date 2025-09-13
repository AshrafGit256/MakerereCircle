<div class="container mx-auto py-8">
    <div class="bg-white rounded-lg shadow-md mb-8">
        <div class="relative h-64 bg-cover bg-center" style="background-image: url('{{ $college->image ? asset('storage/' . $college->image) : asset('images/default-college.jpg') }}');">
            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-end p-6">
                <h1 class="text-4xl font-bold text-white">{{ $college->name }}</h1>
            </div>
        </div>
        <div class="p-6">
            <p class="text-gray-700">{{ $college->description }}</p>
            <p class="text-gray-500 mt-2">Members: {{ $members->count() }}</p>
        </div>
    </div>

    @auth
    <!-- Quick Composer scoped to this college -->
    <section class="bg-white border rounded-lg p-3 mb-8">
        <form wire:submit.prevent="quickPost" class="space-y-2">
            <div class="flex items-start gap-3">
                <x-avatar class="w-10 h-10" src="{{ auth()->user()->getImage() }}" />
                <textarea
                    wire:model.defer="newPostText"
                    rows="2"
                    placeholder="Share something with {{ $college->name }}..."
                    class="w-full resize-none bg-transparent outline-none border border-gray-200 focus:border-blue-400 rounded-md p-2 text-sm"></textarea>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <label class="cursor-pointer text-green-600 text-sm font-medium">
                        <input type="file" class="hidden" wire:model="newPostImages" multiple accept=".jpg,.jpeg,.png" />
                        + Media
                    </label>
                    <div wire:loading wire:target="newPostImages" class="text-xs text-gray-500">Uploading…</div>
                    @if(!empty($newPostImages))
                        <span class="text-xs text-gray-600">{{ count($newPostImages) }} selected</span>
                    @endif
                </div>
                <button
                    type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-1.5 rounded-md disabled:opacity-50"
                    wire:loading.attr="disabled"
                    wire:target="quickPost,newPostImages"
                >Post</button>
            </div>
        </form>
    </section>
    @endauth

    <!-- Leaders Section -->
    <section class="mb-8">
        <h2 class="text-2xl font-bold mb-4">Leaders</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($leaders as $leader)
                <div class="bg-white rounded-lg shadow-md p-4 text-center">
                    <img src="{{ $leader->profile_photo_url ?? asset('images/default-user.jpg') }}" alt="{{ $leader->name }}" class="w-16 h-16 rounded-full mx-auto mb-2">
                    <h3 class="font-semibold">{{ $leader->name }}</h3>
                    <p class="text-sm text-gray-600">{{ $leader->title ?? 'Leader' }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Admins Section -->
    <section class="mb-8">
        <h2 class="text-2xl font-bold mb-4">Top Administrators</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($admins as $admin)
                <div class="bg-white rounded-lg shadow-md p-4 text-center">
                    <img src="{{ $admin->profile_photo_url ?? asset('images/default-user.jpg') }}" alt="{{ $admin->name }}" class="w-16 h-16 rounded-full mx-auto mb-2">
                    <h3 class="font-semibold">{{ $admin->name }}</h3>
                    <p class="text-sm text-gray-600">{{ $admin->title ?? 'Administrator' }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Posts Feed -->
    <section class="mb-8">
        <h2 class="text-2xl font-bold mb-4">College Posts</h2>
        <div class="space-y-4">
            @foreach($posts as $post)
                @livewire('post.item', ['post' => $post], key($post->id))
            @endforeach
        </div>
        {{ $posts->links() }}
    </section>

    <!-- College Chat Section -->
    <section>
        <h2 class="text-2xl font-bold mb-4">College Chat</h2>
        <div class="bg-white rounded-lg shadow-md p-6">
            <p class="text-gray-600">College-specific chat will be implemented here, showing conversations with college_id = {{ $college->id }}.</p>
            {{-- Integrate existing chat component scoped to college --}}
            @livewire('chat.chat-list', ['collegeId' => $college->id])
        </div>
    </section>
</div>
