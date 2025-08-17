<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');              // Event name
            $table->text('description')->nullable(); // Event description
            $table->date('date');                 // Event date
            $table->time('time')->nullable();     // Optional time
            $table->string('location')->nullable(); // Optional location
            $table->unsignedBigInteger('user_id')->nullable(); // Creator
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
