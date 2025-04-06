<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class ProfilePictureUpload extends Component
{
    use WithFileUploads;

    public $photo;

    public function updatedPhoto()
    {
        $this->validate([
            'photo' => 'image|max:1024', // max 1MB
        ]);

        $path = $this->photo->store('profile_photos', 'public');

        $user = Auth::user();
        $user->image_name = $path;
        $user->save();

        session()->flash('message', 'Profile picture updated!');
    }

    public function render()
    {
        return view('livewire.profile-picture-upload');
    }
}
