<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TimetableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a demo lecturer
        $lecturer = \App\Models\User::firstOrCreate([
            'email' => 'lecturer@mak.ac.ug'
        ], [
            'name' => 'Dr. John Doe',
            'password' => bcrypt('password'),
            'role' => 'lecturer',
            'username' => 'johndoe',
        ]);

        // Create demo course units
        $course1 = \App\Models\CourseUnit::create([
            'name' => 'Introduction to Computer Science',
            'code' => 'CSC101',
            'lecturer_id' => $lecturer->id,
            'description' => 'Basic concepts of computer science',
        ]);

        $course2 = \App\Models\CourseUnit::create([
            'name' => 'Data Structures and Algorithms',
            'code' => 'CSC201',
            'lecturer_id' => $lecturer->id,
            'description' => 'Advanced data structures and algorithms',
        ]);

        // Create timetables
        \App\Models\Timetable::create([
            'course_unit_id' => $course1->id,
            'day' => 'Monday',
            'start_time' => '09:00',
            'end_time' => '11:00',
            'room' => 'Room 101',
            'type' => 'day',
            'attendance_code' => 'DAY101',
        ]);

        \App\Models\Timetable::create([
            'course_unit_id' => $course1->id,
            'day' => 'Monday',
            'start_time' => '18:00',
            'end_time' => '20:00',
            'room' => 'Room 101',
            'type' => 'evening',
            'attendance_code' => 'EVE101',
        ]);

        \App\Models\Timetable::create([
            'course_unit_id' => $course2->id,
            'day' => 'Tuesday',
            'start_time' => '10:00',
            'end_time' => '12:00',
            'room' => 'Room 201',
            'type' => 'day',
            'attendance_code' => 'DAY201',
        ]);

        \App\Models\Timetable::create([
            'course_unit_id' => $course2->id,
            'day' => 'Tuesday',
            'start_time' => '19:00',
            'end_time' => '21:00',
            'room' => 'Room 201',
            'type' => 'evening',
            'attendance_code' => 'EVE201',
        ]);
    }
}
