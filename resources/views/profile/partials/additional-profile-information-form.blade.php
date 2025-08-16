<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Additional Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Enhance your profile with additional information to better connect with employment opportunities.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Title -->
            <div class="md:col-span-2">
                <x-input-label for="title" :value="__('Professional Title')" />
                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full bg-transparent" :value="old('title', $user->title)" autocomplete="title" placeholder="e.g., Software Developer, Student, Researcher" />
                <x-input-error class="mt-2" :messages="$errors->get('title')" />
            </div>

            <!-- Bio -->
            <div class="md:col-span-2">
                <x-input-label for="bio" :value="__('Bio')" />
                <textarea id="bio" name="bio" rows="4" class="mt-1 block w-full bg-transparent border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" autocomplete="bio" placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('bio')" />
            </div>

            <!-- Birthdate -->
            <div>
                <x-input-label for="birthdate" :value="__('Birthdate')" />
                <x-text-input id="birthdate" name="birthdate" type="date" class="mt-1 block w-full bg-transparent" :value="old('birthdate', $user->birthdate)" autocomplete="bday" />
                <x-input-error class="mt-2" :messages="$errors->get('birthdate')" />
            </div>

            <!-- Location -->
            <div>
                <x-input-label for="location" :value="__('Location')" />
                <x-text-input id="location" name="location" type="text" class="mt-1 block w-full bg-transparent" :value="old('location', $user->location)" autocomplete="location" placeholder="e.g., Kampala, Uganda" />
                <x-input-error class="mt-2" :messages="$errors->get('location')" />
            </div>

            <!-- Course -->
            <div>
                <x-input-label for="course" :value="__('Course of Study')" />
                <x-text-input id="course" name="course" type="text" class="mt-1 block w-full bg-transparent" :value="old('course', $user->course)" autocomplete="course" placeholder="e.g., Computer Science, Medicine" />
                <x-input-error class="mt-2" :messages="$errors->get('course')" />
            </div>

            <!-- Education Level -->
            <div>
                <x-input-label for="education_level" :value="__('Current Education Level')" />
                <select id="education_level" name="education_level" class="mt-1 block w-full bg-transparent border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" autocomplete="education-level">
                    <option value="">Select your level</option>
                    <option value="Certificate" {{ old('education_level', $user->education_level) == 'Certificate' ? 'selected' : '' }}>Certificate</option>
                    <option value="Diploma" {{ old('education_level', $user->education_level) == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                    <option value="Undergraduate" {{ old('education_level', $user->education_level) == 'Undergraduate' ? 'selected' : '' }}>Undergraduate</option>
                    <option value="Postgraduate" {{ old('education_level', $user->education_level) == 'Postgraduate' ? 'selected' : '' }}>Postgraduate</option>
                    <option value="PhD" {{ old('education_level', $user->education_level) == 'PhD' ? 'selected' : '' }}>PhD</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('education_level')" />
            </div>

            <!-- Skills -->
            <div class="md:col-span-2">
                <x-input-label for="skills" :value="__('Skills (comma separated)')" />
                <x-text-input id="skills" name="skills" type="text" class="mt-1 block w-full bg-transparent" :value="old('skills', $user->skills)" autocomplete="skills" placeholder="e.g., PHP, JavaScript, Project Management" />
                <x-input-error class="mt-2" :messages="$errors->get('skills')" />
            </div>

            <!-- Schools -->
            <div class="md:col-span-2">
                <x-input-label for="schools" :value="__('Schools Attended (comma separated)')" />
                <x-text-input id="schools" name="schools" type="text" class="mt-1 block w-full bg-transparent" :value="old('schools', $user->schools)" autocomplete="schools" placeholder="e.g., Makerere University, St. Mary's College Kisubi" />
                <x-input-error class="mt-2" :messages="$errors->get('schools')" />
            </div>

            <!-- Talents/Leadership -->
            <div class="md:col-span-2">
                <x-input-label for="talents" :value="__('Talents & Leadership Experience')" />
                <textarea id="talents" name="talents" rows="3" class="mt-1 block w-full bg-transparent border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" autocomplete="talents" placeholder="Describe your talents, leadership roles, or extracurricular activities...">{{ old('talents', $user->talents) }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('talents')" />
            </div>

            <!-- Employment Status -->
            <div class="md:col-span-2">
                <x-input-label for="employment_status" :value="__('Employment Status')" />
                <select id="employment_status" name="employment_status" class="mt-1 block w-full bg-transparent border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" autocomplete="employment-status">
                    <option value="">Select your status</option>
                    <option value="Student" {{ old('employment_status', $user->employment_status) == 'Student' ? 'selected' : '' }}>Student</option>
                    <option value="Employed" {{ old('employment_status', $user->employment_status) == 'Employed' ? 'selected' : '' }}>Employed</option>
                    <option value="Unemployed" {{ old('employment_status', $user->employment_status) == 'Unemployed' ? 'selected' : '' }}>Unemployed</option>
                    <option value="Self-employed" {{ old('employment_status', $user->employment_status) == 'Self-employed' ? 'selected' : '' }}>Self-employed</option>
                    <option value="Intern" {{ old('employment_status', $user->employment_status) == 'Intern' ? 'selected' : '' }}>Intern</option>
                    <option value="Freelancer" {{ old('employment_status', $user->employment_status) == 'Freelancer' ? 'selected' : '' }}>Freelancer</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('employment_status')" />
            </div>
        </div>

        <div class="flex items-center gap-4 mt-6">
            <x-primary-button>{{ __('Save Additional Information') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>