<div class="max-w-5xl mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Quizzes</h1>

    @if (session('msg'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded">{{ session('msg') }}</div>
    @endif

    <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-white shadow rounded p-4">
            <h2 class="font-semibold mb-3">Create New Quiz</h2>
            <div class="space-y-3">
                <input type="text" class="w-full border rounded p-2" placeholder="Title" wire:model.defer="form.title">
                <textarea class="w-full border rounded p-2" rows="3" placeholder="Description" wire:model.defer="form.description"></textarea>
                <div>
                    <label class="block text-sm mb-1">Course Unit</label>
                    <select class="w-full border rounded p-2" wire:model.defer="form.course_unit_id">
                        <option value="">Select course</option>
                        @foreach ($courseUnits as $cu)
                            <option value="{{ $cu->id }}">{{ $cu->code }} - {{ $cu->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm mb-1">Total Questions</label>
                        <input type="number" class="w-full border rounded p-2" wire:model.defer="form.total_questions">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Total Time (sec)</label>
                        <input type="number" class="w-full border rounded p-2" wire:model.defer="form.time_limit">
                    </div>
                </div>
                <button class="bg-blue-600 text-white px-4 py-2 rounded" wire:click="create">Create</button>
            </div>
        </div>

        <div class="bg-white shadow rounded p-4">
            <h2 class="font-semibold mb-3">Your Quizzes</h2>
            <div class="divide-y">
                @forelse ($quizzes as $q)
                    <a href="{{ route('lecturer.quiz.show', ['quiz' => $q->id]) }}" class="block py-3">
                        <div class="font-medium">{{ $q->title }} <span class="text-xs ml-2 px-2 py-0.5 rounded bg-gray-100">{{ $q->status }}</span></div>
                        <div class="text-sm text-gray-500">{{ $q->courseUnit->code ?? '' }} • {{ $q->attempts_count }} attempts</div>
                    </a>
                @empty
                    <div class="text-gray-500">No quizzes yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
