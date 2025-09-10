<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\CourseUnit;
use App\Models\Enrollment;

class CreateEnrollmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all students and courses
        $students = User::where('role', 'student')->get();
        $courses = CourseUnit::all();

        if ($students->isEmpty() || $courses->isEmpty()) {
            $this->command->info('No students or courses found. Please run other seeders first.');
            return;
        }

        $enrollmentsCreated = 0;

        // Enroll each student in 2-4 random courses
        foreach ($students as $student) {
            $coursesToEnroll = $courses->random(rand(2, 4));

            foreach ($coursesToEnroll as $course) {
                // Check if already enrolled
                $existingEnrollment = Enrollment::where('user_id', $student->id)
                    ->where('course_unit_id', $course->id)
                    ->first();

                if (!$existingEnrollment) {
                    Enrollment::create([
                        'user_id' => $student->id,
                        'course_unit_id' => $course->id,
                        'status' => 'active',
                        'enrollment_date' => now()->subDays(rand(0, 30))
                    ]);
                    $enrollmentsCreated++;
                }
            }
        }

        $this->command->info("Created {$enrollmentsCreated} student enrollments!");
        $this->command->info('Students are now enrolled in courses and will receive notifications when classes are cancelled.');
    }
}
