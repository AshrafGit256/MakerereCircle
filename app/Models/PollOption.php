<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PollOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'poll_id',
        'option_text',
        'votes_count'
    ];

    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(PollVote::class);
    }

    public function hasUserVoted($userId)
    {
        return $this->votes()->where('user_id', $userId)->exists();
    }

    public function vote($userId)
    {
        if (!$this->hasUserVoted($userId)) {
            $this->votes()->create(['user_id' => $userId]);
            $this->increment('votes_count');
            return true;
        }
        return false;
    }

    public function unvote($userId)
    {
        $vote = $this->votes()->where('user_id', $userId)->first();
        if ($vote) {
            $vote->delete();
            $this->decrement('votes_count');
            return true;
        }
        return false;
    }
}
