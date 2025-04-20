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
        Schema::table('posts', function (Blueprint $table) {
            $table->string('image_name')->after('user_id');
            $table->tinyInteger('status')->default(0)->after('type')->comment('0 = active, 1 = inactive');
            $table->tinyInteger('is_delete')->default(0)->after('status')->comment('0 = not deleted, 1 = deleted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['image_name', 'status', 'is_delete']);
        });
    }
};
