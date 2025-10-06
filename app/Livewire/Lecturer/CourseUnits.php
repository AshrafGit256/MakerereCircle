<?php

namespace App\Livewire\Lecturer;

use App\Models\CourseUnit;
use App\Models\Timetable;
use Livewire\Component;

class CourseUnits extends Component
{
    public $courseUnits = [];
    public $showCreateForm = false;
    public $editingCourse = null;

    // Form fields
    public $name = '';
    public $code = '';
    public $description = '';

    // Timetable fields
    public $timetables = [];
    public $newTimetable = [
        'day' => 'Monday',
        'start_time' => '09:00',
        'end_time' => '11:00',
        'room' => '',
        'type' => 'day'
    ];

    public function mount()
    {
        $this->loadCourseUnits();
    }

    public function loadCourseUnits()
    {
        $this->courseUnits = auth()->user()->taughtCourseUnits()->with('timetables')->get();
    }

    public function showCreateForm()
    {
        $this->showCreateForm = true;
        $this->resetForm();
    }

    public function hideCreateForm()
    {
        $this->showCreateForm = false;
        $this->editingCourse = null;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->code = '';
        $this->description = '';
        $this->timetables = [];
        $this->newTimetable = [
            'day' => 'Monday',
            'start_time' => '09:00',
            'end_time' => '11:00',
            'room' => '',
            'type' => 'day'
        ];
    }

    public function addTimetable()
    {
        $this->timetables[] = $this->newTimetable;
        $this->newTimetable = [
            'day' => 'Monday',
            'start_time' => '09:00',
            'end_time' => '11:00',
            'room' => '',
            'type' => 'day'
        ];
    }

    public function removeTimetable($index)
    {
        unset($this->timetables[$index]);
        $this->timetables = array_values($this->timetables);
    }

    public function saveCourseUnit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:course_units,code' . ($this->editingCourse ? ',' . $this->editingCourse->id : ''),
            'description' => 'nullable|string',
        ]);

        if ($this->editingCourse) {
            $this->editingCourse->update([
                'name' => $this->name,
                'code' => $this->code,
                'description' => $this->description,
            ]);
            $course = $this->editingCourse;
        } else {
            $course = CourseUnit::create([
                'name' => $this->name,
                'code' => $this->code,
                'description' => $this->description,
                'lecturer_id' => auth()->id(),
            ]);
        }

        // Handle timetables
        if ($this->editingCourse) {
            // Delete existing timetables and recreate
            $this->editingCourse->timetables()->delete();
        }

        foreach ($this->timetables as $timetableData) {
            Timetable::create([
                'course_unit_id' => $course->id,
                'day' => $timetableData['day'],
                'start_time' => $timetableData['start_time'],
                'end_time' => $timetableData['end_time'],
                'room' => $timetableData['room'],
                'type' => $timetableData['type'],
                'attendance_code' => strtoupper(substr(md5(uniqid()), 0, 6)), // Generate random code
            ]);
        }

        $this->hideCreateForm();
        $this->loadCourseUnits();
        session()->flash('message', 'Course unit saved successfully!');
    }

    public function editCourseUnit($courseId)
    {
        $course = CourseUnit::find($courseId);
        if ($course && $course->lecturer_id === auth()->id()) {
            $this->editingCourse = $course;
            $this->name = $course->name;
            $this->code = $course->code;
            $this->description = $course->description;
            $this->timetables = $course->timetables->map(function($t) {
                return [
                    'day' => $t->day,
                    'start_time' => $t->start_time->format('H:i'),
                    'end_time' => $t->end_time->format('H:i'),
                    'room' => $t->room,
                    'type' => $t->type,
                ];
            })->toArray();
            $this->showCreateForm = true;
        }
    }

    public function deleteCourseUnit($courseId)
    {
        $course = CourseUnit::find($courseId);
        if ($course && $course->lecturer_id === auth()->id()) {
            $course->delete();
            $this->loadCourseUnits();
            session()->flash('message', 'Course unit deleted successfully!');
        }
    }

    public function cancelTimetable($timetableId)
    {
        $timetable = Timetable::find($timetableId);
        if ($timetable && $timetable->courseUnit->lecturer_id === auth()->id()) {
            $timetable->cancel();
            $this->loadCourseUnits();
            session()->flash('message', 'Class cancelled and students notified!');
        }
    }

    public function render()
    {
        return view('livewire.lecturer.course-units');
    }
}
