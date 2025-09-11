<div class="min-h-screen bg-gray-50 w-full">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Academic Dashboard</h1>
                    <p class="text-gray-600">{{ $currentSemester }}</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Current GPA</p>
                        <p class="text-2xl font-bold text-blue-600">{{ number_format($gpa, 1) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">CGPA</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($cgpa, 1) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Navigation Tabs -->
        <div class="mb-8">
            <nav class="flex space-x-8" aria-label="Tabs">
                <button wire:click="setActiveTab('overview')"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'overview' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Overview
                </button>
                <button wire:click="setActiveTab('timetable')"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'timetable' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Timetable
                </button>
                <button wire:click="setActiveTab('attendance')"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'attendance' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Attendance
                </button>
                <button wire:click="setActiveTab('grades')"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'grades' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Grades
                </button>
                <button wire:click="setActiveTab('events')"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'events' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Events
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        @if($activeTab === 'overview')
            <!-- Overview Tab -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Progress Card -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Credits Completed</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $completedCredits }}/{{ $totalCredits }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($completedCredits / $totalCredits) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attendance Card -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Attendance Rate</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $attendanceStats['percentage'] ?? 0 }}%</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm text-gray-600">
                            {{ $attendanceStats['present_count'] ?? 0 }} present, {{ $attendanceStats['absent_count'] ?? 0 }} absent
                        </div>
                    </div>
                </div>

                <!-- GPA Card -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Current GPA</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ number_format($gpa, 1) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Events Card -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Upcoming Events</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ count($upcomingEvents) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <button wire:click="setActiveTab('timetable')" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">View Timetable</p>
                            <p class="text-sm text-gray-500">Check your class schedule</p>
                        </div>
                    </button>

                    <button wire:click="setActiveTab('attendance')" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Mark Attendance</p>
                            <p class="text-sm text-gray-500">Record your attendance</p>
                        </div>
                    </button>

                    <button wire:click="setActiveTab('grades')" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                        <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">View Grades</p>
                            <p class="text-sm text-gray-500">Check your academic performance</p>
                        </div>
                    </button>
                </div>
            </div>

        @elseif($activeTab === 'timetable')
            <!-- Timetable Tab -->
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Class Timetable</h2>
                    <select wire:model.live="selectedDay" class="select select-bordered">
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                        <option value="Sunday">Sunday</option>
                    </select>
                </div>

                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Course Unit</th>
                                <th>Room</th>
                                <th>Type</th>
                                <th>Attendance</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($timetables as $timetable)
                            <tr>
                                <td>{{ $timetable->start_time->format('H:i') }} - {{ $timetable->end_time->format('H:i') }}</td>
                                <td>{{ $timetable->courseUnit->name }} ({{ $timetable->courseUnit->code }})</td>
                                <td>{{ $timetable->room }}</td>
                                <td>
                                    <span class="badge {{ $timetable->type === 'day' ? 'badge-primary' : 'badge-secondary' }}">
                                        {{ ucfirst($timetable->type) }}
                                    </span>
                                </td>
                                <td>
                                    @if($timetable->attendances->isNotEmpty())
                                        <span class="text-green-600 font-semibold">Present</span>
                                    @else
                                        <span class="text-gray-500">Not Marked</span>
                                    @endif
                                </td>
                                <td>
                                    @if($timetable->attendances->isEmpty() && $timetable->attendance_code)
                                        <button class="btn btn-sm btn-primary" onclick="document.getElementById('attendance-modal-{{ $timetable->id }}').showModal()">
                                            Mark Attendance
                                        </button>
                                    @elseif($timetable->attendances->isNotEmpty())
                                        <span class="text-green-600">✓ Marked</span>
                                    @else
                                        <span class="text-gray-500">No Code</span>
                                    @endif
                                </td>
                            </tr>

                            <!-- Attendance Modal -->
                            <dialog id="attendance-modal-{{ $timetable->id }}" class="modal">
                                <div class="modal-box">
                                    <h3 class="font-bold text-lg">Mark Attendance</h3>
                                    <p class="py-4">Enter the attendance code for {{ $timetable->courseUnit->name }}</p>
                                    <input type="text" wire:model="attendanceCode" placeholder="Enter code" class="input input-bordered w-full" />
                                    <div class="modal-action">
                                        <button class="btn" onclick="document.getElementById('attendance-modal-{{ $timetable->id }}').close()">Cancel</button>
                                        <button wire:click="markAttendance({{ $timetable->id }})" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </dialog>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">No classes scheduled for {{ $selectedDay }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        @elseif($activeTab === 'attendance')
            <!-- Attendance Tab -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Attendance Statistics</h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600">{{ $attendanceStats['present_count'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Present</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-red-600">{{ $attendanceStats['absent_count'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Absent</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-yellow-600">{{ $attendanceStats['late_count'] ?? 0 }}</div>
                        <div class="text-sm text-gray-600">Late</div>
                    </div>
                </div>

                <div class="text-center mb-8">
                    <div class="text-4xl font-bold text-blue-600">{{ $attendanceStats['percentage'] ?? 0 }}%</div>
                    <div class="text-sm text-gray-600">Overall Attendance Rate</div>
                </div>

                <div class="w-full bg-gray-200 rounded-full h-4 mb-8">
                    <div class="bg-blue-600 h-4 rounded-full" style="width: {{ $attendanceStats['percentage'] ?? 0 }}%"></div>
                </div>
            </div>

        @elseif($activeTab === 'grades')
            <!-- Grades Tab -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Grade History</h2>

                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Grade</th>
                                <th>Points</th>
                                <th>Semester</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentGrades as $grade)
                            <tr>
                                <td>{{ $grade['course'] }}</td>
                                <td>
                                    <span class="badge {{ $grade['grade'] === 'A' ? 'badge-success' : ($grade['grade'] === 'B+' ? 'badge-warning' : 'badge-info') }}">
                                        {{ $grade['grade'] }}
                                    </span>
                                </td>
                                <td>{{ $grade['points'] }}</td>
                                <td>{{ $grade['semester'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        @elseif($activeTab === 'events')
            <!-- Events Tab -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Upcoming Events</h2>

                <div class="space-y-4">
                    @foreach($upcomingEvents as $event)
                    <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 {{ $event['type'] === 'exam' ? 'text-red-600' : ($event['type'] === 'assignment' ? 'text-blue-600' : 'text-green-600') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-sm font-medium text-gray-900">{{ $event['title'] }}</h3>
                            <p class="text-sm text-gray-600">{{ $event['course'] }}</p>
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($event['date'])->format('M d, Y') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $event['type'] === 'exam' ? 'bg-red-100 text-red-800' : ($event['type'] === 'assignment' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                {{ ucfirst($event['type']) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Message Display -->
        @if($message)
            <div class="alert alert-info mt-4">
                {{ $message }}
            </div>
        @endif
    </div>
</div>
