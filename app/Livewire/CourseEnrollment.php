<?php

namespace App\Livewire;

use App\Models\CourseUnit;
use App\Models\Enrollment;
use App\Models\User;
use Livewire\Component;

class CourseEnrollment extends Component
{
    public $availableCourses = [];
    public $enrolledCourses = [];
    public $message = '';
    public $activeTab = 'available';

    public function mount()
    {
        $this->loadCourses();
    }

    public function loadCourses()
    {
        $user = auth()->user();

        // Get enrolled course IDs
        $enrolledCourseIds = Enrollment::where('user_id', $user->id)
            ->where('status', 'active')
            ->pluck('course_unit_id')
            ->toArray();

        // Get available courses (not enrolled)
        $this->availableCourses = CourseUnit::with('lecturer')
            ->whereNotIn('id', $enrolledCourseIds)
            ->get();

        // Get enrolled courses
        $this->enrolledCourses = Enrollment::with(['courseUnit.lecturer', 'courseUnit.timetables'])
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->get();
    }

    public function enroll($courseId)
    {
        $user = auth()->user();

        // Check if already enrolled
        $existingEnrollment = Enrollment::where('user_id', $user->id)
            ->where('course_unit_id', $courseId)
            ->first();

        if ($existingEnrollment) {
            $this->message = 'You are already enrolled in this course.';
            return;
        }

        // Create enrollment
        Enrollment::create([
            'user_id' => $user->id,
            'course_unit_id' => $courseId,
            'status' => 'active',
            'enrollment_date' => now()
        ]);

        $this->message = 'Successfully enrolled in the course!';
        $this->loadCourses();
    }

    public function unenroll($courseId)
    {
        $user = auth()->user();

        $enrollment = Enrollment::where('user_id', $user->id)
            ->where('course_unit_id', $courseId)
            ->first();

        if ($enrollment) {
            $enrollment->update(['status' => 'dropped']);
            $this->message = 'Successfully unenrolled from the course.';
            $this->loadCourses();
        }
    }

    public function render()
    {
        return view('livewire.course-enrollment');
    }
}
