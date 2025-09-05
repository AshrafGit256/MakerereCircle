<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Overtrue\LaravelLike\Traits\Likeable;
use Overtrue\LaravelFavorite\Traits\Favoriteable;

class Post extends Model
{
    use HasFactory;
    use Likeable;
    use Favoriteable;
    protected $guarded = [];

    protected $fillable = [
        'user_id',
        'description',
        'location',
        'hide_like_view',
        'allow_commenting',
        'type',
        'video_url',
        'lost',
        'found',
        'poll_question',
        'poll_options',
        'poll_multiple_choice',
        'poll_duration_hours',
        'is_fundraiser',
        'fundraiser_title',
        'fundraiser_description',
        'fundraiser_target_amount',
        'fundraiser_category',
        'fundraiser_end_date',
        'fundraiser_beneficiary_name',
        'fundraiser_beneficiary_story',
        'fundraiser_contact_phone',
        'fundraiser_contact_email'
    ];


    protected $casts = [

        'hide_like_view' => 'boolean',
        'allow_commenting' => 'boolean',

    ];

    function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }


    function user(): BelongsTo
    {

        return $this->belongsTo(User::class);
    }


    function comments(): MorphMany
    {

        return $this->morphMany(Comment::class, 'commentable')->with('replies');
    }

    function poll()
    {
        return $this->hasOne(Poll::class);
    }


}
