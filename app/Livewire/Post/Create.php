<?php

namespace App\Livewire\Post;

use App\Models\Media;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;


class Create extends ModalComponent
{


    use WithFileUploads;

    public $media = [];
    public $description;
    public $location;
    public $video_url;
    public $lost = false;
    public $found = false;
    public $hide_like_view = false;
    public $allow_commenting = true;

    // Poll related properties
    public $has_poll = false;
    public $poll_question = '';
    public $poll_options = ['', ''];
    public $poll_multiple_choice = false;
    public $poll_duration_hours = 24;



    /**
     * Supported: 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'
     */
    public static function modalMaxWidth(): string
    {
        return '4xl';
    }





    function submit()
    {

        #validate

        $this->validate([
            'media.*' => 'nullable|file|mimes:png,jpg,mp4,jpeg,mov|max:100000',
            'video_url' => 'nullable|url',
            'allow_commenting' => 'boolean',
            'hide_like_view' => 'boolean',
            'lost' => 'boolean',
            'found' => 'boolean',
            'has_poll' => 'boolean',
            'poll_question' => 'required_if:has_poll,true|string|max:255',
            'poll_options.*' => 'required_if:has_poll,true|string|max:100',
            'poll_multiple_choice' => 'boolean',
            'poll_duration_hours' => 'nullable|integer|min:1|max:168',
        ]);

        // Ensure at least description, media, video_url, or poll is provided
        if (empty($this->description) && empty($this->media) && empty($this->video_url) && !$this->has_poll) {
            $this->addError('content', 'Please provide a description, upload media, add a video URL, or create a poll.');
            return;
        }

        // Validate poll options
        if ($this->has_poll) {
            $validOptions = array_filter($this->poll_options, function($option) {
                return !empty(trim($option));
            });

            if (count($validOptions) < 2) {
                $this->addError('poll_options', 'Please provide at least 2 poll options.');
                return;
            }
        }


        #determine if real or post

        $type = $this->getPostType($this->media);


        #create post
        $post = Post::create([

            'user_id' => auth()->user()->id,
            'description' => $this->description,
            'location' => $this->location,
            'video_url' => $this->video_url,
            'allow_commenting' => $this->allow_commenting,
            'hide_like_view' => $this->hide_like_view,
            'lost' => $this->lost,
            'found' => $this->found,
            'type' => $type

        ]);

        #add media if any
        if (!empty($this->media)) {
            foreach ($this->media as $key => $media) {

                #get mime type

                $mime = $this->getMime($media);

                #save to storage

                $path = $media->store('media', 'public');

                $url = url(Storage::url($path));


                #create media

                Media::create([

                    'url' => $url,
                    'mime' => $mime,
                    'mediable_id' => $post->id,
                    'mediable_type' => Post::class


                ]);
            }
        }

        #create poll if enabled
        if ($this->has_poll) {
            $poll = \App\Models\Poll::create([
                'post_id' => $post->id,
                'question' => $this->poll_question,
                'multiple_choice' => $this->poll_multiple_choice,
                'ends_at' => $this->poll_duration_hours ? now()->addHours($this->poll_duration_hours) : null,
            ]);

            // Create poll options
            $validOptions = array_filter($this->poll_options, function($option) {
                return !empty(trim($option));
            });

            foreach ($validOptions as $optionText) {
                \App\Models\PollOption::create([
                    'poll_id' => $poll->id,
                    'option_text' => trim($optionText),
                ]);
            }
        }

        $this->reset();
        $this->dispatch('close');

        #dispatch event for post created
        $this->dispatch('post-created', $post->id);
    }


    function getMime($media): string
    {

        if (str()->contains($media->getMimeType(), 'video')) {

            return 'video';
        } else {

            return 'image';
        }
    }

    function getPostType($media): string
    {
        // If there's a video URL, it's a video post
        if (!empty($this->video_url)) {
            return 'video';
        }

        // If there's one video file, it's a reel
        if (count($media) === 1 && str()->contains($media[0]->getMimeType(), 'video')) {
            return 'reel';
        }

        // Otherwise, it's a regular post
        return 'post';
    }




    public function render()
    {
        return view('livewire.post.create');
    }

    public function updatedLost($value)
    {
        
    }

    public function updatedFound($value)
    {

    }

    public function updateButton()
    {
        // Force re-render to update button state
        $this->dispatch('$refresh');
    }

    public function addPollOption()
    {
        $this->poll_options[] = '';
    }

    public function removePollOption($index)
    {
        if (count($this->poll_options) > 2) {
            unset($this->poll_options[$index]);
            $this->poll_options = array_values($this->poll_options);
        }
    }

    public function updatedHasPoll($value)
    {
        if (!$value) {
            $this->poll_question = '';
            $this->poll_options = ['', ''];
            $this->poll_multiple_choice = false;
            $this->poll_duration_hours = 24;
        }
    }
}
