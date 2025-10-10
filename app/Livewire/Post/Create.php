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

    // General Post Properties
    public $media = [];
    public $description;
    public $location;
    public $lost = false;
    public $found = false;
    public $hide_like_view = false;
    public $allow_commenting = true;

    // Video
    public $video_url = '';

    // Poll Properties
    public $has_poll = false;
    public $poll_question = '';
    public $poll_options = ['', '']; // Minimum 2 options
    public $poll_multiple_choice = false;
    public $poll_duration_hours = 24;

    // Fundraiser Properties
    public $is_fundraiser = false;
    public $fundraiser_title = '';
    public $fundraiser_target_amount = null;
    public $fundraiser_category = 'medical';
    public $fundraiser_end_date = null;
    public $fundraiser_beneficiary_name = '';
    public $fundraiser_beneficiary_story = '';
    public $fundraiser_contact_phone = '';
    public $fundraiser_contact_email = '';
    public $fundraiser_images = [];

    /**
     * Supported modal widths
     */
    public static function modalMaxWidth(): string
    {
        return '4xl';
    }

    /**
     * Submit the post
     */
    public function submit()
    {
        // Validate common fields
        $this->validate([
            'description' => 'nullable|string|max:5000',
            'media.*' => 'nullable|file|mimes:png,jpg,jpeg,mp4,mov|max:100000',
            'video_url' => 'nullable|url',
            'allow_commenting' => 'boolean',
            'hide_like_view' => 'boolean',
            'lost' => 'boolean',
            'found' => 'boolean',
        ]);

        // Determine post type
        $type = $this->getPostType($this->media);

        // Create Post
        $post = Post::create([
            'user_id' => auth()->user()->id,
            'description' => $this->description,
            'location' => $this->location,
            'allow_commenting' => $this->allow_commenting,
            'hide_like_view' => $this->hide_like_view,
            'lost' => $this->lost,
            'found' => $this->found,
            'type' => $type,
            'video_url' => $this->video_url,
            'has_poll' => $this->has_poll,
            'is_fundraiser' => $this->is_fundraiser,
        ]);

        // Handle Media
        foreach ($this->media as $file) {
            $mime = $this->getMime($file);
            $path = $file->store('media', 'public');
            $url = url(Storage::url($path));

            Media::create([
                'url' => $url,
                'mime' => $mime,
                'mediable_id' => $post->id,
                'mediable_type' => Post::class,
            ]);
        }

        // Reset form
        $this->reset([
            'media',
            'description',
            'location',
            'lost',
            'found',
            'hide_like_view',
            'allow_commenting',
            'video_url',
            'has_poll',
            'poll_question',
            'poll_options',
            'poll_multiple_choice',
            'poll_duration_hours',
            'is_fundraiser',
            'fundraiser_title',
            'fundraiser_target_amount',
            'fundraiser_category',
            'fundraiser_end_date',
            'fundraiser_beneficiary_name',
            'fundraiser_beneficiary_story',
            'fundraiser_contact_phone',
            'fundraiser_contact_email',
            'fundraiser_images'
        ]);

        $this->dispatch('close');
        $this->dispatch('post-created', $post->id);
    }

    /**
     * Determine media type
     */
    private function getMime($media): string
    {
        return str()->contains($media->getMimeType(), 'video') ? 'video' : 'image';
    }

    /**
     * Determine post type
     */
    private function getPostType($media): string
    {
        if (count($media) === 1 && str()->contains($media[0]->getMimeType(), 'video')) {
            return 'reel';
        }
        return 'post';
    }

    /**
     * Render Livewire view
     */
    public function render()
    {
        return view('livewire.post.create');
    }

    /**
     * Poll management
     */
    public function addPollOption()
    {
        if (count($this->poll_options) < 10) {
            $this->poll_options[] = '';
        }
    }

    public function removePollOption($index)
    {
        if (count($this->poll_options) > 2) {
            unset($this->poll_options[$index]);
            $this->poll_options = array_values($this->poll_options);
        }
    }

    /**
     * Fundraiser image management
     */
    public function removeFundraiserImage($index)
    {
        unset($this->fundraiser_images[$index]);
        $this->fundraiser_images = array_values($this->fundraiser_images);
    }

    public function updateButton()
    {
        // Currently no logic needed; this satisfies Livewire
    }

    // Optional hooks if needed
    public function updatedLost($value) {}
    public function updatedFound($value) {}
}
