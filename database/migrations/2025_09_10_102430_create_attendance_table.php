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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('timetable_id')->constrained('timetables')->onDelete('cascade');
            $table->date('date');
            $table->enum('status', ['present', 'absent', 'late'])->default('present');
            $table->timestamp('marked_at')->nullable();
            $table->string('verification_method')->nullable(); // e.g., 'qr', 'code', 'manual', 'multi-factor-secure'
            $table->string('verification_token')->nullable(); // Unique token for verification
            $table->json('security_data')->nullable(); // Store comprehensive security information
            $table->timestamps();
            $table->unique(['user_id', 'timetable_id', 'date']); // Prevent duplicate attendance per class per day

            // Indexes for performance
            $table->index(['user_id', 'date']);
            $table->index(['timetable_id', 'date']);
            $table->index(['verification_token']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
