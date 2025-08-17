<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('groups', function (Blueprint $table) {
            // Only add if it doesn’t already exist
            if (!Schema::hasColumn('groups', 'image')) {
                $table->string('image')->nullable()->after('description');
            }
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};