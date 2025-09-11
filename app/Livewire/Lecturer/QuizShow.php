<?php

namespace App\Livewire\Lecturer;

use App\Models\Enrollment;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Timetable;
use App\Services\QuestionGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

class QuizShow extends Component
{
    use WithFileUploads;

    public Quiz $quiz;

    public string $uploadContent = '';
    public $slides = null; // temporary uploaded file

    #[Layout('layouts.app')]
    public function mount(Quiz $quiz)
    {
        $this->quiz = $quiz->load(['questions' => function ($q) {
            $q->orderBy('order');
        }, 'attempts' => function ($a) {
            $a->with('user');
        }, 'courseUnit']);

        if ($this->quiz->lecturer_id !== Auth::id()) {
            abort(403);
        }
    }

    public function uploadSlides()
    {
        $this->validate([
            'slides' => 'required|file|max:10240', // 10MB
        ]);

        $path = $this->slides->store('slides', 'public');
        $this->quiz->content_file_path = $path;
        // Simple placeholder extraction. In production, parse PDF/PPTX to text.
        $this->quiz->lecture_content = 'Slides uploaded: ' . ($this->slides->getClientOriginalName() ?? 'lecture');
        $this->quiz->save();

        session()->flash('msg', 'Slides uploaded. You can now generate questions.');
    }

    public function generateQuestions(QuestionGenerator $generator)
    {
        $source = $this->quiz->lecture_content ?: ($this->quiz->description ?: $this->quiz->title);
        $count = max(1, (int)($this->quiz->total_questions ?: 10));

        $generated = $generator->generateFromContent($source, $count);
        $order = ($this->quiz->questions()->max('order') ?? 0) + 1;
        $created = 0;

        foreach ($generated as $gq) {
            $options = $gq['options'] ?? [ 'A' => 'Option A', 'B' => 'Option B', 'C' => 'Option C', 'D' => 'Option D' ];
            $correct = $gq['correct'] ?? array_key_first($options);
            Question::create([
                'quiz_id' => $this->quiz->id,
                'question_text' => $gq['text'] ?? 'What is the key concept?',
                'question_type' => 'multiple_choice',
                'options' => $options,
                'correct_answer' => $correct,
                'explanation' => $gq['explanation'] ?? null,
                'points' => $gq['points'] ?? 10,
                'time_limit' => $gq['time_limit'] ?? 30,
                'order' => $order,
                'is_active' => true,
                'metadata' => ['source' => $gq['source'] ?? 'ai-or-fallback'],
            ]);
            $order++; $created++;
        }

        $this->quiz->refresh();
        session()->flash('msg', "Generated {$created} questions.");
    }

    public function publish()
    {
        $this->quiz->status = 'published';
        $this->quiz->is_active = true;
        $this->quiz->save();
        session()->flash('msg', 'Quiz published. Students can play now.');
    }

    public function complete()
    {
        $this->quiz->status = 'completed';
        $this->quiz->is_active = false;
        $this->quiz->ended_at = now();
        $this->quiz->save();
        session()->flash('msg', 'Quiz closed.');
    }

    public function render()
    {
        // Leaderboard with risk flag if low score but marked present in attendance
        $attempts = QuizAttempt::where('quiz_id', $this->quiz->id)
            ->where('status', 'completed')
            ->with('user')
            ->orderByDesc('total_score')
            ->get();

        // Attendance linkage via Timetable if available
        $attendanceMap = [];
        if ($this->quiz->timetable_id) {
            $tt = Timetable::find($this->quiz->timetable_id);
            if ($tt) {
                foreach ($tt->attendances as $a) {
                    $attendanceMap[$a->user_id] = $a->status; // e.g., present/absent
                }
            }
        }

        $leaderboard = $attempts->map(function ($a) use ($attendanceMap) {
            $present = $attendanceMap[$a->user_id] ?? null;
            $risk = ($present === 'present') && ($a->total_score < max(10, (int) round($a->max_possible_score * 0.3)));
            return [
                'user' => $a->user,
                'score' => $a->total_score,
                'correct' => $a->correct_answers,
                'time' => $a->time_taken,
                'feedback' => $a->feedback,
                'present' => $present,
                'risk' => $risk,
            ];
        });

        return view('livewire.lecturer.quiz-show', [
            'leaderboard' => $leaderboard,
        ]);
    }
}
