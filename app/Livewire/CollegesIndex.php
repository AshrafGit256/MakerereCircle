<?php

namespace App\Livewire;

use App\Models\College;

use Livewire\Component;

class CollegesIndex extends Component
{
    public function render()
    {
        $colleges = College::all();

        return view('livewire.colleges-index', compact('colleges'));
    }
}
