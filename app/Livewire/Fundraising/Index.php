<?php

namespace App\Livewire\Fundraising;

use App\Models\Fundraiser;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $sortBy = 'newest';
    public $showCompleted = false;

    public $categories = [
        'medical' => 'Medical Treatment',
        'education' => 'Education Support',
        'disaster' => 'Disaster Relief',
        'community' => 'Community Project',
        'emergency' => 'Emergency Relief',
        'business' => 'Business Support',
        'other' => 'Other Causes'
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingSortBy()
    {
        $this->resetPage();
    }

    public function updatingShowCompleted()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->dispatch('openModal', 'fundraising.create');
    }

    public function getFundraisers()
    {
        $query = Fundraiser::with('user')
            ->where('is_active', true);

        // Search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('beneficiary_name', 'like', '%' . $this->search . '%');
            });
        }

        // Category filter
        if ($this->category) {
            $query->where('category', $this->category);
        }

        // Completed filter
        if (!$this->showCompleted) {
            $query->whereRaw('current_amount < target_amount');
        }

        // Sorting
        switch ($this->sortBy) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'most_funded':
                $query->orderByRaw('(current_amount / target_amount) DESC');
                break;
            case 'least_funded':
                $query->orderByRaw('(current_amount / target_amount) ASC');
                break;
            case 'ending_soon':
                $query->whereNotNull('end_date')
                      ->orderBy('end_date', 'asc');
                break;
            case 'featured':
                $query->orderBy('is_featured', 'desc')
                      ->orderBy('created_at', 'desc');
                break;
        }

        return $query->paginate(12);
    }

    public function render()
    {
        return view('livewire.fundraising.index', [
            'fundraisers' => $this->getFundraisers()
        ]);
    }
}
