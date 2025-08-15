<div class="max-w-6xl mx-auto p-4">
    <header class="mb-4 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Market</h1>
            <p class="text-sm text-gray-600">Products listed by alumni and the community.</p>
        </div>
        <a href="{{ url('/#create') }}" class="text-sm bg-blue-600 text-white px-3 py-1.5 rounded-md">+ List Item</a>
    </header>

    @php
        use App\Models\Post;
        $items = Post::query()
            ->where('is_market', true)
            ->latest()
            ->paginate(24);
    @endphp

    @if($items->count() === 0)
        <div class="p-6 border rounded-md bg-white text-center text-gray-600">No items yet.</div>
    @else
        <ul class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($items as $it)
            <li class="bg-white border rounded-lg overflow-hidden">
                <a href="{{ route('post', $it->id) }}" class="block">
                    @php $img = optional($it->media->firstWhere('mime','image'))->url; @endphp
                    @if($img)
                        <img src="{{ $img }}" class="w-full h-40 object-cover" alt="item" />
                    @else
                        <div class="w-full h-40 bg-gray-100 flex items-center justify-center text-gray-400">No image</div>
                    @endif
                    <div class="p-3">
                        <div class="font-semibold text-sm line-clamp-2">{{ Str::limit($it->title ?? ($it->description ?? ''), 60) }}</div>
                        <div class="mt-1 text-sm text-green-700 font-bold">{{ $it->price ? 'UGX '.number_format($it->price) : '—' }}</div>
                        <div class="mt-1 text-xs text-gray-500">by {{ $it->user->name ?? 'User' }}</div>
                    </div>
                </a>
            </li>
            @endforeach
        </ul>
        <div class="mt-4">{{ $items->links() }}</div>
    @endif
</div>
