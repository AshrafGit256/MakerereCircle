<?php

namespace App\Livewire;

use App\Models\Timetable;
use App\Models\Attendance;
use App\Models\CourseUnit;
use Livewire\Component;
use Carbon\Carbon;

class Classes extends Component
{
    public $selectedDay = 'Monday';
    public $timetables = [];
    public $attendanceCode = '';
    public $message = '';
    public $activeTab = 'overview';

    // Academic progress data
    public $currentSemester = 'Semester I 2024/2025';
    public $gpa = 3.8;
    public $cgpa = 3.6;
    public $totalCredits = 120;
    public $completedCredits = 95;
    public $attendanceStats = [];
    public $upcomingEvents = [];
    public $recentGrades = [];

    public function mount()
    {
        $this->loadTimetables();
        $this->loadAcademicData();
    }

    public function loadTimetables()
    {
        $this->timetables = Timetable::with(['courseUnit', 'attendances' => function($query) {
            $query->where('user_id', auth()->id())
                  ->where('date', today());
        }])
        ->where('day', $this->selectedDay)
        ->where('is_cancelled', false)
        ->orderBy('start_time')
        ->get();
    }

    public function loadAcademicData()
    {
        // Load attendance statistics
        $this->attendanceStats = [
            'total_classes' => Attendance::where('user_id', auth()->id())->count(),
            'present_count' => Attendance::where('user_id', auth()->id())->where('status', 'present')->count(),
            'absent_count' => Attendance::where('user_id', auth()->id())->where('status', 'absent')->count(),
            'late_count' => Attendance::where('user_id', auth()->id())->where('status', 'late')->count(),
        ];

        // Calculate attendance percentage
        if ($this->attendanceStats['total_classes'] > 0) {
            $this->attendanceStats['percentage'] = round(
                ($this->attendanceStats['present_count'] / $this->attendanceStats['total_classes']) * 100,
                1
            );
        } else {
            $this->attendanceStats['percentage'] = 0;
        }

        // Mock upcoming events (in real app, this would come from database)
        $this->upcomingEvents = [
            [
                'title' => 'Database Systems Exam',
                'date' => '2024-09-15',
                'type' => 'exam',
                'course' => 'CSC 2104'
            ],
            [
                'title' => 'Software Engineering Assignment Due',
                'date' => '2024-09-18',
                'type' => 'assignment',
                'course' => 'CSC 3102'
            ],
            [
                'title' => 'Data Structures Lab',
                'date' => '2024-09-12',
                'type' => 'lab',
                'course' => 'CSC 2101'
            ]
        ];

        // Mock recent grades
        $this->recentGrades = [
            ['course' => 'CSC 2104', 'grade' => 'A', 'points' => 4.0, 'semester' => 'Semester I 2024'],
            ['course' => 'CSC 3102', 'grade' => 'A-', 'points' => 3.7, 'semester' => 'Semester I 2024'],
            ['course' => 'CSC 2101', 'grade' => 'B+', 'points' => 3.3, 'semester' => 'Semester II 2023'],
        ];
    }

    public function updatedSelectedDay()
    {
        $this->loadTimetables();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function markAttendance($timetableId)
    {
        // Rate limiting: max 5 attempts per hour
        $attempts = cache()->get("attendance_attempts_" . auth()->id(), 0);
        if ($attempts >= 5) {
            $this->message = 'Too many attempts. Please try again later.';
            return;
        }

        $timetable = Timetable::find($timetableId);

        if (!$timetable || !$timetable->attendance_code) {
            $this->message = 'No attendance code available for this class.';
            cache()->increment("attendance_attempts_" . auth()->id(), 1, 3600);
            return;
        }

        // Check if class is cancelled
        if ($timetable->is_cancelled) {
            $this->message = 'This class has been cancelled.';
            return;
        }

        // Check if current time is within class time +/- 15 minutes
        $now = now();
        $classStart = Carbon::createFromTimeString($timetable->start_time->format('H:i'));
        $classEnd = Carbon::createFromTimeString($timetable->end_time->format('H:i'));
        $bufferStart = $classStart->copy()->subMinutes(15);
        $bufferEnd = $classEnd->copy()->addMinutes(15);

        if (!$now->between($bufferStart, $bufferEnd)) {
            $this->message = 'Attendance can only be marked during class time.';
            return;
        }

        if ($this->attendanceCode !== $timetable->attendance_code) {
            $this->message = 'Invalid attendance code.';
            cache()->increment("attendance_attempts_" . auth()->id(), 1, 3600);
            return;
        }

        // Check if already marked
        $existing = Attendance::where('user_id', auth()->id())
            ->where('timetable_id', $timetableId)
            ->where('date', today())
            ->first();

        if ($existing) {
            $this->message = 'Attendance already marked for this class.';
            return;
        }

        Attendance::create([
            'user_id' => auth()->id(),
            'timetable_id' => $timetableId,
            'date' => today(),
            'status' => 'present',
            'marked_at' => now(),
            'verification_method' => 'code',
        ]);

        // Reset attempts on success
        cache()->forget("attendance_attempts_" . auth()->id());

        $this->message = 'Attendance marked successfully!';
        $this->attendanceCode = '';
        $this->loadTimetables();
        $this->loadAcademicData();
    }

    public function render()
    {
        return view('livewire.classes');
    }
}
