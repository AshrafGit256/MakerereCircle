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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fundraiser_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('UGX');
            $table->string('payment_method'); // mobile_money, bank_transfer, card
            $table->string('payment_provider')->nullable(); // mtn, airtel, centenary, etc.
            $table->string('transaction_reference')->unique();
            $table->string('status')->default('pending'); // pending, completed, failed, refunded
            $table->boolean('is_anonymous')->default(false);
            $table->text('message')->nullable(); // Optional message from donor
            $table->json('payment_details')->nullable(); // Store payment gateway response
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['fundraiser_id', 'status']);
            $table->index(['user_id', 'created_at']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
