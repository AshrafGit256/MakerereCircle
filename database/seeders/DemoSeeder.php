<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Create some users
        $user1 = User::firstOrCreate(
            ['email' => 'testuser@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'gender' => 'male',
            ]
        );

        $user2 = User::firstOrCreate(
            ['email' => 'alice@example.com'],
            [
                'name' => 'Alice Student',
                'password' => Hash::make('alice123'),
                'role' => 'student',
                'gender' => 'female',
            ]
        );

        $user3 = User::firstOrCreate(
            ['email' => 'bob@example.com'],
            [
                'name' => 'Bob Lecturer',
                'password' => Hash::make('bob123'),
                'role' => 'lecturer',
                'gender' => 'male',
            ]
        );

        // Create some posts for testuser
        Post::firstOrCreate([
            'user_id' => $user1->id,
            'description' => 'Hello world! My first post.',
            'type' => 'post',
        ]);

        Post::firstOrCreate([
            'user_id' => $user1->id,
            'description' => 'Check out my new reel!',
            'type' => 'reel',
        ]);

        // Optional: add more posts for other users if needed
    }
}
