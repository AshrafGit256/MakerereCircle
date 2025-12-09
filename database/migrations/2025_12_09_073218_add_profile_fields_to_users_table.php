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
            // Add all missing columns
            $table->string('course')->nullable()->after('bio');
            $table->string('education_level')->nullable()->after('course');
            $table->string('employment_status')->nullable()->after('education_level');
            $table->string('location')->nullable()->after('employment_status');
            $table->text('skills')->nullable()->after('location');
            $table->text('schools')->nullable()->after('skills');
            $table->text('talents')->nullable()->after('schools');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'course',
                'education_level',
                'employment_status',
                'location',
                'skills',
                'schools',
                'talents'
            ]);
        });
    }
};