<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">My Course Units</h2>
        <button wire:click="showCreateForm" class="btn btn-primary">Add New Course Unit</button>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success mb-4">
            {{ session('message') }}
        </div>
    @endif

    <!-- Course Units List -->
    <div class="grid gap-4">
        @forelse($courseUnits as $course)
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="card-title">{{ $course->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $course->code }}</p>
                        <p class="text-sm">{{ $course->description }}</p>
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="editCourseUnit({{ $course->id }})" class="btn btn-sm btn-outline">Edit</button>
                        <button wire:click="deleteCourseUnit({{ $course->id }})" class="btn btn-sm btn-error"
                                onclick="return confirm('Are you sure you want to delete this course unit?')">Delete</button>
                    </div>
                </div>

                <!-- Quizzes -->
                <div class="mt-6">
                    <h4 class="font-semibold mb-2">Quizzes</h4>
                    @if($course->quizzes && $course->quizzes->count())
                        <div class="space-y-2">
                            @foreach($course->quizzes as $q)
                                <div class="flex justify-between items-center p-2 border rounded">
                                    <div>
                                        <div class="font-medium">{{ $q->title }} <span class="text-xs px-2 py-0.5 bg-gray-100 rounded">{{ $q->status }}</span></div>
                                        <div class="text-xs text-gray-500">Questions: {{ $q->questions()->count() }} • Attempts: {{ $q->attempts()->count() }}</div>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('lecturer.quiz.show', ['quiz' => $q->id]) }}" class="btn btn-sm">Manage</a>
                                        @if($q->is_active)
                                            <a href="{{ route('quiz.play', ['quiz' => $q->id]) }}" class="btn btn-sm btn-primary" target="_blank">Start Now</a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No quizzes yet for this course.</p>
                    @endif
                    <div class="mt-2">
                        <a href="{{ route('lecturer.quizzes') }}" class="link link-primary">Create/Manage Quizzes</a>
                    </div>
                </div>

                <!-- Timetables -->
                <div class="mt-4">
                    <h4 class="font-semibold mb-2">Timetables:</h4>
                    @if($course->timetables->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="table table-compact w-full">
                                <thead>
                                    <tr>
                                        <th>Day</th>
                                        <th>Time</th>
                                        <th>Room</th>
                                        <th>Type</th>
                                        <th>Code</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($course->timetables as $timetable)
                                    <tr>
                                        <td>{{ $timetable->day }}</td>
                                        <td>{{ $timetable->start_time->format('H:i') }} - {{ $timetable->end_time->format('H:i') }}</td>
                                        <td>{{ $timetable->room }}</td>
                                        <td>
                                            <span class="badge {{ $timetable->type === 'day' ? 'badge-primary' : 'badge-secondary' }}">
                                                {{ ucfirst($timetable->type) }}
                                            </span>
                                        </td>
                                        <td>{{ $timetable->attendance_code }}</td>
                                        <td>
                                            @if(!$timetable->is_cancelled)
                                                <button wire:click="cancelTimetable({{ $timetable->id }})"
                                                        class="btn btn-sm btn-warning"
                                                        onclick="return confirm('Are you sure you want to cancel this class?')">Cancel</button>
                                            @else
                                                <span class="text-red-600 font-semibold">Cancelled</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">No timetables set</p>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-8">
            <p class="text-gray-500">No course units found. Create your first course unit!</p>
        </div>
        @endforelse
    </div>

    <!-- Create/Edit Modal -->
    @if($showCreateForm)
    <div class="modal modal-open">
        <div class="modal-box max-w-4xl">
            <h3 class="font-bold text-lg mb-4">{{ $editingCourse ? 'Edit' : 'Create' }} Course Unit</h3>

            <form wire:submit.prevent="saveCourseUnit">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Course Name</label>
                        <input type="text" wire:model="name" class="input input-bordered w-full" required>
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Course Code</label>
                        <input type="text" wire:model="code" class="input input-bordered w-full" required>
                        @error('code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Description</label>
                    <textarea wire:model="description" class="textarea textarea-bordered w-full" rows="3"></textarea>
                </div>

                <!-- Timetables Section -->
                <div class="mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <h4 class="font-semibold">Timetables</h4>
                        <button type="button" wire:click="addTimetable" class="btn btn-sm btn-outline">Add Timetable</button>
                    </div>

                    <div class="space-y-2">
                        @foreach($timetables as $index => $timetable)
                        <div class="flex gap-2 items-center bg-gray-50 p-3 rounded">
                            <select wire:model="timetables.{{ $index }}.day" class="select select-sm select-bordered">
                                <option value="Monday">Monday</option>
                                <option value="Tuesday">Tuesday</option>
                                <option value="Wednesday">Wednesday</option>
                                <option value="Thursday">Thursday</option>
                                <option value="Friday">Friday</option>
                                <option value="Saturday">Saturday</option>
                                <option value="Sunday">Sunday</option>
                            </select>
                            <input type="time" wire:model="timetables.{{ $index }}.start_time" class="input input-sm input-bordered">
                            <input type="time" wire:model="timetables.{{ $index }}.end_time" class="input input-sm input-bordered">
                            <input type="text" wire:model="timetables.{{ $index }}.room" placeholder="Room" class="input input-sm input-bordered">
                            <select wire:model="timetables.{{ $index }}.type" class="select select-sm select-bordered">
                                <option value="day">Day</option>
                                <option value="evening">Evening</option>
                            </select>
                            <button type="button" wire:click="removeTimetable({{ $index }})" class="btn btn-sm btn-error">Remove</button>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="modal-action">
                    <button type="button" wire:click="hideCreateForm" class="btn">Cancel</button>
                    <button type="submit" class="btn btn-primary">{{ $editingCourse ? 'Update' : 'Create' }} Course Unit</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
