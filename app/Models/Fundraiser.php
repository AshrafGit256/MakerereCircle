<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Fundraiser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'title',
        'description',
        'target_amount',
        'current_amount',
        'currency',
        'category',
        'end_date',
        'is_active',
        'is_featured',
        'images',
        'beneficiary_name',
        'beneficiary_story',
        'contact_phone',
        'contact_email'
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'images' => 'array'
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    // Helper methods
    public function getProgressPercentage()
    {
        if ($this->target_amount <= 0) {
            return 0;
        }

        return min(100, round(($this->current_amount / $this->target_amount) * 100, 1));
    }

    public function getRemainingAmount()
    {
        return max(0, $this->target_amount - $this->current_amount);
    }

    public function isCompleted()
    {
        return $this->current_amount >= $this->target_amount;
    }

    public function isExpired()
    {
        return $this->end_date && $this->end_date->isPast();
    }

    public function getDaysRemaining()
    {
        if (!$this->end_date) {
            return null;
        }

        return max(0, Carbon::now()->diffInDays($this->end_date, false));
    }

    public function getTotalDonors()
    {
        return $this->donations()->where('status', 'completed')->distinct('user_id')->count();
    }

    public function getRecentDonations($limit = 5)
    {
        return $this->donations()
            ->with('user')
            ->where('status', 'completed')
            ->where('is_anonymous', false)
            ->orderBy('completed_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function addDonation($userId, $amount, $paymentMethod, $paymentProvider = null, $message = null, $isAnonymous = false)
    {
        // Generate unique transaction reference
        $transactionRef = 'DON_' . time() . '_' . $userId . '_' . $this->id;

        $donation = $this->donations()->create([
            'user_id' => $userId,
            'amount' => $amount,
            'currency' => $this->currency,
            'payment_method' => $paymentMethod,
            'payment_provider' => $paymentProvider,
            'transaction_reference' => $transactionRef,
            'status' => 'pending',
            'message' => $message,
            'is_anonymous' => $isAnonymous,
        ]);

        return $donation;
    }

    public function completeDonation($transactionReference, $paymentDetails = null)
    {
        $donation = $this->donations()->where('transaction_reference', $transactionReference)->first();

        if ($donation && $donation->status === 'pending') {
            $donation->update([
                'status' => 'completed',
                'completed_at' => now(),
                'payment_details' => $paymentDetails
            ]);

            // Update fundraiser current amount
            $this->increment('current_amount', $donation->amount);

            // Award points to donor
            if ($donation->user_id !== $this->user_id) { // Don't award points for self-donations
                $donation->user->awardPoints(5, 'donation_made', 'Made a donation to ' . $this->title);
            }

            return $donation;
        }

        return null;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where(function($q) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>', now());
                    });
    }

    public function scopeCompleted($query)
    {
        return $query->whereRaw('current_amount >= target_amount');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
