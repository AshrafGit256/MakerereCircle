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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // MTN Mobile Money, Airtel Money, etc.
            $table->string('slug')->unique(); // mtn_mobile_money, airtel_money, etc.
            $table->string('type'); // mobile_money, bank_transfer, card
            $table->string('provider_code')->nullable(); // API codes for integrations
            $table->string('logo_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('config')->nullable(); // Store API keys, endpoints, etc.
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['type', 'is_active']);
        });

        // Insert Uganda payment methods
        DB::table('payment_methods')->insert([
            [
                'name' => 'MTN Mobile Money',
                'slug' => 'mtn_mobile_money',
                'type' => 'mobile_money',
                'provider_code' => 'MTN',
                'logo_url' => '/images/payments/mtn.png',
                'is_active' => true,
                'config' => json_encode(['country_code' => '256', 'prefixes' => ['077', '078']]),
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Airtel Money',
                'slug' => 'airtel_money',
                'type' => 'mobile_money',
                'provider_code' => 'AIRTEL',
                'logo_url' => '/images/payments/airtel.png',
                'is_active' => true,
                'config' => json_encode(['country_code' => '256', 'prefixes' => ['075', '070']]),
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Centenary Bank',
                'slug' => 'centenary_bank',
                'type' => 'bank_transfer',
                'provider_code' => 'CENTENARY',
                'logo_url' => '/images/payments/centenary.png',
                'is_active' => true,
                'config' => json_encode(['bank_code' => '31']),
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Stanbic Bank',
                'slug' => 'stanbic_bank',
                'type' => 'bank_transfer',
                'provider_code' => 'STANBIC',
                'logo_url' => '/images/payments/stanbic.png',
                'is_active' => true,
                'config' => json_encode(['bank_code' => '22']),
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bank Transfer',
                'slug' => 'bank_transfer',
                'type' => 'bank_transfer',
                'provider_code' => 'BANK_TRANSFER',
                'logo_url' => '/images/payments/bank.png',
                'is_active' => true,
                'config' => null,
                'sort_order' => 5,
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
        Schema::dropIfExists('payment_methods');
    }
};
