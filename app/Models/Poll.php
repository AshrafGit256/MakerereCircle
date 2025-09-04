<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poll extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'question',
        'multiple_choice',
        'ends_at'
    ];

    protected $casts = [
        'multiple_choice' => 'boolean',
        'ends_at' => 'datetime'
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(PollOption::class);
    }

    public function votes()
    {
        return $this->hasManyThrough(PollVote::class, PollOption::class);
    }

    public function getVotesAttribute()
    {
        return $this->options()->with('votes')->get()->pluck('votes')->flatten();
    }

    public function hasUserVoted($userId)
    {
        return $this->votes()->where('user_id', $userId)->exists();
    }

    public function getTotalVotesAttribute()
    {
        return $this->options()->sum('votes_count');
    }

    public function isExpired()
    {
        return $this->ends_at && now()->isAfter($this->ends_at);
    }
}
