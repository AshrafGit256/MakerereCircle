<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Overtrue\LaravelLike\Traits\Likeable;

class Media extends Model
{
    use HasFactory;
    use Likeable;

    protected $guarded=[];

    function mediable() : MorphTo {
        
        return $this->morphTo();
    }
}
