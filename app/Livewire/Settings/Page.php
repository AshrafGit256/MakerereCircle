<?php

namespace App\Livewire\Settings;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

class Page extends Component
{
    public $font = 'sans';
    public $theme = 'light';
    public $accent = '#0ea5e9';

    public function mount()
    {
        $user = Auth::user();
        // If you later persist per-user settings in DB, hydrate here
        $this->font = session('ui.font', 'sans');
        $this->theme = session('ui.theme', 'light');
        $this->accent = session('ui.accent', '#0ea5e9');
    }

    public function save()
    {
        // Persist to session (can be replaced with DB persistence later)
        session(['ui.font' => $this->font]);
        session(['ui.theme' => $this->theme]);
        session(['ui.accent' => $this->accent]);

        $this->dispatch('ui-updated');
    }

    #[Title('Settings')]
    public function render()
    {
        return view('livewire.settings.page');
    }
}
