<?php

namespace App\Livewire\Quiz;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Play extends Component
{
    public Quiz $quiz;
    public ?QuizAttempt $attempt = null;

    public int $index = 0; // current question index
    public int $questionStartTs = 0; // unix ts

    public ?string $selected = null;

    #[Layout('layouts.app')]
    public function mount(Quiz $quiz)
    {
        $this->quiz = $quiz->load(['questions' => function ($q) {
            $q->where('is_active', true)->orderBy('order');
        }]);

        if (!$quiz->isPublished() || !$quiz->is_active) {
            abort(403, 'Quiz is not available.');
        }

        $user = Auth::user();
        if (!$quiz->canAttemptBy($user)) {
            abort(403, 'Max attempts reached or quiz locked.');
        }

        $attemptNumber = ($quiz->attempts()->where('user_id', $user->id)->max('attempt_number') ?? 0) + 1;
        $this->attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_id' => $user->id,
            'attempt_number' => $attemptNumber,
            'status' => 'in_progress',
            'current_question' => 0,
            'total_questions' => $quiz->questions->count(),
            'correct_answers' => 0,
            'total_score' => 0,
            'max_possible_score' => $quiz->questions->sum('points'),
            'started_at' => now(),
            'answers' => [],
            'question_times' => [],
            'ip_address' => request()->ip(),
            'device_fingerprint' => request()->userAgent(),
        ]);

        $this->questionStartTs = now()->timestamp;
    }

    public function getQuestionProperty()
    {
        return $this->quiz->questions[$this->index] ?? null;
    }

    public function submitAnswer()
    {
        if (!$this->question) return;
        $q = $this->question;

        $timeSpent = max(1, now()->timestamp - $this->questionStartTs);
        $timeLimit = max(1, (int)($q->time_limit ?: 30));
        $basePoints = (int)($q->points ?: 10);

        $correct = $q->isCorrect($this->selected);
        // Speed bonus: linear from 0.5x at timeLimit to 1.5x at 0 sec
        $speedMultiplier = max(0.5, min(1.5, 1.5 - ($timeSpent / $timeLimit)));
        $pointsEarned = $correct ? (int) round($basePoints * $speedMultiplier) : 0;

        $this->attempt->addAnswer($q->id, (string)($this->selected ?? ''), $timeSpent, $correct, $pointsEarned);
        $this->attempt->save();

        $this->selected = null;
        $this->index++;
        $this->questionStartTs = now()->timestamp;

        if ($this->index >= $this->quiz->questions->count()) {
            $this->finish();
        }
    }

    protected function finish(): void
    {
        $a = $this->attempt;
        $a->status = 'completed';
        $a->completed_at = now();
        // AI-like feedback (rule-based)
        $accuracy = $a->total_questions > 0 ? ($a->correct_answers / $a->total_questions) : 0;
        $avgTime = $a->total_questions > 0 ? ($a->time_taken / $a->total_questions) : 0;
        $comment = 'Good effort.';
        if ($accuracy >= 0.8) {
            $comment = 'Excellent retention. Keep it up!';
        } elseif ($accuracy >= 0.5) {
            $comment = 'Fair retention. Review weak areas.';
        } else {
            $comment = 'Low retention. Revisit lecture slides and key concepts.';
        }
        if ($avgTime > 20) {
            $comment .= ' Work on speed for quicker recall.';
        }
        $a->feedback = $comment;
        $a->save();

        session()->flash('quiz_done', true);
    }

    public function render()
    {
        return view('livewire.quiz.play');
    }
}
