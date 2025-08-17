<?php

namespace App\Livewire\Groups;

use App\Models\Group;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;   // <---- ADD THIS

class Index extends Component
{
    use WithFileUploads, WithPagination;  // <---- USE IT HERE

    public $search = '';
    public $newGroupName = '';
    public $newGroupDescription = '';
    public $newGroupImage;

    protected $queryString = ['search'];

    public function render()
    {
        $groups = Group::with('members')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate(12);

        return view('livewire.groups.index', [
            'groups' => $groups
        ]);
    }

    public function createGroup()
    {
        abort_unless(auth()->check(), 401);

        $this->validate([
            'newGroupName' => 'required|string|max:255',
            'newGroupDescription' => 'nullable|string|max:1000',
            'newGroupImage' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($this->newGroupImage) {
            $imagePath = $this->newGroupImage->store('groups', 'public');
        }

        $group = Group::create([
            'name' => $this->newGroupName,
            'description' => $this->newGroupDescription,
            'image' => $imagePath,
            'slug' => Str::slug($this->newGroupName) . '-' . time(),
            'user_id' => auth()->id(),
        ]);

        // Add creator as member
        $group->members()->attach(auth()->id());

        $this->reset('newGroupName', 'newGroupDescription', 'newGroupImage');
        $this->dispatch('group-created');
    }
}
