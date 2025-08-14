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

    // New customization options
    public $font_size = 16; // px
    public $radius = 8; // px
    public $link_style = 'hover'; // 'hover' | 'always' | 'none'
    public $reduce_motion = false;
    public $compact = false;

    public function mount()
    {
        $user = Auth::user();
        // If you later persist per-user settings in DB, hydrate here
        $this->font = session('ui.font', 'sans');
        $this->theme = session('ui.theme', 'light');
        $this->accent = session('ui.accent', '#0ea5e9');

        $this->font_size = (int) session('ui.font_size', 16);
        $this->radius = (int) session('ui.radius', 8);
        $this->link_style = session('ui.link_style', 'hover');
        $this->reduce_motion = (bool) session('ui.reduce_motion', false);
        $this->compact = (bool) session('ui.compact', false);
    }

    public function save()
    {
        // Persist to session (can be replaced with DB persistence later)
        session(['ui.font' => $this->font]);
        session(['ui.theme' => $this->theme]);
        session(['ui.accent' => $this->accent]);

        session(['ui.font_size' => (int) $this->font_size]);
        session(['ui.radius' => (int) $this->radius]);
        session(['ui.link_style' => $this->link_style]);
        session(['ui.reduce_motion' => (bool) $this->reduce_motion]);
        session(['ui.compact' => (bool) $this->compact]);

        // Dispatch with payload so layout can apply instantly
        $this->dispatch('ui-updated', [
            'font' => $this->font,
            'theme' => $this->theme,
            'accent' => $this->accent,
            'font_size' => (int) $this->font_size,
            'radius' => (int) $this->radius,
            'link_style' => $this->link_style,
            'reduce_motion' => (bool) $this->reduce_motion,
            'compact' => (bool) $this->compact,
        ]);
    }

    #[Title('Settings')]
    public function render()
    {
        return view('livewire.settings.page');
    }
}
