<?php

namespace App\Livewire\Groups;

use App\Models\Group;
use App\Models\Media;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

class Show extends Component
{
    use WithFileUploads;

    public Group $group;
    public $posts;

    public $body;
    public $media;

    public function mount(Group $group)
    {
        $this->group = $group;
        $this->loadPosts();
    }

    public function loadPosts()
    {
        $this->posts = Post::with('media','user')
            ->where('group_id', $this->group->id)
            ->latest()->take(50)->get();
    }

    public function toggleMembership()
    {
        abort_unless(auth()->check(), 401);
        $user = auth()->user();
        if ($this->group->members()->where('user_id', $user->id)->exists()) {
            $this->group->members()->detach($user->id);
        } else {
            $this->group->members()->attach($user->id);
        }
    }

    public function post()
    {
        abort_unless(auth()->check(), 401);

        $this->validate([
            'body' => 'nullable|string|max:1000',
            'media' => 'nullable|file|mimes:png,jpg,jpeg,mp4,mov|max:100000',
        ]);

        if (empty($this->body) && empty($this->media)) {
            return;
        }

        $type = 'post';
        $mime = null;
        if ($this->media) {
            $mime = str()->contains($this->media->getMimeType(), 'video') ? 'video' : 'image';
            $type = $mime === 'video' ? 'reel' : 'post';
        }

        $post = Post::create([
            'user_id' => auth()->id(),
            'group_id' => $this->group->id,
            'description' => $this->body,
            'allow_commenting' => true,
            'hide_like_view' => false,
            'lost' => false,
            'found' => false,
            'type' => $type,
        ]);

        if ($this->media) {
            $path = $this->media->store('media', 'public');
            $url = url(Storage::url($path));
            Media::create([
                'url' => $url,
                'mime' => $mime,
                'mediable_id' => $post->id,
                'mediable_type' => Post::class,
            ]);
        }

        $this->reset('body', 'media');
        $this->loadPosts();
    }

    #[Title('College Group')]
    public function render()
    {
        return view('livewire.groups.show');
    }
}
