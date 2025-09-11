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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('course_unit_id')->constrained('course_units')->onDelete('cascade');
            $table->foreignId('lecturer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('timetable_id')->nullable()->constrained('timetables')->onDelete('set null');
            $table->string('lecture_content')->nullable(); // Extracted text from slides/PDF
            $table->string('content_file_path')->nullable(); // Path to uploaded lecture file
            $table->enum('status', ['draft', 'published', 'active', 'completed'])->default('draft');
            $table->integer('total_questions')->default(10);
            $table->integer('time_limit')->default(300); // seconds per question
            $table->integer('max_attempts')->default(1);
            $table->boolean('is_active')->default(false);
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->json('settings')->nullable(); // Additional quiz settings
            $table->timestamps();

            $table->index(['course_unit_id', 'status']);
            $table->index(['lecturer_id', 'status']);
            $table->index('scheduled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
