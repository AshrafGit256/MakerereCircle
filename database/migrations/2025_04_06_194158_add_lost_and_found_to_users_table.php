<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLostAndFoundToUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->boolean('lost')->default(0)->after('allow_commenting'); // Adding lost as a boolean after allow_commenting
            $table->boolean('found')->default(0)->after('lost'); // Adding found as a boolean after lost
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('lost'); // Removing the lost column if the migration is rolled back
            $table->dropColumn('found'); // Removing the found column if the migration is rolled back
        });
    }
}
