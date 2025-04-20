<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $fillable = [
        'post_id', 'name', 'email', 'description', 'location',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}

