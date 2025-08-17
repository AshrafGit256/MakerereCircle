<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;


class Home extends Component
{

    public $posts;

    public $canLoadMore;
    public $pageSize = 15;
    public $pageIndex = 0;

    use WithFileUploads;

    public $newPostText;
    public $newPostImages = [];


    #[On('closeModal')]
    function reverUrl()
    {
        $this->js("history.replaceState({},'','/')");
    }


    #[On('post-created')]
    function postCreaed($id)
    {

        $post = Post::find($id);

        $this->posts = $this->posts->prepend($post);
    }



    function loadMore()  {
        if (!$this->canLoadMore) {
            return null;
        }

        $this->pageIndex += 1;
        $newPosts = Post::with('comments.replies')
            ->latest()
            ->skip($this->pageIndex * $this->pageSize)
            ->take($this->pageSize)
            ->get();

        // Initialize posts collection if null
        if (!$this->posts) {
            $this->posts = collect();
        }

        $this->posts = $this->posts->concat($newPosts);

        $total = Post::count();
        $this->canLoadMore = $this->posts->count() < $total;
    }


    #function to load posts 

    function loadPosts()  {
        $this->pageIndex = 0;
        $this->posts = Post::with('comments.replies')
            ->latest()
            ->take($this->pageSize)
            ->get();

        $total = Post::count();
        $this->canLoadMore = $this->posts->count() < $total;
    }

    function toggleFollow(User $user)  {

        abort_unless(auth()->check(),401);

        auth()->user()->toggleFollow($user);
        
    }


    function mount()
    {

        $this->loadPosts();

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

        $type = 'post';
        $mime = 'image';

        $post = Post::create([
            'user_id' => auth()->id(),
            'description' => $this->newPostText,
            'location' => null,
            'allow_commenting' => true,
            'hide_like_view' => false,
            'lost' => false,
            'found' => false,
            'type' => $type,
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

        $this->dispatch('post-created', $post->id);

        $this->reset('newPostText', 'newPostImages');
    }

    public function render()
    {
        $suggestedUsers = User::where('is_delete', 0)->where('is_admin', 0)->latest()->limit(60)->get();
        $groups = \App\Models\Group::with('members')->latest()->limit(5)->get();
        return view('livewire.home',['suggestedUsers'=>$suggestedUsers, 'groups'=>$groups]);
    }
}
