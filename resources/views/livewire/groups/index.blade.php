<div class="max-w-4xl mx-auto p-4">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Groups</h1>
        <p class="text-gray-600">Connect with others in your college, course, or interests</p>
    </div>

    <!-- Search and Create Group -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div class="flex-1">
                <input 
                    wire:model.live.debounce.300ms="search"
                    type="text" 
                    placeholder="Search groups..." 
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            @auth
            <button 
                onclick="document.getElementById('create-group-modal').showModal()"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                + Create Group
            </button>
            @endauth
        </div>
        
        <!-- Create Group Modal -->
        <dialog id="create-group-modal" class="modal">
            <div class="modal-box">
                <h3 class="font-bold text-lg mb-4">Create New Group</h3>
                <form wire:submit.prevent="createGroup">
                    <div class="mb-4">
                        <label for="newGroupName" class="block text-sm font-medium text-gray-700 mb-1">Group Name</label>
                        <input 
                            wire:model="newGroupName"
                            type="text" 
                            id="newGroupName" 
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="e.g., Computer Science 2025"
                            required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="newGroupDescription" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea
                            wire:model="newGroupDescription"
                            id="newGroupDescription"
                            rows="3"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Describe your group..."></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label for="newGroupImage" class="block text-sm font-medium text-gray-700 mb-1">Group Image</label>
                        <input
                            wire:model="newGroupImage"
                            type="file"
                            id="newGroupImage"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            accept="image/*">
                        @if($newGroupImage)
                            <div class="mt-2">
                                <img src="{{ $newGroupImage->temporaryUrl() }}" class="w-16 h-16 rounded-full object-cover">
                            </div>
                        @endif
                    </div>
                    
                    <div class="modal-action">
                        <button type="button" onclick="document.getElementById('create-group-modal').close()" class="btn">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Group</button>
                    </div>
                </form>
            </div>
        </dialog>
    </div>

    <!-- Groups Grid -->
    @if($groups->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($groups as $group)
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="p-5">
                        <div class="flex items-center mb-4">
                            <img src="{{ $group->image ?? asset('assets/dist/img/cedat.jpg') }}" 
                                 class="w-16 h-16 rounded-full object-cover border" 
                                 alt="{{ $group->name }}">
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $group->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $group->members->count() }} members</p>
                            </div>
                        </div>
                        
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $group->description ?? 'No description provided' }}</p>
                        
                        <div class="flex justify-between items-center">
                            <a href="{{ route('groups.show', $group->slug) }}" 
                               class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                View Group
                            </a>
                            
                            @auth
                            @if($group->members()->where('user_id', auth()->id())->exists())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Member
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Not joined
                                </span>
                            @endif
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-8">
            {{ $groups->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">No groups found</h3>
            <p class="mt-1 text-gray-500">Get started by creating a new group.</p>
            @auth
            <div class="mt-6">
                <button 
                    onclick="document.getElementById('create-group-modal').showModal()"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Create Group
                </button>
            </div>
            @endauth
        </div>
    @endif
</div>