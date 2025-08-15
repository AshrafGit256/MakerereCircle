<div class="max-w-6xl mx-auto p-4">
    <header class="mb-4 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Events</h1>
            <p class="text-sm text-gray-600">All happenings on and around campus.</p>
        </div>
        <a href="{{ url('/#create') }}" class="text-sm bg-blue-600 text-white px-3 py-1.5 rounded-md">+ Create</a>
    </header>

    @php
        use App\Models\Post;
        $events = Post::query()
            ->where('is_event', true)
            ->orderByRaw('COALESCE(event_start_at, created_at) ASC')
            ->paginate(20);
    @endphp

    @if($events->count() === 0)
        <div class="p-6 border rounded-md bg-white text-center text-gray-600">No events yet.</div>
    @else
        <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($events as $e)
            <li class="bg-white border rounded-lg p-4">
                <h3 class="font-semibold text-gray-800 line-clamp-2">{{ Str::limit($e->title ?? ($e->description ?? ''), 80) }}</h3>
                <div class="mt-2 text-sm text-gray-600 flex flex-col gap-1">
                    <div><span class="text-gray-400">Location:</span> {{ $e->event_location ?? '—' }}</div>
                    <div><span class="text-gray-400">Starts:</span> {{ optional($e->event_start_at)->format('M d, Y H:i') ?? '—' }}</div>
                    @if($e->event_end_at)
                    <div><span class="text-gray-400">Ends:</span> {{ optional($e->event_end_at)->format('M d, Y H:i') }}</div>
                    @endif
                </div>
                <div class="mt-3 flex items-center gap-2">
                    <a href="{{ route('post', $e->id) }}" class="text-blue-600 text-sm">View</a>
                    @if($e->event_start_at)
                    <a href="https://calendar.google.com/calendar/render?action=TEMPLATE&text={{ urlencode($e->title ?? 'Event') }}&dates={{ $e->event_start_at->format('Ymd\THis\Z') }}/{{ optional($e->event_end_at)->format('Ymd\THis\Z') }}&details={{ urlencode(Str::limit($e->description,160)) }}&location={{ urlencode($e->event_location ?? '') }}" target="_blank" class="text-sm text-gray-600">Add to Calendar</a>
                    @endif
                </div>
            </li>
            @endforeach
        </ul>
        <div class="mt-4">{{ $events->links() }}</div>
    @endif
</div>
