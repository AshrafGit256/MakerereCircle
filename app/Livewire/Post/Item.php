<?php

namespace App\Livewire\Post;

use App\Models\Comment;
use App\Models\Media;
use App\Models\Post;
use App\Notifications\NewCommentNotification;
use App\Notifications\PostLikedNotification;
use Livewire\Component;

class Item extends Component
{

    public $post;

    public $body;


    function togglePostLike()  {

        abort_unless(auth()->check(),401);

        auth()->user()->toggleLike($this->post);     
        
        #send notifcation is post is liked 

        if ($this->post->isLikedBy(auth()->user())) {
            if ($this->post->user_id != auth()->id()) {
            $this->post->user->notify(new PostLikedNotification(auth()->user(),$this->post));
            }
        }
    }


    function toggleFavorite()  {

        abort_unless(auth()->check(),401);
        auth()->user()->toggleFavorite($this->post);        
    }

    function toggleCommentLike(Comment $comment)  {

        abort_unless(auth()->check(),401);

        auth()->user()->toggleLike($comment);        
    }

    // Voting on media (image options)
    public function voteOnMedia($mediaId)
    {
        abort_unless(auth()->check(), 401);
        /** @var Media $media */
        $media = Media::findOrFail($mediaId);

        // Ensure the media belongs to this post and is an image option
        if ($media->mediable_type !== Post::class || $media->mediable_id !== $this->post->id || $media->mime !== 'image') {
            abort(403, 'Invalid vote target');
        }

        // Enforce single choice per user: remove likes from other images of this post
        foreach ($this->post->media()->where('mime', 'image')->where('id', '!=', $media->id)->get() as $other) {
            if (auth()->user()->hasLiked($other)) {
                auth()->user()->toggleLike($other);
            }
        }

        // Toggle selected media like (vote)
        auth()->user()->toggleLike($media);

        // Refresh post relationships to update counts in UI
        $this->post->refresh();
    }

    public function mediaVotes(Media $media): int
    {
        return $media->likers()->count();
    }


    function addComment()  {

        $this->validate(['body'=>'required']);

        #create comment 
       $comment= Comment::create([
            'body'=>$this->body,
            'commentable_id'=>$this->post->id,
            'commentable_type'=>Post::class,
            'user_id'=>auth()->id(),

        ]);

        $this->reset('body');

        #notify user 

        if ($this->post->user_id != auth()->id()) {
            $this->post->user->notify(new NewCommentNotification(auth()->user(),$comment));

        }

        
    }


    public function render()
    {
        return view('livewire.post.item');
    }
}
