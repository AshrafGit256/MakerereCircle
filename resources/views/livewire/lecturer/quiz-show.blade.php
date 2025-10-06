<div class="max-w-6xl mx-auto p-4">
    <div class="flex items-start justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold">{{ $quiz->title }}</h1>
            <div class="text-sm text-gray-500">{{ $quiz->courseUnit->code ?? '' }} • Status: <span class="px-2 py-0.5 bg-gray-100 rounded">{{ $quiz->status }}</span></div>
        </div>
        <div class="flex gap-2">
            <form wire:submit.prevent="uploadSlides" class="flex items-center gap-2" enctype="multipart/form-data">
                <input type="file" wire:model="slides" class="text-sm" />
                <button type="submit" class="px-3 py-2 bg-gray-700 text-white rounded">Upload Slides</button>
            </form>
            <button class="px-3 py-2 bg-violet-600 text-white rounded" wire:click="generateQuestions">AI Generate {{ $quiz->total_questions }}</button>
            @if ($quiz->is_active)
                <button class="px-3 py-2 bg-red-600 text-white rounded" wire:click="complete">Close Quiz</button>
            @else
                <button class="px-3 py-2 bg-green-600 text-white rounded" wire:click="publish">Publish</button>
            @endif
        </div>
    </div>

    @if (session('msg'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded">{{ session('msg') }}</div>
    @endif

    <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-white shadow rounded p-4">
            <h2 class="font-semibold mb-3">Questions ({{ $quiz->questions->count() }})</h2>
            <ol class="list-decimal pl-5 space-y-3">
                @foreach ($quiz->questions as $q)
                    <li>
                        <div class="font-medium">{!! nl2br(e($q->question_text)) !!}</div>
                        <div class="grid grid-cols-2 gap-2 mt-2 text-sm">
                            @foreach ($q->options as $k => $opt)
                                <div class="p-2 border rounded {{ $k == $q->correct_answer ? 'bg-green-50 border-green-300' : '' }}">
                                    <span class="font-semibold">{{ $k }}.</span> {{ is_string($opt) ? $opt : json_encode($opt) }}
                                </div>
                            @endforeach
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>

        <div class="bg-white shadow rounded p-4">
            <h2 class="font-semibold mb-3">Leaderboard & Retention</h2>
            <div class="divide-y">
                @forelse ($leaderboard as $row)
                    <div class="py-3">
                        <div class="flex justify-between">
                            <div>
                                <div class="font-medium">{{ $row['user']->name }}</div>
                                <div class="text-xs text-gray-500">{{ $row['present'] === 'present' ? 'Attendance: Present' : ($row['present'] ?? 'Attendance: N/A') }}</div>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold">{{ $row['score'] }} pts</div>
                                <div class="text-xs text-gray-500">Correct: {{ $row['correct'] }} • Time: {{ $row['time'] }}s</div>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600 mt-1">{{ $row['feedback'] }}</div>
                        @if ($row['risk'])
                            <div class="mt-1 text-xs text-red-700 bg-red-50 border border-red-200 inline-block px-2 py-0.5 rounded">Low retention flagged. Consider roll call verification.</div>
                        @endif
                    </div>
                @empty
                    <div class="text-gray-500">No attempts yet.</div>
                @endforelse
            </div>

            <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded text-sm">
                Share with students: <code class="px-1 bg-gray-100 rounded">{{ route('quiz.play', ['quiz' => $quiz->id]) }}</code>
            </div>
        </div>
    </div>
</div>
