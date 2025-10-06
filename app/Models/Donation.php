<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'fundraiser_id',
        'user_id',
        'amount',
        'currency',
        'payment_method',
        'payment_provider',
        'transaction_reference',
        'status',
        'is_anonymous',
        'message',
        'payment_details',
        'completed_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_anonymous' => 'boolean',
        'payment_details' => 'array',
        'completed_at' => 'datetime'
    ];

    // Relationships
    public function fundraiser(): BelongsTo
    {
        return $this->belongsTo(Fundraiser::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    public function getDonorName()
    {
        if ($this->is_anonymous) {
            return 'Anonymous';
        }

        return $this->user->name;
    }

    public function getFormattedAmount()
    {
        return number_format($this->amount, 0) . ' ' . $this->currency;
    }

    public function getPaymentMethodName()
    {
        $methods = [
            'mtn_mobile_money' => 'MTN Mobile Money',
            'airtel_money' => 'Airtel Money',
            'centenary_bank' => 'Centenary Bank',
            'stanbic_bank' => 'Stanbic Bank',
            'bank_transfer' => 'Bank Transfer',
        ];

        return $methods[$this->payment_method] ?? ucfirst(str_replace('_', ' ', $this->payment_method));
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeAnonymous($query)
    {
        return $query->where('is_anonymous', true);
    }

    public function scopePublic($query)
    {
        return $query->where('is_anonymous', false);
    }
}
