<div class="p-6">
    <h2 class="text-2xl font-bold mb-6">Attendance Reports</h2>

    <!-- Filters -->
    <div class="mb-6 flex gap-4">
        <div>
            <label class="block text-sm font-medium mb-2">Course Unit</label>
            <select wire:model.live="selectedCourse" class="select select-bordered">
                <option value="">All Courses</option>
                @foreach($courses as $course)
                <option value="{{ $course->id }}">{{ $course->name }} ({{ $course->code }})</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Month</label>
            <input type="month" wire:model.live="selectedMonth" class="input input-bordered">
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="card bg-green-100 shadow">
            <div class="card-body text-center">
                <h3 class="text-2xl font-bold text-green-600">{{ $attendanceData->where('status', 'present')->count() }}</h3>
                <p class="text-sm">Present</p>
            </div>
        </div>
        <div class="card bg-red-100 shadow">
            <div class="card-body text-center">
                <h3 class="text-2xl font-bold text-red-600">{{ $attendanceData->where('status', 'absent')->count() }}</h3>
                <p class="text-sm">Absent</p>
            </div>
        </div>
        <div class="card bg-yellow-100 shadow">
            <div class="card-body text-center">
                <h3 class="text-2xl font-bold text-yellow-600">{{ $attendanceData->where('status', 'late')->count() }}</h3>
                <p class="text-sm">Late</p>
            </div>
        </div>
        <div class="card bg-blue-100 shadow">
            <div class="card-body text-center">
                <h3 class="text-2xl font-bold text-blue-600">{{ $attendanceData->unique('user_id')->count() }}</h3>
                <p class="text-sm">Unique Students</p>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="card bg-base-100 shadow mb-6">
        <div class="card-body">
            <h3 class="card-title">Daily Attendance Trend</h3>
            <canvas id="attendanceChart" width="400" height="200"></canvas>
        </div>
    </div>

    <!-- Detailed Table -->
    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <h3 class="card-title">Detailed Attendance</h3>
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Student</th>
                            <th>Course Unit</th>
                            <th>Status</th>
                            <th>Time Marked</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendanceData as $attendance)
                        <tr>
                            <td>{{ $attendance->date->format('M d, Y') }}</td>
                            <td>{{ $attendance->user->name }}</td>
                            <td>{{ $attendance->timetable->courseUnit->name }}</td>
                            <td>
                                <span class="badge {{ $attendance->status === 'present' ? 'badge-success' : ($attendance->status === 'absent' ? 'badge-error' : 'badge-warning') }}">
                                    {{ ucfirst($attendance->status) }}
                                </span>
                            </td>
                            <td>{{ $attendance->marked_at ? $attendance->marked_at->format('H:i') : 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">No attendance records found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('livewire:loaded', () => {
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    const chartData = @json($chartData);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [{
                label: 'Present',
                data: chartData.present,
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.1
            }, {
                label: 'Absent',
                data: chartData.absent,
                borderColor: 'rgb(239, 68, 68)',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                tension: 0.1
            }, {
                label: 'Late',
                data: chartData.late,
                borderColor: 'rgb(245, 158, 11)',
                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
