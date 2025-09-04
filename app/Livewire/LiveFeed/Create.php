<?php

namespace App\Livewire\LiveFeed;

use App\Models\Post;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class Create extends ModalComponent
{
    public $description;
    public $stream_url;
    public $location;

    /**
     * Supported: 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'
     */
    public static function modalMaxWidth(): string
    {
        return '4xl';
    }

    public function submit()
    {
        $this->validate([
            'description' => 'required|string|max:1000',
            'stream_url' => 'required|url',
            'location' => 'nullable|string|max:255',
        ]);

        // Create live feed post
        Post::create([
            'user_id' => auth()->user()->id,
            'description' => $this->description,
            'location' => $this->location,
            'video_url' => $this->stream_url,
            'type' => 'live',
            'allow_commenting' => true,
            'hide_like_view' => false,
        ]);

        $this->reset();
        $this->dispatch('close');
        $this->dispatch('live-feed-created');
    }

    public function render()
    {
        return view('livewire.live-feed.create');
    }
}
