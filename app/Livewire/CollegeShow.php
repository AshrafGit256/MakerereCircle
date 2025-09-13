<?php

namespace App\Livewire;

use App\Models\College;
use App\Models\Post;
use App\Models\User;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class CollegeShow extends Component
{
    use WithFileUploads, WithPagination;

    public $college;
    public $collegeId;
    public $imageUrl;

    protected $paginationTheme = 'tailwind';

    // Quick composer state
    public $newPostText;
    public $newPostImages = [];

    public function mount(College $college)
    {
        // Route model binding by slug: /colleges/{college:slug}
        $this->college = $college;
        $this->collegeId = $college->id;
        $this->imageUrl = $college->image ? asset('storage/' . $college->image) : asset('images/default-college.jpg');
    }

    public function quickPost()
    {
        if (!auth()->check()) {
            return;
        }

        $this->validate([
            'newPostText' => 'nullable|string|max:1000',
            'newPostImages.*' => 'nullable|image|mimes:png,jpg,jpeg|max:100000',
        ]);

        if (empty($this->newPostText) && count($this->newPostImages) === 0) {
            return;
        }

        $post = Post::create([
            'user_id' => auth()->id(),
            'description' => $this->newPostText,
            'location' => null,
            'allow_commenting' => true,
            'hide_like_view' => false,
            'lost' => false,
            'found' => false,
            'type' => 'post',
            'college_id' => $this->collegeId,
        ]);

        if (!empty($this->newPostImages)) {
            foreach ($this->newPostImages as $upload) {
                $path = $upload->store('media', 'public');
                $url = url(Storage::url($path));

                Media::create([
                    'url' => $url,
                    'mime' => 'image',
                    'mediable_id' => $post->id,
                    'mediable_type' => Post::class,
                ]);
            }
        }

        // Reset composer, go back to first page and refresh the list
        $this->reset('newPostText', 'newPostImages');
        $this->resetPage();
        $this->dispatch('$refresh');
    }

    public function render()
    {
        $posts = Post::inCollege($this->collegeId)->with('user', 'media')->latest()->paginate(10);

        $members = User::where('college_id', $this->collegeId)->get();

        $leaders = $members->filter(function ($user) {
            $title = strtolower($user->title ?? '');
            return str_contains($title, 'dean') || str_contains($title, 'head') || str_contains($title, 'leader');
        });

        $admins = $members->filter(function ($user) {
            $title = strtolower($user->title ?? '');
            return str_contains($title, 'admin') || str_contains($title, 'director') || str_contains($title, 'chair');
        });

        return view('livewire.college-show', compact('posts', 'leaders', 'admins', 'members'));
    }
}
