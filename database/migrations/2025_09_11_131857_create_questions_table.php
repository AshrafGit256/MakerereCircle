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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->text('question_text');
            $table->enum('question_type', ['multiple_choice', 'true_false', 'short_answer'])->default('multiple_choice');
            $table->json('options'); // For multiple choice options
            $table->string('correct_answer'); // The correct answer
            $table->text('explanation')->nullable(); // Explanation for the correct answer
            $table->integer('points')->default(10); // Points for this question
            $table->integer('time_limit')->default(30); // Time limit in seconds
            $table->integer('order')->default(0); // Question order in quiz
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable(); // Additional AI-generated metadata
            $table->timestamps();

            $table->index(['quiz_id', 'order']);
            $table->index(['quiz_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
