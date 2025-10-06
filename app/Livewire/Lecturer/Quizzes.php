<?php

namespace App\Livewire\Lecturer;

use App\Models\CourseUnit;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Quizzes extends Component
{
    public array $form = [
        'title' => '',
        'description' => '',
        'course_unit_id' => null,
        'total_questions' => 10,
        'time_limit' => 300,
    ];

    public ?int $selectedCourse = null;

    #[Layout('layouts.app')]
    public function render()
    {
        $lecturer = Auth::user();
        $courseUnits = CourseUnit::where('lecturer_id', $lecturer->id)->get();
        $quizzes = Quiz::where('lecturer_id', $lecturer->id)
            ->withCount('attempts')
            ->latest()
            ->get();

        return view('livewire.lecturer.quizzes', [
            'courseUnits' => $courseUnits,
            'quizzes' => $quizzes,
        ]);
    }

    public function create()
    {
        $user = Auth::user();
        $data = $this->form;
        $this->validate([
            'form.title' => 'required|string|max:255',
            'form.course_unit_id' => 'required|exists:course_units,id',
            'form.total_questions' => 'required|integer|min:1|max:50',
            'form.time_limit' => 'required|integer|min:60|max:3600',
        ]);

        $quiz = Quiz::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'course_unit_id' => $data['course_unit_id'],
            'lecturer_id' => $user->id,
            'status' => 'draft',
            'total_questions' => $data['total_questions'],
            'time_limit' => $data['time_limit'],
            'is_active' => false,
        ]);

        session()->flash('msg', 'Quiz created. Use AI to generate questions or add manually.');
        return redirect()->route('lecturer.quiz.show', ['quiz' => $quiz->id]);
    }
}
