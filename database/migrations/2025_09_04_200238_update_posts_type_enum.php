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
        // Update the STRING to include 'video' and 'live' types
        DB::statement("ALTER TABLE posts MODIFY COLUMN type STRING('post', 'reel', 'video', 'live') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the STRING back to original values
        DB::statement("ALTER TABLE posts MODIFY COLUMN type STRING('post', 'reel') NOT NULL");
    }
};
