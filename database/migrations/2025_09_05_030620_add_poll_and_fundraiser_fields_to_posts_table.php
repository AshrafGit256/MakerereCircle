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
            // Poll fields
            $table->string('poll_question')->nullable();
            $table->json('poll_options')->nullable();
            $table->boolean('poll_multiple_choice')->default(false);
            $table->integer('poll_duration_hours')->default(24);

            // Fundraiser fields
            $table->boolean('is_fundraiser')->default(false);
            $table->string('fundraiser_title')->nullable();
            $table->text('fundraiser_description')->nullable();
            $table->decimal('fundraiser_target_amount', 10, 2)->nullable();
            $table->string('fundraiser_category')->nullable();
            $table->date('fundraiser_end_date')->nullable();
            $table->string('fundraiser_beneficiary_name')->nullable();
            $table->text('fundraiser_beneficiary_story')->nullable();
            $table->string('fundraiser_contact_phone')->nullable();
            $table->string('fundraiser_contact_email')->nullable();

            // Update type enum to include poll, fundraiser, and video
            $table->enum('type', ['post', 'reel', 'poll', 'fundraiser', 'video'])->default('post')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Drop poll fields
            $table->dropColumn([
                'poll_question',
                'poll_options',
                'poll_multiple_choice',
                'poll_duration_hours',
                'is_fundraiser',
                'fundraiser_title',
                'fundraiser_description',
                'fundraiser_target_amount',
                'fundraiser_category',
                'fundraiser_end_date',
                'fundraiser_beneficiary_name',
                'fundraiser_beneficiary_story',
                'fundraiser_contact_phone',
                'fundraiser_contact_email'
            ]);

            // Revert type enum
            $table->enum('type', ['post', 'reel', 'video'])->default('post')->change();
        });
    }
};
