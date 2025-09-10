<?php

namespace App\Livewire\Lecturer;

use App\Models\CourseUnit;
use App\Models\Attendance;
use Livewire\Component;
use Carbon\Carbon;

class AttendanceReports extends Component
{
    public $selectedCourse = '';
    public $selectedMonth = '';
    public $attendanceData = [];
    public $chartData = [];

    public function mount()
    {
        $this->selectedMonth = now()->format('Y-m');
        $this->loadReports();
    }

    public function updatedSelectedCourse()
    {
        $this->loadReports();
    }

    public function updatedSelectedMonth()
    {
        $this->loadReports();
    }

    public function loadReports()
    {
        $query = Attendance::with(['user', 'timetable.courseUnit'])
            ->whereHas('timetable.courseUnit', function($q) {
                $q->where('lecturer_id', auth()->id());
                if ($this->selectedCourse) {
                    $q->where('id', $this->selectedCourse);
                }
            });

        if ($this->selectedMonth) {
            $startDate = Carbon::createFromFormat('Y-m', $this->selectedMonth)->startOfMonth();
            $endDate = Carbon::createFromFormat('Y-m', $this->selectedMonth)->endOfMonth();
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        $this->attendanceData = $query->get();

        $this->prepareChartData();
    }

    public function prepareChartData()
    {
        // Prepare data for charts
        $this->chartData = [
            'labels' => [],
            'present' => [],
            'absent' => [],
            'late' => []
        ];

        if ($this->selectedMonth) {
            $startDate = Carbon::createFromFormat('Y-m', $this->selectedMonth)->startOfMonth();
            $endDate = Carbon::createFromFormat('Y-m', $this->selectedMonth)->endOfMonth();

            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $this->chartData['labels'][] = $date->format('M d');

                $dayAttendances = $this->attendanceData->where('date', $date->toDateString());

                $this->chartData['present'][] = $dayAttendances->where('status', 'present')->count();
                $this->chartData['absent'][] = $dayAttendances->where('status', 'absent')->count();
                $this->chartData['late'][] = $dayAttendances->where('status', 'late')->count();
            }
        }
    }

    public function getCoursesProperty()
    {
        return auth()->user()->taughtCourseUnits;
    }

    public function render()
    {
        return view('livewire.lecturer.attendance-reports', [
            'courses' => $this->courses,
        ]);
    }
}
