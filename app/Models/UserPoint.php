<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'points',
        'total_points',
        'action_type',
        'description',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Static method to award points
    public static function awardPoints($userId, $points, $actionType, $description, $metadata = null)
    {
        $user = User::find($userId);
        if (!$user) return null;

        // Calculate new total
        $currentTotal = self::where('user_id', $userId)->max('total_points') ?? 0;
        $newTotal = $currentTotal + $points;

        return self::create([
            'user_id' => $userId,
            'points' => $points,
            'total_points' => $newTotal,
            'action_type' => $actionType,
            'description' => $description,
            'metadata' => $metadata
        ]);
    }

    // Get user's total points
    public static function getUserTotalPoints($userId)
    {
        return self::where('user_id', $userId)->max('total_points') ?? 0;
    }
}
