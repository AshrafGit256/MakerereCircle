<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Attributes\On;
use Livewire\Component;

class Explore extends Component
{

    #[On('closeModal')]
    function reverUrl()
    {
        $this->js("history.replaceState({},'','/explore')");
    }


    public function render()
    {
        $posts = Post::with(['user', 'media', 'comments', 'likers'])
                ->withCount(['comments', 'likers'])
                ->limit(100)
                ->latest()
                ->get();
        return view('livewire.explore',['posts'=>$posts]);
    }
}
