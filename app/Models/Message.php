<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'conversation_id',
        'sender_id',
        'receiver_id',
        'read_at',
        'college_id',
    ];

    protected $dates=['read_at'];


    function conversation()  {

        return $this->belongsTo(Conversation::class);
        
    }

    function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }

    public function scopeCollege($query, $collegeId)
    {
        return $query->where('college_id', $collegeId);
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    function isRead()  {

        return $this->read_at!=null;
        
    }

}
