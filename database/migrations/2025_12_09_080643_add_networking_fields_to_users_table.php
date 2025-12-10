<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Basic profile fields
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('title');
            }

            if (!Schema::hasColumn('users', 'course')) {
                $table->string('course')->nullable()->after('bio');
            }

            if (!Schema::hasColumn('users', 'education_level')) {
                $table->string('education_level')->nullable()->after('course');
            }

            if (!Schema::hasColumn('users', 'employment_status')) {
                $table->string('employment_status')->nullable()->after('education_level');
            }

            if (!Schema::hasColumn('users', 'location')) {
                $table->string('location')->nullable()->after('employment_status');
            }

            if (!Schema::hasColumn('users', 'skills')) {
                $table->text('skills')->nullable()->after('location');
            }

            if (!Schema::hasColumn('users', 'schools')) {
                $table->text('schools')->nullable()->after('skills');
            }

            if (!Schema::hasColumn('users', 'talents')) {
                $table->text('talents')->nullable()->after('schools');
            }

            // New networking fields
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role', ['student', 'lecturer', 'staff', 'alumni', 'industry_partner', 'other'])
                    ->default('student')
                    ->after('talents');
            }

            if (!Schema::hasColumn('users', 'gender')) {
                $table->string('gender', ['male', 'female', 'other'])->nullable()->after('role');
            }

            if (!Schema::hasColumn('users', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('gender');
            }

            if (!Schema::hasColumn('users', 'year_of_study')) {
                $table->string('year_of_study')->nullable()->after('date_of_birth');
            }

            if (!Schema::hasColumn('users', 'semester')) {
                $table->string('semester')->nullable()->after('year_of_study');
            }

            if (!Schema::hasColumn('users', 'looking_for')) {
                $table->text('looking_for')->nullable()->after('semester');
            }

            if (!Schema::hasColumn('users', 'interests')) {
                $table->text('interests')->nullable()->after('looking_for');
            }

            if (!Schema::hasColumn('users', 'region')) {
                $table->string('region')->nullable()->after('interests');
            }

            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('region');
            }

            if (!Schema::hasColumn('users', 'social_links')) {
                $table->json('social_links')->nullable()->after('phone');
            }

            if (!Schema::hasColumn('users', 'is_online')) {
                $table->boolean('is_online')->default(false)->after('social_links');
            }

            if (!Schema::hasColumn('users', 'last_seen')) {
                $table->timestamp('last_seen')->nullable()->after('is_online');
            }

            if (!Schema::hasColumn('users', 'connection_count')) {
                $table->integer('connection_count')->default(0)->after('last_seen');
            }

            // Indexes for better performance
            $table->index(['role', 'is_admin', 'is_delete']);
            $table->index(['education_level', 'employment_status']);
            $table->index(['year_of_study', 'course']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove new columns
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
