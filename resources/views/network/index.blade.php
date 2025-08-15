<div class="max-w-6xl mx-auto p-4">
    <header class="mb-4">
        <h1 class="text-2xl font-bold">Network</h1>
        <p class="text-sm text-gray-600">Discover people across Makerere: lecturers, alumni, staff, and students.</p>
    </header>

    <section class="mb-4 flex flex-wrap items-center gap-3">
        <form method="GET" class="flex items-center gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by name, username, email"
                   class="border rounded-md px-3 py-1.5 text-sm w-72" />
            <select name="role" class="border rounded-md px-2 py-1.5 text-sm">
                <option value="">All roles</option>
                <option value="admin" {{ request('role')==='admin' ? 'selected' : '' }}>Lecturers/Admin</option>
                <option value="alumni" {{ request('role')==='alumni' ? 'selected' : '' }}>Alumni</option>
                <option value="staff" {{ request('role')==='staff' ? 'selected' : '' }}>Non-teaching staff</option>
                <option value="student" {{ request('role')==='student' ? 'selected' : '' }}>Students</option>
            </select>
            <button class="bg-blue-600 text-white text-sm font-semibold px-3 py-1.5 rounded-md">Filter</button>
        </form>
        <div class="ml-auto flex items-center gap-2 text-xs">
            <span class="inline-flex items-center gap-1"><span class="inline-block w-3 h-3 rounded bg-blue-600"></span> Lecturers/Admin</span>
            <span class="inline-flex items-center gap-1"><span class="inline-block w-3 h-3 rounded bg-red-600"></span> Alumni</span>
            <span class="inline-flex items-center gap-1"><span class="inline-block w-3 h-3 rounded bg-black"></span> Non-teaching staff</span>
            <span class="inline-flex items-center gap-1"><span class="inline-block w-3 h-3 rounded bg-yellow-400"></span> Students</span>
        </div>
    </section>

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
        <div class="p-6 border rounded-md bg-white text-center text-gray-600">No users found.</div>
    @else
        <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($users as $u)
                @php
                    // badge inference
                    $badge = ['label' => 'Student', 'class' => 'bg-yellow-400 text-black'];
                    if (!empty($u->is_admin) && (int)$u->is_admin === 1) {
                        $badge = ['label' => 'Admin', 'class' => 'bg-blue-600 text-white'];
                    } elseif (Str::contains((string)$u->email, 'alumni')) {
                        $badge = ['label' => 'Alumni', 'class' => 'bg-red-600 text-white'];
                    } elseif (Str::contains((string)$u->email, 'mak.ac.ug')) {
                        $badge = ['label' => 'Staff', 'class' => 'bg-black text-white'];
                    }
                @endphp
                <li class="bg-white border rounded-lg p-4 flex gap-3">
                    <a href="{{ route('profile.home', $u->username) }}" class="shrink-0">
                        <img src="{{ $u->getImage() }}" alt="{{ $u->name }}" class="w-14 h-14 rounded-full object-cover border" />
                    </a>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <a href="{{ route('profile.home', $u->username) }}" class="font-semibold truncate block">{{ $u->name }}</a>
                                <div class="text-xs text-gray-500 truncate">@{{ $u->username }}</div>
                            </div>
                            <span class="text-[10px] px-2 py-0.5 rounded-full {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                        </div>
                        <div class="mt-2 grid grid-cols-2 gap-x-4 gap-y-1 text-xs text-gray-600">
                            <div><span class="text-gray-400">College:</span> {{ $u->college ?? '—' }}</div>
                            <div><span class="text-gray-400">Program:</span> {{ $u->program ?? '—' }}</div>
                            <div><span class="text-gray-400">Year:</span> {{ $u->year ?? '—' }}</div>
                            <div><span class="text-gray-400">Hall/Hostel:</span> {{ $u->hall ?? '—' }}</div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
        <div class="mt-4">{{ $users->links() }}</div>
    @endif
</div>
