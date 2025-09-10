<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Course Enrollment</h1>
            <p class="text-gray-600">Enroll in courses to track your attendance and academic progress</p>
        </div>

        <!-- Message Display -->
        @if($message)
            <div class="mb-6 p-4 rounded-lg {{ str_contains($message, 'Successfully') ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700' }} border">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        @if(str_contains($message, 'Successfully'))
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        @else
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        @endif
                    </svg>
                    {{ $message }}
                </div>
            </div>
        @endif

        <!-- Tabs -->
        <div class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <button wire:click="$set('activeTab', 'available')" class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'available' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Available Courses
                    </button>
                    <button wire:click="$set('activeTab', 'enrolled')" class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'enrolled' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        My Courses
                    </button>
                </nav>
            </div>
        </div>

        <!-- Available Courses Tab -->
        @if($activeTab === 'available')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($availableCourses as $course)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 mb-1">{{ $course->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $course->code }}</p>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $course->activeEnrollments->count() }} students
                                </span>
                            </div>

                            <div class="mb-4">
                                <p class="text-sm text-gray-600 mb-2">
                                    <span class="font-medium">Lecturer:</span> {{ $course->lecturer->name }}
                                </p>
                                @if($course->description)
                                    <p class="text-sm text-gray-700">{{ Str::limit($course->description, 100) }}</p>
                                @endif
                            </div>

                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Schedule:</h4>
                                <div class="space-y-1">
                                    @foreach($course->timetables as $timetable)
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $timetable->day }}: {{ $timetable->start_time->format('H:i') }} - {{ $timetable->end_time->format('H:i') }} ({{ $timetable->room }})
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <button wire:click="enroll({{ $course->id }})"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                                Enroll Now
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No courses available</h3>
                        <p class="mt-1 text-sm text-gray-500">All courses have been enrolled in or no courses are currently available.</p>
                    </div>
                @endforelse
            </div>
        @endif

        <!-- My Courses Tab -->
        @if($activeTab === 'enrolled')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($enrolledCourses as $enrollment)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-green-500 px-4 py-2">
                            <span class="text-white text-sm font-medium">Enrolled</span>
                        </div>
                        <div class="p-6">
                            <div class="mb-4">
                                <h3 class="text-xl font-semibold text-gray-900 mb-1">{{ $enrollment->courseUnit->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $enrollment->courseUnit->code }}</p>
                            </div>

                            <div class="mb-4">
                                <p class="text-sm text-gray-600 mb-2">
                                    <span class="font-medium">Lecturer:</span> {{ $enrollment->courseUnit->lecturer->name }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Enrolled:</span> {{ $enrollment->enrollment_date->format('M j, Y') }}
                                </p>
                            </div>

                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Upcoming Classes:</h4>
                                <div class="space-y-1">
                                    @foreach($enrollment->courseUnit->timetables as $timetable)
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $timetable->day }}: {{ $timetable->start_time->format('H:i') }} - {{ $timetable->end_time->format('H:i') }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="flex space-x-2">
                                <button class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg transition duration-200">
                                    View Details
                                </button>
                                <button wire:click="unenroll({{ $enrollment->course_unit_id }})"
                                        class="bg-red-100 hover:bg-red-200 text-red-700 font-medium py-2 px-4 rounded-lg transition duration-200">
                                    Unenroll
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No enrolled courses</h3>
                        <p class="mt-1 text-sm text-gray-500">You haven't enrolled in any courses yet. Browse available courses to get started.</p>
                    </div>
                @endforelse
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('livewire:loaded', () => {
    // Add any JavaScript for enhanced UX
    Livewire.on('enrollment-updated', () => {
        // Could add toast notifications or animations here
    });
});
</script>
