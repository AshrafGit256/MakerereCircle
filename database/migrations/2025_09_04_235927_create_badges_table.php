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
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description');
            $table->string('icon')->nullable();
            $table->string('color', 7)->default('#3B82F6'); // Hex color
            $table->string('criteria_type'); // posts_count, points_total, etc.
            $table->integer('criteria_value');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert default badges
        DB::table('badges')->insert([
            [
                'name' => 'First Post',
                'slug' => 'first_post',
                'description' => 'Created your first post',
                'icon' => '📝',
                'color' => '#10B981',
                'criteria_type' => 'posts_count',
                'criteria_value' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Content Creator',
                'slug' => 'content_creator',
                'description' => 'Created 10 posts',
                'icon' => '🎨',
                'color' => '#F59E0B',
                'criteria_type' => 'posts_count',
                'criteria_value' => 10,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Poll Master',
                'slug' => 'poll_master',
                'description' => 'Created 5 polls',
                'icon' => '📊',
                'color' => '#8B5CF6',
                'criteria_type' => 'polls_count',
                'criteria_value' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Social Butterfly',
                'slug' => 'social_butterfly',
                'description' => 'Made 50 comments',
                'icon' => '🦋',
                'color' => '#EC4899',
                'criteria_type' => 'comments_count',
                'criteria_value' => 50,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Point Collector',
                'slug' => 'point_collector',
                'description' => 'Earned 1000 points',
                'icon' => '⭐',
                'color' => '#F59E0B',
                'criteria_type' => 'points_total',
                'criteria_value' => 1000,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Streak Master',
                'slug' => 'streak_master',
                'description' => 'Maintained a 30-day streak',
                'icon' => '🔥',
                'color' => '#EF4444',
                'criteria_type' => 'streak_days',
                'criteria_value' => 30,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};
