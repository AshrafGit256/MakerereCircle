<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Claim;
use App\Models\Post;

class ClaimForm extends Component
{
    public Post $post;
    public string $name = '';
    public string $email = '';
    public string $description = '';
    public string $location = '';

    protected array $rules = [
        'name'        => 'required|min:3',
        'email'       => 'required|email',
        'description' => 'required|min:5',
        'location'    => 'required',
    ];

    // Accept the raw params array, then look up the Post
    public function mount(array $params)
    {
        $postId = $params['post'] ?? null;

        if (! $postId) {
            $this->closeModal();
            return;
        }

        $this->post = Post::findOrFail($postId);
    }

    public function submit()
    {
        $this->validate();

        Claim::create([
            'post_id'     => $this->post->id,
            'name'        => $this->name,
            'email'       => $this->email,
            'description' => $this->description,
            'location'    => $this->location,
        ]);

        $this->closeModal();
        session()->flash('message', 'Claim submitted!');
    }

    public function render()
    {
        return view('livewire.claim-form');
    }
}

