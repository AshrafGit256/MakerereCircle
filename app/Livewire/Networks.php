<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class Networks extends Component
{
    public function render()
    {
        // Fetch all users with their name, username, and title
        $users = User::select('id', 'name', 'username', 'title')
            ->where('is_admin', 0)
            ->where('is_delete', 0)
            ->get();

        return view('livewire.networks', ['users' => $users]);
    }
}