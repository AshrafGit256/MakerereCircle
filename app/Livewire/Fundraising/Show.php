<?php

namespace App\Livewire\Fundraising;

use App\Models\Fundraiser;
use Livewire\Component;

class Show extends Component
{
    public Fundraiser $fundraiser;
    public $recentDonations = [];
    public $progressPercentage = 0;
    public $remainingAmount = 0;
    public $totalDonors = 0;
    public $daysRemaining = null;

    public function mount(Fundraiser $fundraiser)
    {
        $this->fundraiser = $fundraiser;
        $this->loadFundraiserData();
    }

    public function loadFundraiserData()
    {
        $this->recentDonations = $this->fundraiser->getRecentDonations(10);
        $this->progressPercentage = $this->fundraiser->getProgressPercentage();
        $this->remainingAmount = $this->fundraiser->getRemainingAmount();
        $this->totalDonors = $this->fundraiser->getTotalDonors();
        $this->daysRemaining = $this->fundraiser->getDaysRemaining();
    }

    public function openDonateModal()
    {
        $this->dispatch('openModal', 'fundraising.donate', ['fundraiser' => $this->fundraiser->id]);
    }

    public function render()
    {
        return view('livewire.fundraising.show');
    }
}
