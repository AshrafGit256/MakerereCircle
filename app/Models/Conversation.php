<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'college_id',
    ];


    function messages() : HasMany {

        return $this->hasMany(Message::class);
        
    }

    function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }

    public function scopeCollege($query, $collegeId)
    {
        return $query->where('college_id', $collegeId);
    }


    function getReceiver()  {

        if($this->sender_id===auth()->id()){

            return User::firstWhere('id',$this->receiver_id);
        }
        else{
            return User::firstWhere('id',$this->sender_id);

        }
        
    }

}
