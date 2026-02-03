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
        // Skip this migration for PostgreSQL - enum values already exist or will be created correctly
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE posts MODIFY COLUMN type ENUM('post', 'reel', 'video', 'live') NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op for PostgreSQL
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE posts MODIFY COLUMN type ENUM('post', 'reel') NOT NULL");
        }
    }
};
