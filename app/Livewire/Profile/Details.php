<?php

namespace App\Livewire\Profile;

use App\Models\User;
use Livewire\Component;

class Details extends Component
{
    public $user;

    function mount($user)
    {
        $this->user = User::whereUsername($user)->firstOrFail();
    }

    public function render()
    {
        return view('livewire.profile.details');
    }
}