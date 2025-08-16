<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class Networks extends Component
{
    use WithPagination;
    
    public $search = '';
    public $course = '';
    public $title = '';
    public $employmentStatus = '';
    public $location = '';
    public $skills = '';
    public $schools = '';
    public $talents = '';
    public $educationLevel = '';
    public $sortBy = 'name';
    public $sortDirection = 'asc';
    
    protected $queryString = [
        'search' => ['except' => ''],
        'course' => ['except' => ''],
        'title' => ['except' => ''],
        'employmentStatus' => ['except' => ''],
        'location' => ['except' => ''],
        'skills' => ['except' => ''],
        'schools' => ['except' => ''],
        'talents' => ['except' => ''],
        'educationLevel' => ['except' => ''],
        'sortBy' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'page' => ['except' => 1],
    ];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        
        $this->sortBy = $field;
    }
    
    public function clearFilters()
    {
        $this->search = '';
        $this->course = '';
        $this->title = '';
        $this->employmentStatus = '';
        $this->location = '';
        $this->skills = '';
        $this->schools = '';
        $this->talents = '';
        $this->educationLevel = '';
    }
    
    public function render()
    {
        $baseQuery = User::select('id', 'name', 'username', 'title', 'bio', 'course', 'education_level', 'employment_status', 'location', 'skills', 'schools', 'talents', 'created_at', 'image_name')
            ->where('is_admin', 0)
            ->where('is_delete', 0);
        
        $users = $baseQuery->clone()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('username', 'like', '%' . $this->search . '%')
                      ->orWhere('title', 'like', '%' . $this->search . '%')
                      ->orWhere('bio', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->course, function ($query) {
                $query->where('course', 'like', '%' . $this->course . '%');
            })
            ->when($this->title, function ($query) {
                $query->where('title', 'like', '%' . $this->title . '%');
            })
            ->when($this->employmentStatus, function ($query) {
                $query->where('employment_status', $this->employmentStatus);
            })
            ->when($this->location, function ($query) {
                $query->where('location', 'like', '%' . $this->location . '%');
            })
            ->when($this->skills, function ($query) {
                $query->where('skills', 'like', '%' . $this->skills . '%');
            })
            ->when($this->schools, function ($query) {
                $query->where('schools', 'like', '%' . $this->schools . '%');
            })
            ->when($this->talents, function ($query) {
                $query->where('talents', 'like', '%' . $this->talents . '%');
            })
            ->when($this->educationLevel, function ($query) {
                $query->where('education_level', $this->educationLevel);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(12);
        
        // Get statistics for the filters
        $stats = $this->getStats($baseQuery);
        
        return view('livewire.networks', [
            'users' => $users,
            'stats' => $stats
        ]);
    }
    
    private function getStats($baseQuery)
    {
        $query = $baseQuery->clone();
        
        return [
            'total' => $query->count(),
            'employed' => $query->where('employment_status', 'Employed')->count(),
            'students' => $query->where('employment_status', 'Student')->count(),
            'unemployed' => $query->where('employment_status', 'Unemployed')->count(),
            'studying' => $query->whereNull('employment_status')->count(),
        ];
    }
}