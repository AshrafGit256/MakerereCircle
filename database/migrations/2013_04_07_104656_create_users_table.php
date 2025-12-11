<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'bio' => 'text',
                'course' => 'string',
                'education_level' => 'string',
                'employment_status' => 'string',
                'location' => 'string',
                'skills' => 'text',
                'schools' => 'text',
                'talents' => 'text',
                'role' => 'string',
                'gender' => 'string',
                'date_of_birth' => 'date',
                'year_of_study' => 'string',
                'semester' => 'string',
                'looking_for' => 'text',
                'interests' => 'text',
                'region' => 'string',
                'phone' => 'string',
                'social_links' => 'json',
                'is_online' => 'boolean',
                'last_seen' => 'timestamp',
                'connection_count' => 'integer',
            ];

            foreach ($columns as $column => $type) {
                if (!Schema::hasColumn('users', $column)) {
                    match ($type) {
                        'string' => $table->string($column)->nullable(),
                        'text' => $table->text($column)->nullable(),
                        'json' => $table->json($column)->nullable(),
                        'boolean' => $table->boolean($column)->default(false),
                        'timestamp' => $table->timestamp($column)->nullable(),
                        'integer' => $table->integer($column)->default(0),
                        'date' => $table->date($column)->nullable(),
                        default => null
                    };
                }
            }

            // Add indexes
            $table->index(['role', 'is_online']);
            $table->index(['education_level', 'employment_status']);
            $table->index(['year_of_study', 'course']);
        });

        // ✅ Add default user directly after creating columns
        if (Schema::hasTable('users')) {
            User::firstOrCreate(
                ['email' => 'testuser@example.com'],
                [
                    'name' => 'Test User',
                    'password' => Hash::make('password123'),
                    'role' => 'student',
                    'gender' => 'male',
                ]
            );
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = [
                'bio',
                'course',
                'education_level',
                'employment_status',
                'location',
                'skills',
                'schools',
                'talents',
                'role',
                'gender',
                'date_of_birth',
                'year_of_study',
                'semester',
                'looking_for',
                'interests',
                'region',
                'phone',
                'social_links',
                'is_online',
                'last_seen',
                'connection_count'
            ];

            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
