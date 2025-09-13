<div class="max-w-3xl mx-auto p-4">
    <h1 class="text-2xl font-bold mb-2">{{ $quiz->title }}</h1>
    <p class="text-gray-600 mb-4">{{ $quiz->description }}</p>

    @if (session('quiz_done'))
    <div class="bg-green-50 border border-green-200 p-4 rounded">
        <h2 class="text-xl font-semibold mb-2">Quiz Completed</h2>
        <p>Total Score: <strong>{{ $attempt->total_score }}</strong> / {{ $attempt->max_possible_score }}</p>
        <p>Correct Answers: <strong>{{ $attempt->correct_answers }}</strong> / {{ $attempt->total_questions }}</p>
        <p>Time Taken: <strong>{{ $attempt->time_taken }}s</strong></p>
        <p class="mt-2">Feedback: {{ $attempt->feedback }}</p>
    </div>
    @else
    @if ($this->question)
    <div class="mb-4">
        <div class="flex justify-between mb-2">
            <div>Question {{ $index + 1 }} of {{ $quiz->questions->count() }}</div>
            <div class="text-sm text-gray-500">Time limit: {{ $this->question->time_limit ?? 30 }}s</div>
        </div>
        <div class="bg-white shadow rounded p-4">
            <p class="font-medium mb-3">{!! nl2br(e($this->question->question_text)) !!}</p>

            @if ($this->question->question_type === 'multiple_choice' || $this->question->question_type === 'true_false')
            <div class="space-y-2">
                @foreach (($this->question->options ?? []) as $key => $opt)
                <label class="flex items-center gap-2 p-2 border rounded cursor-pointer">
                    <input type="radio" name="opt" value="{{ $key }}" wire:model.defer="selected">
                    <span>{{ is_string($opt) ? $opt : json_encode($opt) }}</span>
                </label>
                @endforeach
            </div>
            @elseif ($this->question->question_type === 'short_answer')
            <input type="text" class="w-full border rounded p-2" placeholder="Type your answer" wire:model.defer="selected" />
            @endif

            <div class="mt-4 flex justify-between items-center">
                <div class="text-sm text-gray-500">Speed affects points. Faster = more.</div>
                <button class="bg-blue-600 text-white px-4 py-2 rounded" wire:click="submitAnswer" @disabled="!$selected">Submit</button>
            </div>
        </div>
    </div>
    @else
    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded">Loading next question...</div>
    @endif

    <div class="mt-4">
        <progress value="{{ $index }}" max="{{ $quiz->questions->count() }}" class="w-full"></progress>
    </div>
    @endif
</div>