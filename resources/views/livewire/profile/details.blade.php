<div class="max-w-6xl mx-auto py-10 px-6">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">

        <!-- Profile Header with MAK Colors -->
        <div class="grid grid-cols-3 text-center font-bold text-white text-lg">
            <div class="bg-green-700 py-4">Makerere</div>
            <div class="bg-yellow-500 py-4 text-black">University</div>
            <div class="bg-black py-4">Student Profile</div>
        </div>

        <!-- Main profile section -->
        <div class="p-8">
            <div class="flex flex-col md:flex-row items-center md:items-start md:gap-8">
                <!-- Avatar -->
                <x-avatar src="{{ $user->getImage() }}" class="w-32 h-32 rounded-full border-4 border-gray-200 shadow-md" />

                <!-- Name & Title -->
                <div class="mt-4 md:mt-0 text-center md:text-left flex-1">
                    <h1 class="text-3xl font-extrabold text-gray-900">{{ $user->name }}</h1>

                    @if($user->title)
                    <p class="text-lg text-gray-600 mt-1">{{ $user->title }}</p>
                    @endif

                    @if($user->username)
                    <p class="text-gray-500 mt-1">{{ '@' . $user->username }}</p>
                    @endif

                    <!-- Action Buttons -->
                    <div class="mt-4 flex justify-center md:justify-start gap-4">
                        <button class="px-5 py-2 bg-green-700 text-white font-medium rounded-lg shadow hover:bg-green-800 transition">
                            Connect
                        </button>
                        <button wire:click="message({{$user->id}})" class="px-5 py-2 bg-red-600 text-white font-medium rounded-lg shadow hover:bg-red-700 transition">
                            Message
                        </button>
                    </div>
                </div>
            </div>

            <!-- Bio -->
            @if($user->bio)
            <div class="mt-8">
                <h2 class="text-2xl font-semibold text-gray-800 border-b pb-2">About</h2>
                <p class="text-gray-700 mt-3 leading-relaxed">{{ $user->bio }}</p>
            </div>
            @endif

            <!-- Info Sections -->
            <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-10">
                <!-- Personal Info -->
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800 border-b pb-2">Personal Information</h2>
                    <div class="mt-4 space-y-4 text-gray-700">
                        @if($user->birthdate)
                        <p><span class="font-medium">Birthdate:</span> {{ \Carbon\Carbon::parse($user->birthdate)->format('F j, Y') }}</p>
                        @endif

                        @if($user->location)
                        <p><span class="font-medium">Location:</span> {{ $user->location }}</p>
                        @endif

                        @if($user->employment_status)
                        <p><span class="font-medium">Employment Status:</span> {{ $user->employment_status }}</p>
                        @endif
                    </div>
                </div>

                <!-- Education & Skills -->
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800 border-b pb-2">Education & Skills</h2>
                    <div class="mt-4 space-y-4 text-gray-700">
                        @if($user->course)
                        <p><span class="font-medium">Course of Study:</span> {{ $user->course }}</p>
                        @endif

                        @if($user->education_level)
                        <p><span class="font-medium">Education Level:</span> {{ $user->education_level }}</p>
                        @endif

                        @if($user->skills)
                        <p><span class="font-medium">Skills:</span> {{ $user->skills }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Additional Sections -->
            @if($user->schools || $user->talents)
            <div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-10">
                @if($user->schools)
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800 border-b pb-2">Schools Attended</h2>
                    <p class="mt-3 text-gray-700">{{ $user->schools }}</p>
                </div>
                @endif

                @if($user->talents)
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800 border-b pb-2">Talents & Leadership</h2>
                    <p class="mt-3 text-gray-700">{{ $user->talents }}</p>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>