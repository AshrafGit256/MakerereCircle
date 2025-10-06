<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\CourseUnit;
use App\Models\Timetable;
use App\Models\Attendance;
use App\Models\ClassNotification;
use Carbon\Carbon;

class ComprehensiveAcademicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create additional lecturers
        $lecturers = [
            [
                'name' => 'Prof. Sarah Johnson',
                'email' => 'sarah.johnson@mak.ac.ug',
                'username' => 'sarahj',
                'role' => 'lecturer'
            ],
            [
                'name' => 'Dr. Michael Chen',
                'email' => 'michael.chen@mak.ac.ug',
                'username' => 'michaelc',
                'role' => 'lecturer'
            ],
            [
                'name' => 'Dr. Grace Nakato',
                'email' => 'grace.nakato@mak.ac.ug',
                'username' => 'gracen',
                'role' => 'lecturer'
            ]
        ];

        foreach ($lecturers as $lecturerData) {
            User::firstOrCreate(
                ['email' => $lecturerData['email']],
                array_merge($lecturerData, ['password' => bcrypt('password')])
            );
        }

        // Create students
        $students = [
            ['name' => 'Alice Mutesi', 'email' => 'alice.mutesi@student.mak.ac.ug', 'username' => 'alicem'],
            ['name' => 'Bob Kagwa', 'email' => 'bob.kagwa@student.mak.ac.ug', 'username' => 'bobk'],
            ['name' => 'Carol Nansubuga', 'email' => 'carol.nansubuga@student.mak.ac.ug', 'username' => 'caroln'],
            ['name' => 'David Ochieng', 'email' => 'david.ochieng@student.mak.ac.ug', 'username' => 'davido'],
            ['name' => 'Emma Nakato', 'email' => 'emma.nakato@student.mak.ac.ug', 'username' => 'emman'],
            ['name' => 'Frank Ssali', 'email' => 'frank.ssali@student.mak.ac.ug', 'username' => 'franks'],
            ['name' => 'Grace Kyambadde', 'email' => 'grace.kyambadde@student.mak.ac.ug', 'username' => 'gracek'],
            ['name' => 'Henry Mugisha', 'email' => 'henry.mugisha@student.mak.ac.ug', 'username' => 'henrym'],
            ['name' => 'Irene Namukasa', 'email' => 'irene.namukasa@student.mak.ac.ug', 'username' => 'irenen'],
            ['name' => 'James Okello', 'email' => 'james.okello@student.mak.ac.ug', 'username' => 'jameso'],
            ['name' => 'Katherine Byaruhanga', 'email' => 'katherine.byaruhanga@student.mak.ac.ug', 'username' => 'katherineb'],
            ['name' => 'Lawrence Ssekitto', 'email' => 'lawrence.ssekitto@student.mak.ac.ug', 'username' => 'lawrences'],
            ['name' => 'Mary Kabahinda', 'email' => 'mary.kabahinda@student.mak.ac.ug', 'username' => 'maryk'],
            ['name' => 'Nathan Wanyama', 'email' => 'nathan.wanyama@student.mak.ac.ug', 'username' => 'nathanw'],
            ['name' => 'Olivia Nakato', 'email' => 'olivia.nakato@student.mak.ac.ug', 'username' => 'olivian']
        ];

        $createdStudents = [];
        foreach ($students as $studentData) {
            $student = User::firstOrCreate(
                ['email' => $studentData['email']],
                array_merge($studentData, [
                    'password' => bcrypt('password'),
                    'role' => 'student'
                ])
            );
            $createdStudents[] = $student;
        }

        // Get all lecturers
        $allLecturers = User::where('role', 'lecturer')->get();

        // Create more course units
        $courses = [
            [
                'name' => 'Database Systems',
                'code' => 'CSC301',
                'lecturer_id' => $allLecturers->where('email', 'michael.chen@mak.ac.ug')->first()->id ?? $allLecturers->first()->id,
                'description' => 'Advanced database design and management'
            ],
            [
                'name' => 'Web Development',
                'code' => 'CSC302',
                'lecturer_id' => $allLecturers->where('email', 'sarah.johnson@mak.ac.ug')->first()->id ?? $allLecturers->first()->id,
                'description' => 'Modern web development technologies'
            ],
            [
                'name' => 'Software Engineering',
                'code' => 'CSC303',
                'lecturer_id' => $allLecturers->where('email', 'grace.nakato@mak.ac.ug')->first()->id ?? $allLecturers->first()->id,
                'description' => 'Software development methodologies and practices'
            ],
            [
                'name' => 'Computer Networks',
                'code' => 'CSC304',
                'lecturer_id' => $allLecturers->where('email', 'lecturer@mak.ac.ug')->first()->id ?? $allLecturers->first()->id,
                'description' => 'Network architecture and protocols'
            ],
            [
                'name' => 'Artificial Intelligence',
                'code' => 'CSC401',
                'lecturer_id' => $allLecturers->where('email', 'michael.chen@mak.ac.ug')->first()->id ?? $allLecturers->first()->id,
                'description' => 'AI concepts and machine learning'
            ]
        ];

        foreach ($courses as $courseData) {
            CourseUnit::firstOrCreate(
                ['code' => $courseData['code']],
                $courseData
            );
        }

        // Get all courses
        $allCourses = CourseUnit::all();

        // Create timetables for new courses
        $timetables = [
            [
                'course_unit_id' => $allCourses->where('code', 'CSC301')->first()->id,
                'day' => 'Wednesday',
                'start_time' => '09:00',
                'end_time' => '11:00',
                'room' => 'Room 301',
                'type' => 'day',
                'attendance_code' => 'DAY301'
            ],
            [
                'course_unit_id' => $allCourses->where('code', 'CSC302')->first()->id,
                'day' => 'Thursday',
                'start_time' => '10:00',
                'end_time' => '12:00',
                'room' => 'Room 302',
                'type' => 'day',
                'attendance_code' => 'DAY302'
            ],
            [
                'course_unit_id' => $allCourses->where('code', 'CSC303')->first()->id,
                'day' => 'Friday',
                'start_time' => '14:00',
                'end_time' => '16:00',
                'room' => 'Room 303',
                'type' => 'day',
                'attendance_code' => 'DAY303'
            ],
            [
                'course_unit_id' => $allCourses->where('code', 'CSC304')->first()->id,
                'day' => 'Wednesday',
                'start_time' => '16:00',
                'end_time' => '18:00',
                'room' => 'Room 304',
                'type' => 'evening',
                'attendance_code' => 'EVE304'
            ],
            [
                'course_unit_id' => $allCourses->where('code', 'CSC401')->first()->id,
                'day' => 'Thursday',
                'start_time' => '15:00',
                'end_time' => '17:00',
                'room' => 'Room 401',
                'type' => 'day',
                'attendance_code' => 'DAY401'
            ]
        ];

        foreach ($timetables as $timetableData) {
            Timetable::firstOrCreate(
                [
                    'course_unit_id' => $timetableData['course_unit_id'],
                    'day' => $timetableData['day'],
                    'start_time' => $timetableData['start_time']
                ],
                $timetableData
            );
        }

        // Create enrollments (many-to-many relationship between students and courses)
        // For simplicity, we'll enroll students in random courses
        $allTimetables = Timetable::all();

        foreach ($createdStudents as $student) {
            // Enroll each student in 2-4 random courses
            $randomCourses = $allCourses->random(rand(2, 4));

            foreach ($randomCourses as $course) {
                // Find timetables for this course
                $courseTimetables = $allTimetables->where('course_unit_id', $course->id);

                // Create attendance records for the last 30 days
                for ($i = 0; $i < 30; $i++) {
                    $date = Carbon::now()->subDays($i);

                    foreach ($courseTimetables as $timetable) {
                        // Skip if the day doesn't match
                        if (strtolower($date->format('l')) !== strtolower($timetable->day)) {
                            continue;
                        }

                        // Random attendance (80% present rate)
                        $isPresent = rand(1, 100) <= 80;

                        Attendance::firstOrCreate(
                            [
                                'user_id' => $student->id,
                                'timetable_id' => $timetable->id,
                                'date' => $date->toDateString()
                            ],
                            [
                                'status' => $isPresent ? 'present' : 'absent',
                                'marked_at' => $isPresent ? $date->copy()->setTimeFromTimeString($timetable->start_time) : null,
                                'verification_method' => $isPresent ? 'code' : null
                            ]
                        );
                    }
                }
            }
        }

        // Create some notifications
        $sampleNotifications = [
            [
                'user_id' => $createdStudents[0]->id,
                'timetable_id' => $allTimetables->first()->id,
                'type' => 'class_cancelled',
                'message' => 'CSC101 class on Monday has been cancelled due to lecturer unavailability.',
                'is_read' => false
            ],
            [
                'user_id' => $createdStudents[1]->id,
                'timetable_id' => $allTimetables->skip(1)->first()->id,
                'type' => 'attendance_reminder',
                'message' => 'Don\'t forget to mark your attendance for CSC201 class today.',
                'is_read' => false
            ]
        ];

        foreach ($sampleNotifications as $notificationData) {
            ClassNotification::create($notificationData);
        }

        $this->command->info('Comprehensive academic data seeded successfully!');
        $this->command->info('Created:');
        $this->command->info('- ' . count($lecturers) . ' additional lecturers');
        $this->command->info('- ' . count($students) . ' students');
        $this->command->info('- ' . count($courses) . ' additional courses');
        $this->command->info('- ' . count($timetables) . ' timetables');
        $this->command->info('- Student enrollments and attendance records');
        $this->command->info('- Sample notifications');
    }
}
