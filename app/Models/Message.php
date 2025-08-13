<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Message extends Model
{
    use HasFactory;

    protected $guarded=[];

    protected $dates=['read_at'];


    function conversation()  {

        return $this->belongsTo(Conversation::class);
        
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    function isRead()  {

        return $this->read_at!=null;
        
    }

}
