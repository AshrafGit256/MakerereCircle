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
    public $perPageIncrements=5;
    public $perPage=20;

    use WithFileUploads;

    public $newPostText;
    public $newPostImage;


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


     //   dd('here');
        if (!$this->canLoadMore) {

            return null;
        }


        #increment page
        $this->perPage += $this->perPageIncrements;

        #load posts
        $this->loadPosts();

        
    }


    #function to load posts 

    function loadPosts()  {

        $this->posts = Post::with('comments.replies')
        ->latest()
        ->take($this->perPage)->get();

        $this->canLoadMore= (count($this->posts)>= $this->perPage);
        
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
        abort_unless(auth()->check(), 401);

        $this->validate([
            'newPostText' => 'nullable|string|max:1000',
            'newPostImage' => 'nullable|file|mimes:png,jpg,jpeg,mp4,mov|max:100000',
        ]);

        if (empty($this->newPostText) && empty($this->newPostImage)) {
            return;
        }

        $type = 'post';
        $mime = null;

        if ($this->newPostImage) {
            $mime = str()->contains($this->newPostImage->getMimeType(), 'video') ? 'video' : 'image';
            $type = $mime === 'video' ? 'reel' : 'post';
        }

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

        if ($this->newPostImage) {
            $path = $this->newPostImage->store('media', 'public');
            $url = url(Storage::url($path));

            Media::create([
                'url' => $url,
                'mime' => $mime,
                'mediable_id' => $post->id,
                'mediable_type' => Post::class,
            ]);
        }

        $this->dispatch('post-created', $post->id);

        $this->reset('newPostText', 'newPostImage');
    }

    public function render()
    {
        $suggestedUsers= User::limit(5)->get();
        return view('livewire.home',['suggestedUsers'=>$suggestedUsers]);
    }
}
