<?php

namespace App\Livewire\Fundraising;

use App\Models\Fundraiser;
use App\Models\Post;
use Livewire\Component;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;

class Create extends ModalComponent
{
    use WithFileUploads;

    public $title;
    public $description;
    public $target_amount;
    public $category = 'medical';
    public $end_date;
    public $beneficiary_name;
    public $beneficiary_story;
    public $contact_phone;
    public $contact_email;
    public $images = [];
    public $is_featured = false;

    public $categories = [
        'medical' => 'Medical Treatment',
        'education' => 'Education Support',
        'disaster' => 'Disaster Relief',
        'community' => 'Community Project',
        'emergency' => 'Emergency Relief',
        'business' => 'Business Support',
        'other' => 'Other Causes'
    ];

    /**
     * Supported: 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'
     */
    public static function modalMaxWidth(): string
    {
        return '5xl';
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'target_amount' => 'required|numeric|min:1000|max:10000000',
            'category' => 'required|in:' . implode(',', array_keys($this->categories)),
            'end_date' => 'nullable|date|after:today',
            'beneficiary_name' => 'nullable|string|max:255',
            'beneficiary_story' => 'nullable|string|max:5000',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'images.*' => 'nullable|image|max:2048',
            'is_featured' => 'boolean'
        ];
    }

    public function submit()
    {
        $this->validate();

        // Handle image uploads
        $imageUrls = [];
        if ($this->images) {
            foreach ($this->images as $image) {
                $path = $image->store('fundraisers', 'public');
                $imageUrls[] = url('storage/' . $path);
            }
        }

        // Create fundraiser
        $fundraiser = Fundraiser::create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'description' => $this->description,
            'target_amount' => $this->target_amount,
            'currency' => 'UGX',
            'category' => $this->category,
            'end_date' => $this->end_date,
            'beneficiary_name' => $this->beneficiary_name,
            'beneficiary_story' => $this->beneficiary_story,
            'contact_phone' => $this->contact_phone,
            'contact_email' => $this->contact_email,
            'images' => $imageUrls,
            'is_featured' => $this->is_featured,
            'is_active' => true
        ]);

        // Award points for creating fundraiser
        auth()->user()->awardPoints(25, 'fundraiser_created', 'Created a fundraiser: ' . $this->title);

        $this->reset();
        $this->dispatch('close');
        $this->dispatch('fundraiser-created', $fundraiser->id);

        session()->flash('message', 'Fundraiser created successfully!');
    }

    public function render()
    {
        return view('livewire.fundraising.create');
    }
}
