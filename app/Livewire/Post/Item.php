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

    public function mount()
    {
        // Ensure poll relationship is loaded
        if ($this->post->poll) {
            $this->post->load('poll.options');
        }
    }

    public function repost()
    {
        abort_unless(auth()->check(), 401);

        $orig = $this->post;
        $user = auth()->user();

        // Build a safe, simple repost description referencing the original
        $originalAuthor = optional($orig->user)->username ?? 'user';
        $originalDesc = trim((string) ($orig->description ?? ''));
        $link = route('post', $orig->id);

        $newDescription = "Repost from @{$originalAuthor}:\n\n" . $originalDesc . "\n\n(View original: {$link})";

        // Create a lightweight repost without duplicating media (safe default)
        // If you later want to duplicate media, copy Media rows to the new Post here.
        $new = Post::create([
            'user_id' => $user->id,
            'description' => $newDescription,
            'allow_commenting' => $orig->allow_commenting,
            'hide_like_view' => $orig->hide_like_view,
        ]);

        // Optionally inform the frontend; ensure you have a listener if you want a toast
        $this->dispatch('reposted', postId: $new->id);
    }


    function togglePostLike()  {

        abort_unless(auth()->check(),401);

        $wasLiked = $this->post->isLikedBy(auth()->user());
        auth()->user()->toggleLike($this->post);

        #send notifcation is post is liked
        if ($this->post->isLikedBy(auth()->user()) && !$wasLiked) {
            // User just liked the post
            if ($this->post->user_id != auth()->id()) {
                $this->post->user->notify(new PostLikedNotification(auth()->user(),$this->post));

                // Award points to post author for receiving a like
                $this->post->user->awardPoints(2, 'like_received', 'Received a like on your post');
            }

            // Award points to liker for engaging
            auth()->user()->awardPoints(1, 'like_given', 'Liked a post');
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

    // Voting on poll options
    public function voteOnPollOption($optionId)
    {
        abort_unless(auth()->check(), 401);

        $option = \App\Models\PollOption::findOrFail($optionId);

        // Ensure the option belongs to this post's poll
        if (!$this->post->poll || $option->poll_id !== $this->post->poll->id) {
            abort(403, 'Invalid poll option');
        }

        // Check if poll is expired
        if ($this->post->poll->isExpired()) {
            abort(403, 'Poll has expired');
        }

        // Check if user already voted (for single choice polls)
        if (!$this->post->poll->multiple_choice && $this->post->poll->hasUserVoted(auth()->id())) {
            abort(403, 'You have already voted on this poll');
        }

        // Vote on the option
        if ($option->vote(auth()->id())) {
            // Refresh post relationships to update counts in UI
            $this->post->refresh();
            $this->post->load('poll.options');
        }
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

            // Award points to post author for receiving a comment
            $this->post->user->awardPoints(3, 'comment_received', 'Received a comment on your post');
        }

        // Award points to commenter for engaging
        auth()->user()->awardPoints(2, 'comment_given', 'Left a comment on a post');


    }


    public function render()
    {
        return view('livewire.post.item');
    }
}
