<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'provider_code',
        'logo_url',
        'is_active',
        'config',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'config' => 'array'
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // Helper methods
    public function isMobileMoney()
    {
        return $this->type === 'mobile_money';
    }

    public function isBankTransfer()
    {
        return $this->type === 'bank_transfer';
    }

    public function isCard()
    {
        return $this->type === 'card';
    }

    public function getConfigValue($key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }

    // Static methods for common payment methods
    public static function getMobileMoneyMethods()
    {
        return self::active()->byType('mobile_money')->ordered()->get();
    }

    public static function getBankMethods()
    {
        return self::active()->byType('bank_transfer')->ordered()->get();
    }

    public static function getCardMethods()
    {
        return self::active()->byType('card')->ordered()->get();
    }

    public static function getAllActiveMethods()
    {
        return self::active()->ordered()->get();
    }
}
