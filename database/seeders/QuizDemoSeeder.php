<?php

namespace Database\Seeders;

use App\Models\CourseUnit;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Timetable;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class QuizDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Create lecturer
        $lect = User::firstOrCreate([
            'email' => 'lecturer@example.com',
        ], [
            'name' => 'Dr. Kahoot',
            'password' => Hash::make('password'),
            'role' => 'lecturer',
        ]);

        // Create students
        $students = collect(range(1, 8))->map(function ($i) {
            return User::firstOrCreate([
                'email' => "student{$i}@example.com",
            ], [
                'name' => "Student {$i}",
                'password' => Hash::make('password'),
                'role' => 'student',
            ]);
        });

        // Course unit
        $cu = CourseUnit::firstOrCreate([
            'code' => 'CSK101',
        ], [
            'name' => 'Cognitive Skills',
            'lecturer_id' => $lect->id,
            'description' => 'Improving memory and retention',
        ]);

        // Timetable session (optional)
        $tt = Timetable::firstOrCreate([
            'course_unit_id' => $cu->id,
            'day' => 'Monday',
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
        ], []);

        // Quiz
        $quiz = Quiz::firstOrCreate([
            'title' => 'Retention Check 1',
            'course_unit_id' => $cu->id,
        ], [
            'lecturer_id' => $lect->id,
            'description' => 'Lecture recap quiz',
            'timetable_id' => $tt->id,
            'status' => 'published',
            'is_active' => true,
            'total_questions' => 10,
            'time_limit' => 300,
            'max_attempts' => 3,
        ]);

        if ($quiz->questions()->count() === 0) {
            for ($i = 1; $i <= 10; $i++) {
                $options = [
                    'A' => "Option A{$i}",
                    'B' => "Option B{$i}",
                    'C' => "Option C{$i}",
                    'D' => "Option D{$i}",
                ];
                $correct = array_rand($options);
                Question::create([
                    'quiz_id' => $quiz->id,
                    'question_text' => "Q{$i}: Which is correct for concept {$i}?",
                    'question_type' => 'multiple_choice',
                    'options' => $options,
                    'correct_answer' => $correct,
                    'explanation' => 'Explanation text',
                    'points' => 10,
                    'time_limit' => 30,
                    'order' => $i,
                    'is_active' => true,
                ]);
            }
        }

        // Generate attempts with random performance
        $maxScore = $quiz->questions()->sum('points');
        foreach ($students as $idx => $stu) {
            $attempt = QuizAttempt::updateOrCreate([
                'quiz_id' => $quiz->id,
                'user_id' => $stu->id,
                'attempt_number' => 1,
            ], [
                'status' => 'completed',
                'current_question' => 10,
                'total_questions' => 10,
                'correct_answers' => rand(2, 10),
                'total_score' => rand(20, $maxScore),
                'max_possible_score' => $maxScore,
                'time_taken' => rand(120, 500),
                'started_at' => now()->subMinutes(rand(30, 60)),
                'completed_at' => now()->subMinutes(rand(1, 29)),
                'answers' => [],
                'question_times' => [],
                'ip_address' => '127.0.0.1',
            ]);
            $acc = $attempt->total_questions > 0 ? ($attempt->correct_answers / $attempt->total_questions) : 0;
            $attempt->feedback = $acc >= 0.7 ? 'Strong retention' : ($acc >= 0.4 ? 'Average retention' : 'Low retention');
            $attempt->save();

            // Mark attendance for half the students as present to demo risk flagging
            if ($idx % 2 === 0) {
                Attendance::updateOrCreate([
                    'user_id' => $stu->id,
                    'timetable_id' => $tt->id,
                    'date' => today(),
                ], [
                    'status' => 'present',
                    'marked_at' => now()->subMinutes(rand(40, 90)),
                    'verification_method' => 'seeded',
                    'security_data' => ['seed' => true],
                ]);
            }
        }
    }
}
