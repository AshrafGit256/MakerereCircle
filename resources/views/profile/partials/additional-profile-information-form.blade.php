<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-white">
            {{ __('Complete Your Profile') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Complete your profile to unlock networking features and better employment opportunities.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Title -->
            <div class="md:col-span-2">
                <x-input-label for="title" :value="__('Professional Title')" />
                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full dark:bg-gray-800 dark:border-gray-700 dark:text-white" :value="old('title', $user->title)" autocomplete="title" placeholder="e.g., Software Developer, Student, Researcher" />
                <x-input-error class="mt-2" :messages="$errors->get('title')" />
            </div>

            <!-- Bio -->
            <div class="md:col-span-2">
                <x-input-label for="bio" :value="__('Bio')" />
                <textarea id="bio" name="bio" rows="4" class="mt-1 block w-full dark:bg-gray-800 dark:border-gray-700 dark:text-white border-gray-300 dark:border-gray-700 focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" autocomplete="bio" placeholder="Tell us about yourself, your goals, and what you're looking for...">{{ old('bio', $user->bio) }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('bio')" />
            </div>

            <!-- Personal Information Section -->
            <div class="md:col-span-2">
                <h3 class="text-md font-medium text-gray-900 dark:text-white mb-4">{{ __('Personal Information') }}</h3>
            </div>

            <!-- Birthdate -->
            <div>
                <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
                <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-1 block w-full dark:bg-gray-800 dark:border-gray-700 dark:text-white" :value="old('date_of_birth', $user->date_of_birth)" autocomplete="bday" />
                <x-input-error class="mt-2" :messages="$errors->get('date_of_birth')" />
            </div>

            <!-- Gender -->
            <div>
                <x-input-label for="gender" :value="__('Gender')" />
                <select id="gender" name="gender" class="mt-1 block w-full dark:bg-gray-800 dark:border-gray-700 dark:text-white border-gray-300 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" autocomplete="sex">
                    <option value="">Select gender</option>
                    <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('gender')" />
            </div>

            <!-- Location -->
            <div>
                <x-input-label for="location" :value="__('Location')" />
                <x-text-input id="location" name="location" type="text" class="mt-1 block w-full dark:bg-gray-800 dark:border-gray-700 dark:text-white" :value="old('location', $user->location)" autocomplete="location" placeholder="e.g., Kampala, Uganda" />
                <x-input-error class="mt-2" :messages="$errors->get('location')" />
            </div>

            <!-- Region -->
            <div>
                <x-input-label for="region" :value="__('Region/Hometown')" />
                <x-text-input id="region" name="region" type="text" class="mt-1 block w-full dark:bg-gray-800 dark:border-gray-700 dark:text-white" :value="old('region', $user->region)" autocomplete="region" placeholder="e.g., Central, Western, Buganda" />
                <x-input-error class="mt-2" :messages="$errors->get('region')" />
            </div>

            <!-- Phone -->
            <div>
                <x-input-label for="phone" :value="__('Phone Number')" />
                <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full dark:bg-gray-800 dark:border-gray-700 dark:text-white" :value="old('phone', $user->phone)" autocomplete="tel" placeholder="e.g., +256 700 123456" />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>

            <!-- Academic Information Section -->
            <div class="md:col-span-2">
                <h3 class="text-md font-medium text-gray-900 dark:text-white mb-4 mt-4">{{ __('Academic Information') }}</h3>
            </div>

            <!-- Role -->
            <div>
                <x-input-label for="role" :value="__('Role in University')" />
                <select id="role" name="role" class="mt-1 block w-full dark:bg-gray-800 dark:border-gray-700 dark:text-white border-gray-300 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Select your role</option>
                    <option value="student" {{ old('role', $user->role) == 'student' ? 'selected' : '' }}>Student</option>
                    <option value="lecturer" {{ old('role', $user->role) == 'lecturer' ? 'selected' : '' }}>Lecturer</option>
                    <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>Staff</option>
                    <option value="alumni" {{ old('role', $user->role) == 'alumni' ? 'selected' : '' }}>Alumni</option>
                    <option value="industry_partner" {{ old('role', $user->role) == 'industry_partner' ? 'selected' : '' }}>Industry Partner</option>
                    <option value="other" {{ old('role', $user->role) == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('role')" />
            </div>

            <!-- Course -->
            <div>
                <x-input-label for="course" :value="__('Course of Study')" />
                <x-text-input id="course" name="course" type="text" class="mt-1 block w-full dark:bg-gray-800 dark:border-gray-700 dark:text-white" :value="old('course', $user->course)" autocomplete="course" placeholder="e.g., Computer Science, Medicine" />
                <x-input-error class="mt-2" :messages="$errors->get('course')" />
            </div>

            <!-- Education Level -->
            <div>
                <x-input-label for="education_level" :value="__('Current Education Level')" />
                <select id="education_level" name="education_level" class="mt-1 block w-full dark:bg-gray-800 dark:border-gray-700 dark:text-white border-gray-300 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" autocomplete="education-level">
                    <option value="">Select your level</option>
                    <option value="Certificate" {{ old('education_level', $user->education_level) == 'Certificate' ? 'selected' : '' }}>Certificate</option>
                    <option value="Diploma" {{ old('education_level', $user->education_level) == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                    <option value="Undergraduate" {{ old('education_level', $user->education_level) == 'Undergraduate' ? 'selected' : '' }}>Undergraduate</option>
                    <option value="Postgraduate" {{ old('education_level', $user->education_level) == 'Postgraduate' ? 'selected' : '' }}>Postgraduate</option>
                    <option value="PhD" {{ old('education_level', $user->education_level) == 'PhD' ? 'selected' : '' }}>PhD</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('education_level')" />
            </div>

            <!-- Year of Study -->
            <div>
                <x-input-label for="year_of_study" :value="__('Year of Study')" />
                <select id="year_of_study" name="year_of_study" class="mt-1 block w-full dark:bg-gray-800 dark:border-gray-700 dark:text-white border-gray-300 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Select year</option>
                    <option value="1" {{ old('year_of_study', $user->year_of_study) == '1' ? 'selected' : '' }}>Year 1</option>
                    <option value="2" {{ old('year_of_study', $user->year_of_study) == '2' ? 'selected' : '' }}>Year 2</option>
                    <option value="3" {{ old('year_of_study', $user->year_of_study) == '3' ? 'selected' : '' }}>Year 3</option>
                    <option value="4" {{ old('year_of_study', $user->year_of_study) == '4' ? 'selected' : '' }}>Year 4</option>
                    <option value="5+" {{ old('year_of_study', $user->year_of_study) == '5+' ? 'selected' : '' }}>Year 5+</option>
                    <option value="alumni" {{ old('year_of_study', $user->year_of_study) == 'alumni' ? 'selected' : '' }}>Alumni</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('year_of_study')" />
            </div>

            <!-- Semester -->
            <div>
                <x-input-label for="semester" :value="__('Current Semester')" />
                <select id="semester" name="semester" class="mt-1 block w-full dark:bg-gray-800 dark:border-gray-700 dark:text-white border-gray-300 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Select semester</option>
                    <option value="1" {{ old('semester', $user->semester) == '1' ? 'selected' : '' }}>Semester 1</option>
                    <option value="2" {{ old('semester', $user->semester) == '2' ? 'selected' : '' }}>Semester 2</option>
                    <option value="summer" {{ old('semester', $user->semester) == 'summer' ? 'selected' : '' }}>Summer</option>
                    <option value="break" {{ old('semester', $user->semester) == 'break' ? 'selected' : '' }}>Break</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('semester')" />
            </div>

            <!-- Professional Information Section -->
            <div class="md:col-span-2">
                <h3 class="text-md font-medium text-gray-900 dark:text-white mb-4 mt-4">{{ __('Professional Information') }}</h3>
            </div>

            <!-- Employment Status -->
            <div>
                <x-input-label for="employment_status" :value="__('Employment Status')" />
                <select id="employment_status" name="employment_status" class="mt-1 block w-full dark:bg-gray-800 dark:border-gray-700 dark:text-white border-gray-300 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" autocomplete="employment-status">
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

            <!-- Looking For -->
            <div>
                <x-input-label for="looking_for" :value="__('Looking For')" />
                <select id="looking_for" name="looking_for" class="mt-1 block w-full dark:bg-gray-800 dark:border-gray-700 dark:text-white border-gray-300 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">What are you looking for?</option>
                    <option value="employment" {{ old('looking_for', $user->looking_for) == 'employment' ? 'selected' : '' }}>Employment</option>
                    <option value="internship" {{ old('looking_for', $user->looking_for) == 'internship' ? 'selected' : '' }}>Internship</option>
                    <option value="mentorship" {{ old('looking_for', $user->looking_for) == 'mentorship' ? 'selected' : '' }}>Mentorship</option>
                    <option value="collaboration" {{ old('looking_for', $user->looking_for) == 'collaboration' ? 'selected' : '' }}>Collaboration</option>
                    <option value="study_group" {{ old('looking_for', $user->looking_for) == 'study_group' ? 'selected' : '' }}>Study Group</option>
                    <option value="networking" {{ old('looking_for', $user->looking_for) == 'networking' ? 'selected' : '' }}>Networking</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('looking_for')" />
            </div>

            <!-- Skills & Interests Section -->
            <div class="md:col-span-2">
                <h3 class="text-md font-medium text-gray-900 dark:text-white mb-4 mt-4">{{ __('Skills & Interests') }}</h3>
            </div>

            <!-- Skills -->
            <div class="md:col-span-2">
                <x-input-label for="skills" :value="__('Skills (comma separated)')" />
                <x-text-input id="skills" name="skills" type="text" class="mt-1 block w-full dark:bg-gray-800 dark:border-gray-700 dark:text-white" :value="old('skills', $user->skills)" autocomplete="skills" placeholder="e.g., PHP, JavaScript, Project Management, Public Speaking" />
                <x-input-error class="mt-2" :messages="$errors->get('skills')" />
            </div>

            <!-- Interests -->
            <div class="md:col-span-2">
                <x-input-label for="interests" :value="__('Interests/Hobbies (comma separated)')" />
                <x-text-input id="interests" name="interests" type="text" class="mt-1 block w-full dark:bg-gray-800 dark:border-gray-700 dark:text-white" :value="old('interests', $user->interests)" autocomplete="interests" placeholder="e.g., Programming, Football, Music, Entrepreneurship" />
                <x-input-error class="mt-2" :messages="$errors->get('interests')" />
            </div>

            <!-- Schools -->
            <div class="md:col-span-2">
                <x-input-label for="schools" :value="__('Schools Attended (comma separated)')" />
                <x-text-input id="schools" name="schools" type="text" class="mt-1 block w-full dark:bg-gray-800 dark:border-gray-700 dark:text-white" :value="old('schools', $user->schools)" autocomplete="schools" placeholder="e.g., Makerere University, St. Mary's College Kisubi, Kampala Parents School" />
                <x-input-error class="mt-2" :messages="$errors->get('schools')" />
            </div>

            <!-- Talents/Leadership -->
            <div class="md:col-span-2">
                <x-input-label for="talents" :value="__('Talents & Leadership Experience')" />
                <textarea id="talents" name="talents" rows="3" class="mt-1 block w-full dark:bg-gray-800 dark:border-gray-700 dark:text-white border-gray-300 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" autocomplete="talents" placeholder="Describe your talents, leadership roles, extracurricular activities, or achievements...">{{ old('talents', $user->talents) }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('talents')" />
            </div>

            <!-- Social Links -->
            <div class="md:col-span-2">
                <x-input-label for="social_links" :value="__('Social Media Links (JSON format)')" />
                <textarea id="social_links" name="social_links" rows="2" class="mt-1 block w-full dark:bg-gray-800 dark:border-gray-700 dark:text-white border-gray-300 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm font-mono text-sm" placeholder='{"linkedin": "https://linkedin.com/in/username", "twitter": "https://twitter.com/username", "github": "https://github.com/username"}'>{{ old('social_links', $user->social_links ? json_encode($user->social_links, JSON_PRETTY_PRINT) : '') }}</textarea>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Enter social media links in JSON format</p>
                <x-input-error class="mt-2" :messages="$errors->get('social_links')" />
            </div>
        </div>

        <div class="flex items-center gap-4 mt-6">
            <x-primary-button>{{ __('Save Profile Information') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-600 dark:text-gray-400">{{ __('Profile updated successfully!') }}</p>
            @endif
        </div>
    </form>
</section>