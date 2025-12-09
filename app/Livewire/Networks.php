<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Schema;

class Networks extends Component
{
    use WithPagination;

    // Basic filters
    public $search = '';
    public $course = '';
    public $title = '';
    public $location = '';
    public $skills = '';
    public $schools = '';
    public $talents = '';
    public $educationLevel = '';
    public $employmentStatus = '';

    // Advanced filters
    public $minAge = '';
    public $maxAge = '';
    public $gender = '';
    public $yearOfStudy = '';
    public $semester = '';
    public $role = '';
    public $lookingFor = '';
    public $interests = '';
    public $selectedInterests = '';

    // Sorting
    public $sortBy = 'name';
    public $sortDirection = 'asc';

    // Quick filters
    public $quickFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'course' => ['except' => ''],
        'title' => ['except' => ''],
        'location' => ['except' => ''],
        'educationLevel' => ['except' => ''],
        'employmentStatus' => ['except' => ''],
        'yearOfStudy' => ['except' => ''],
        'role' => ['except' => ''],
        'sortBy' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function mount()
    {
        // Initialize with default values if needed
    }

    public function setFilter($filter)
    {
        $this->quickFilter = $filter;

        switch ($filter) {
            case 'hiring':
                $this->lookingFor = 'employment';
                $this->employmentStatus = 'Hiring Manager';
                break;
            case 'project_partners':
                $this->lookingFor = 'collaboration';
                break;
            case 'mentors':
                $this->role = 'alumni';
                $this->lookingFor = 'mentorship';
                break;
            case 'region_mates':
                // This would require region field in users table
                break;
            case 'same_school':
                // This would require previous schools field
                break;
        }
    }

    public function removeInterest($interest)
    {
        $interests = explode(',', $this->selectedInterests);
        $interests = array_filter($interests, function ($i) use ($interest) {
            return trim($i) !== trim($interest);
        });
        $this->selectedInterests = implode(',', $interests);
    }

    public function clearFilters()
    {
        $this->reset([
            'search',
            'course',
            'title',
            'location',
            'skills',
            'schools',
            'talents',
            'educationLevel',
            'employmentStatus',
            'minAge',
            'maxAge',
            'gender',
            'yearOfStudy',
            'semester',
            'role',
            'lookingFor',
            'interests',
            'selectedInterests',
            'quickFilter'
        ]);
        $this->resetPage();
    }

    public function saveSearch()
    {
        // Save current search to user's saved searches
        // You'll need to implement this based on your needs
        session()->flash('message', 'Search saved successfully!');
    }

    public function connectWith($userId)
    {
        // Implement connection request logic
        session()->flash('message', 'Connection request sent!');
    }

    public function render()
    {
        $users = User::query()
            ->where('is_admin', 0)
            ->where('is_delete', 0)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('username', 'like', '%' . $this->search . '%')
                        ->orWhere('bio', 'like', '%' . $this->search . '%')
                        ->orWhere('title', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->course, function ($query) {
                $query->where('course', 'like', '%' . $this->course . '%');
            })
            ->when($this->title, function ($query) {
                $query->where('title', 'like', '%' . $this->title . '%');
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
            ->when($this->employmentStatus, function ($query) {
                $query->where('employment_status', $this->employmentStatus);
            })
            ->when($this->yearOfStudy, function ($query) {
                $query->where('year_of_study', $this->yearOfStudy);
            })
            ->when($this->role, function ($query) {
                $query->where('role', $this->role);
            })
            ->when($this->lookingFor, function ($query) {
                $query->where('looking_for', 'like', '%' . $this->lookingFor . '%');
            })
            ->when($this->gender, function ($query) {
                $query->where('gender', $this->gender);
            })
            ->when($this->selectedInterests, function ($query) {
                $interests = explode(',', $this->selectedInterests);
                foreach ($interests as $interest) {
                    if (trim($interest)) {
                        $query->where(function ($q) use ($interest) {
                            $q->where('skills', 'like', '%' . trim($interest) . '%')
                                ->orWhere('interests', 'like', '%' . trim($interest) . '%');
                        });
                    }
                }
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(12);

        // Calculate matching tags (this is simplified - you'd need actual logic)
        $users->each(function ($user) {
            // This would compare current user's interests with other users
            // For now, we'll just show their skills as matching tags
            $user->matching_tags = $user->skills;
        });

        $stats = $this->getStats();

        return view('livewire.networks', [
            'users' => $users,
            'stats' => $stats,
        ]);
    }

    private function getStats()
    {
        $total = User::where('is_admin', 0)->where('is_delete', 0)->count();

        // Check if role column exists before querying it
        if (Schema::hasColumn('users', 'role')) {
            $students = User::where('is_admin', 0)->where('is_delete', 0)
                ->where('role', 'student')->count();
        } else {
            $students = 0; // Default value if column doesn't exist
        }

        // Check if employment_status column exists
        if (Schema::hasColumn('users', 'employment_status')) {
            $employed = User::where('is_admin', 0)->where('is_delete', 0)
                ->where('employment_status', 'Employed')->count();
            $unemployed = User::where('is_admin', 0)->where('is_delete', 0)
                ->where('employment_status', 'Unemployed')->count();
        } else {
            $employed = 0;
            $unemployed = 0;
        }

        // Check if looking_for column exists
        if (Schema::hasColumn('users', 'looking_for')) {
            $hiring = User::where('is_admin', 0)->where('is_delete', 0)
                ->where('looking_for', 'like', '%employment%')->count();
        } else {
            $hiring = 0;
        }

        $projects = 0; // You'd track project participation

        return [
            'total' => $total,
            'students' => $students,
            'employed' => $employed,
            'unemployed' => $unemployed,
            'hiring' => $hiring,
            'projects' => $projects,
        ];
    }
}
