<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update the enum to include 'video' and 'live' types
        DB::statement("ALTER TABLE posts MODIFY COLUMN type ENUM('post', 'reel', 'video', 'live') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the enum back to original values
        DB::statement("ALTER TABLE posts MODIFY COLUMN type ENUM('post', 'reel') NOT NULL");
    }
};
