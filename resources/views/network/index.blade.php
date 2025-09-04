<div class="max-w-7xl mx-auto p-6 lg:p-8 bg-gray-50 dark:bg-gray-800 rounded-lg shadow-xl">
    <header class="mb-8 text-center">
        <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2">Makerere Network</h1>
        <p class="text-lg text-gray-600 dark:text-gray-300">Connect with fellow students, esteemed lecturers, dedicated staff, and successful alumni.</p>
    </header>

    <section class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 bg-white dark:bg-gray-700 rounded-lg p-6 shadow mb-8">
        <div class="md:col-span-2 lg:col-span-3 xl:col-span-4">
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Search by name, username, email, title, or bio..."
                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-base py-2 px-4" />
        </div>

        <div class="flex flex-col">
            <label for="role-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Filter by Role</label>
            <select wire:model.live="role" id="role-filter" class="border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-base py-2 px-4">
                <option value="">All Roles</option>
                <option value="admin">Lecturers/Admin ({{ $stats['admins'] ?? 0 }})</option>
                <option value="alumni">Alumni ({{ $stats['alumni'] ?? 0 }})</option>
                <option value="staff">Non-teaching Staff ({{ $stats['staff'] ?? 0 }})</option>
                <option value="student">Students ({{ $stats['students'] ?? 0 }})</option>
            </select>
        </div>

        <div class="flex flex-col">
            <label for="employment-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Employment Status</label>
            <select wire:model.live="employmentStatus" id="employment-filter" class="border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-base py-2 px-4">
                <option value="">Any Status</option>
                <option value="Employed">Employed ({{ $stats['employed'] ?? 0 }})</option>
                <option value="Unemployed">Unemployed ({{ $stats['unemployed'] ?? 0 }})</option>
                <option value="Student">Student ({{ $stats['studying'] ?? 0 }})</option>
            </select>
        </div>

        <div class="flex flex-col">
            <label for="education-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Education Level</label>
            <select wire:model.live="educationLevel" id="education-filter" class="border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-base py-2 px-4">
                <option value="">Any Level</option>
                @foreach ($educationLevels as $level)
                <option value="{{ $level }}">{{ $level }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-col">
            <label for="course-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Course</label>
            <input
                wire:model.live.debounce.300ms="course"
                type="text"
                placeholder="Filter by Course..."
                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-base py-2 px-4" />
        </div>

        <div class="flex flex-col">
            <label for="location-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Location</label>
            <input
                wire:model.live.debounce.300ms="location"
                type="text"
                placeholder="Filter by Location..."
                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-base py-2 px-4" />
        </div>

        <div class="flex flex-col">
            <label for="skills-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Skills</label>
            <input
                wire:model.live.debounce.300ms="skills"
                type="text"
                placeholder="Filter by Skills..."
                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-base py-2 px-4" />
        </div>

        <div class="flex flex-col">
            <label for="schools-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Schools</label>
            <input
                wire:model.live.debounce.300ms="schools"
                type="text"
                placeholder="Filter by Schools..."
                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-base py-2 px-4" />
        </div>

        <div class="flex flex-col">
            <label for="talents-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Talents</label>
            <input
                wire:model.live.debounce.300ms="talents"
                type="text"
                placeholder="Filter by Talents..."
                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-base py-2 px-4" />
        </div>

        <div class="flex flex-col">
            <label for="sort-by" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Sort By</label>
            <select wire:model.live="sortBy" id="sort-by" class="border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-base py-2 px-4">
                <option value="name">Name</option>
                <option value="created_at">Newest Join</option>
            </select>
        </div>

        <div class="flex flex-col justify-end">
            <button
                wire:click="clearFilters"
                class="w-full bg-gray-200 dark:bg-gray-600 dark:text-white text-gray-800 font-semibold py-2 px-4 rounded-md shadow-sm hover:bg-gray-300 dark:hover:bg-gray-500 transition">Clear Filters</button>
        </div>
    </section>

    <section class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Browse Directory ({{ $stats['total'] ?? 0 }} Users)</h2>

        @php
        use App\Models\User;
        use Illuminate\Support\Str;

        $q = request('q');
        $role = request('role');

        $query = User::query();
        if ($q) {
        $query->where(function($qq) use ($q){
        $qq->where('name','like','%'.$q.'%')
        ->orWhere('username','like','%'.$q.'%')
        ->orWhere('email','like','%'.$q.'%');
        });
        }
        // role filter heuristic based on available fields
        if ($role === 'admin') {
        $query->where('is_admin', 1);
        } elseif ($role === 'alumni') {
        $query->where('email','like','%alumni%');
        } elseif ($role === 'staff') {
        $query->where('email','like','%mak.ac.ug%')->where('email','not like','%alumni%')->where('is_admin','!=',1);
        } elseif ($role === 'student') {
        // students as default catch-all: not admin, not alumni, not staff email
        $query->where(function($qq){
        $qq->whereNull('is_admin')->orWhere('is_admin','!=',1);
        })->where('email','not like','%alumni%');
        }

        $users = $query->orderBy('name')->paginate(30)->withQueryString();
        @endphp

        @if($users->count() === 0)
        <div class="p-8 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-center text-gray-600 dark:text-gray-300 shadow-sm">
            <p class="text-lg font-semibold">No users found matching your criteria.</p>
            <p class="text-sm mt-2">Try adjusting your search or filters.</p>
        </div>
        @else
        <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($users as $u)
            <li class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl p-6 flex flex-col items-center text-center shadow-lg hover:shadow-xl transition-all duration-200 ease-in-out transform hover:-translate-y-1">
                <a href="{{ route('profile.home', $u->username) }}" class="shrink-0 mb-4">
                    <img src="{{ $u->getImage() }}" alt="{{ $u->name }}" class="w-24 h-24 rounded-full object-cover border-4 border-blue-500 shadow-md" />
                </a>
                <div class="flex-1 min-w-0">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white truncate mb-1">
                        <a href="{{ route('profile.home', $u->username) }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">{{ $u->name }}</a>
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 truncate mb-2">@{{ $u->username }}</p>
                    <div class="text-xs font-semibold px-3 py-1 rounded-full mb-3
                            @php
                                $badgeClass = 'bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-200';
                                $roleLabel = 'User';
                                if (!empty($u->is_admin) && (int)$u->is_admin === 1) {
                                    $badgeClass = 'bg-blue-600 text-white';
                                    $roleLabel = 'Lecturer/Admin';
                                } elseif (Str::contains((string)$u->email, 'alumni')) {
                                    $badgeClass = 'bg-green-600 text-white';
                                    $roleLabel = 'Alumni';
                                } elseif (Str::contains((string)$u->email, 'mak.ac.ug')) {
                                    $badgeClass = 'bg-purple-600 text-white';
                                    $roleLabel = 'Staff';
                                } else {
                                    $roleLabel = 'Student'; // Default for non-admin, non-alumni, non-staff emails
                                    $badgeClass = 'bg-yellow-500 text-gray-900';
                                }
                            @endphp
                            {{ $badgeClass }}">{{ $roleLabel }}</div>
                    <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">{{ Str::limit($u->bio, 70) ?? ($u->title ?? 'No bio available.') }}</p>

                    <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1 mb-4">
                        @if($u->college)
                        <p><span class="font-medium">College:</span> {{ $u->college }}</p>
                        @endif
                        @if($u->program)
                        <p><span class="font-medium">Program:</span> {{ $u->program }}</p>
                        @endif
                        @if($u->year)
                        <p><span class="font-medium">Year:</span> {{ $u->year }}</p>
                        @endif
                        @if($u->hall)
                        <p><span class="font-medium">Hall/Hostel:</span> {{ $u->hall }}</p>
                        @endif
                    </div>

                    @auth
                    @if (auth()->user()->isNot($u))
                    <button
                        wire:click="{{ auth()->user()->isFollowing($u) ? 'unfollowUser(\'' . $u->username . '\')' : 'followUser(\'' . $u->username . '\')' }}"
                        class="mt-2 w-full py-2 px-4 rounded-md font-semibold text-white
                                    {{ auth()->user()->isFollowing($u) ? 'bg-gray-500 hover:bg-gray-600' : 'bg-blue-600 hover:bg-blue-700' }} transition-colors duration-200">
                        {{ auth()->user()->isFollowing($u) ? 'Following' : 'Connect' }}
                    </button>
                    @endif
                    @endauth
                </div>
            </li>
            @endforeach
        </ul>
        <div class="mt-8">
            {{ $users->links() }}
        </div>
        @endif
    </section>
</div>