<div class="bg-white lg:h-[500px] flex flex-col border gap-y-4 px-5">

    {{-- Top header --}}
    <header class="w-full py-2 border-b">
        <div class="flex justify-between">

            <button wire:click="$dispatch('closeModal')"  class="font-bold">
                X
            </button>

            <div class="text-lg font-bold">
                Create new post
                @if(empty(trim($description ?? '')) && count($media ?? [])==0 && empty(trim($video_url ?? '')) && !$has_poll && !$is_fundraiser)
                    <span class="text-xs text-gray-500 block">(Add a caption, upload media, paste a YouTube URL, create a poll, or start a fundraiser to enable sharing)</span>
                @else
                    <span class="text-xs text-green-600 block">✓ Ready to share! ({{ !empty(trim($description ?? '')) ? 'Caption' : '' }} {{ count($media ?? [])>0 ? 'Media' : '' }} {{ !empty(trim($video_url ?? '')) ? 'YouTube' : '' }} {{ $has_poll ? 'Poll' : '' }} {{ $is_fundraiser ? 'Fundraiser' : '' }})</span>
                @endif
            </div>


            <button @disabled(empty(trim($description ?? '')) && count($media ?? [])==0 && empty(trim($video_url ?? '')) && !$has_poll && !$is_fundraiser) wire:loading.attr='disabled' wire:click='submit' class="font-bold disabled:opacity-50 disabled:cursor-not-allowed disabled:text-gray-400 {{ empty(trim($description ?? '')) && count($media ?? [])==0 && empty(trim($video_url ?? '')) && !$has_poll && !$is_fundraiser ? 'text-gray-400' : 'text-blue-500' }}">

                Share

            </button>


        </div>


    </header>


    <main class="grid grid-cols-12 gap-3 h-full w-full overflow-hidden">


        {{-- Media --}}
        <aside class=" lg:col-span-7  m-auto items-center w-full overflow-scroll">

            @if (count($media)==0 && empty($video_url))
                    {{-- trigger button --}}
            <label for="customFileInput"  class=" m-auto max-w-fit flex-col flex gap-3 cursor-pointer">
                <input wire:model.live="media" type="file" multiple accept=".jpg,.png,.jpeg,.mp4,.mov" id="customFileInput" type="text" class="sr-only">

                <span class="m-auto">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-14 h-14">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                      </svg>


                </span>

                <span class="bg-blue-500 text-white text-sm rounded-lg p-2 px-4">
                    Upload files from computer
                </span>


            </label>
            @elseif (count($media) > 0)

            {{-- Show when file count is > 0 --}}
            <div class=" flex overflow-x-scroll w-[500px] h-96 snap-x snap-mandatory gap-2 px-2">



                @foreach ($media as $key=> $file)
                <div class="w-full h-full shrink-0 snap-always snap-center">
                    @if (strpos($file->getMimeType(),'image')!==false)
                    <img src="{{$file->temporaryUrl()}}" alt="" class="w-full h-full object-contain">

                    @elseif (strpos($file->getMimeType(),'video')!==false)
                    <x-video :source="$file->temporaryUrl()" />

                    @endif

                </div>
                   @endforeach




           </div>

            @elseif (!empty($video_url))
                {{-- YouTube video preview --}}
                <div class="w-full h-full flex justify-center items-center">
                    @php
                        // Extract YouTube video ID from URL
                        $videoId = '';
                        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video_url, $matches)) {
                            $videoId = $matches[1];
                        }
                    @endphp
                    @if($videoId)
                        <iframe width="100%" height="315" src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    @else
                        <div class="text-center text-gray-500">
                            <p>Invalid YouTube URL</p>
                            <p>Please enter a valid YouTube video URL</p>
                        </div>
                    @endif
                </div>
            @endif


         </aside>

        {{-- Details --}}
        <aside class=" lg:col-span-5  h-full border-l p-3 flex gap-4 flex-col overflow-hidden overflow-y-scroll">


            {{-- Auther --}}
            <div class="flex items-center gap-2">
                <x-avatar class="w-9 h-9" />
                <h5 class="font-bold">
                    {{ auth()->user()->name }}
                    <x-verified-badge :user="auth()->user()" size="sm" />
                </h5>
            </div>


            {{-- description --}}
            <div>
                <textarea
                wire:model.live="description"
                wire:keydown.debounce.100ms="updateButton"
                placeholder="Add a caption (required for text-only posts)"
                class="border-0 focus:border-0 px-0 w-full rounded-lg bg-white h-32 focus:outline-none focus:ring-0"
                name="" id="" cols="30" rows="10"></textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                @error('content')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- location --}}
            <div class=" w-full items-center">
                <input type="text"
                wire:model='location'
                placeholder="Add location"
                class="border-0 focus:border-0 px-0 w-full rounded-lg bg-white  focus:outline-none focus:ring-0"
                >
            </div>

            {{-- YouTube video URL --}}
            <div class=" w-full items-center">
                <input type="url"
                wire:model.live='video_url'
                wire:keydown.debounce.100ms="updateButton"
                placeholder="Add YouTube video URL (optional)"
                class="border-0 focus:border-0 px-0 w-full rounded-lg bg-white  focus:outline-none focus:ring-0"
                >
            </div>

            {{-- Poll Toggle --}}
            <div class="flex items-center gap-3">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" wire:model.live="has_poll" class="rounded">
                    <span class="text-sm text-gray-700">Add a poll</span>
                </label>
            </div>

            {{-- Fundraising Toggle --}}
            <div class="flex items-center gap-3">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" wire:model.live="is_fundraiser" class="rounded">
                    <span class="text-sm text-gray-700">Start a fundraiser</span>
                </label>
            </div>

            {{-- Poll Creation Section --}}
            @if($has_poll)
            <div class="bg-gray-50 p-4 rounded-lg space-y-4">
                {{-- Poll Question --}}
                <div>
                    <input type="text"
                           wire:model="poll_question"
                           placeholder="Ask a question..."
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           maxlength="255">
                    @error('poll_question')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Poll Options --}}
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">Poll options</label>
                    @foreach($poll_options as $index => $option)
                    <div class="flex items-center gap-2">
                        <input type="text"
                               wire:model="poll_options.{{ $index }}"
                               placeholder="Option {{ $index + 1 }}"
                               class="flex-1 border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               maxlength="100">
                        @if(count($poll_options) > 2)
                        <button type="button"
                                wire:click="removePollOption({{ $index }})"
                                class="text-red-500 hover:text-red-700 p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                        @endif
                    </div>
                    @endforeach

                    @if(count($poll_options) < 10)
                    <button type="button"
                            wire:click="addPollOption"
                            class="text-blue-500 hover:text-blue-700 text-sm flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add option
                    </button>
                    @endif

                    @error('poll_options')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Poll Settings --}}
                <div class="grid grid-cols-2 gap-4">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" wire:model="poll_multiple_choice" class="rounded">
                        <span class="text-sm text-gray-700">Multiple choice</span>
                    </label>

                    <div>
                        <select wire:model="poll_duration_hours"
                                class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="24">1 day</option>
                            <option value="72">3 days</option>
                            <option value="168">1 week</option>
                            <option value="">No limit</option>
                        </select>
                    </div>
                </div>
            </div>
            @endif

            {{-- Fundraising Creation Section --}}
            @if($is_fundraiser)
            <div class="bg-green-50 p-4 rounded-lg space-y-4 border border-green-200">
                <h4 class="font-semibold text-green-800 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                    </svg>
                    Fundraising Campaign
                </h4>

                {{-- Fundraiser Title --}}
                <div>
                    <input type="text"
                           wire:model="fundraiser_title"
                           placeholder="Fundraiser title..."
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                           maxlength="255">
                    @error('fundraiser_title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Target Amount & Category --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <input type="number"
                               wire:model="fundraiser_target_amount"
                               placeholder="Target amount (UGX)"
                               min="1000"
                               step="1000"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        @error('fundraiser_target_amount')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <select wire:model="fundraiser_category"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="medical">Medical Treatment</option>
                            <option value="education">Education Support</option>
                            <option value="disaster">Disaster Relief</option>
                            <option value="community">Community Project</option>
                            <option value="emergency">Emergency Relief</option>
                            <option value="business">Business Support</option>
                            <option value="other">Other Causes</option>
                        </select>
                        @error('fundraiser_category')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- End Date --}}
                <div>
                    <input type="date"
                           wire:model="fundraiser_end_date"
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <p class="text-sm text-gray-600 mt-1">Optional: Set an end date for your fundraiser</p>
                    @error('fundraiser_end_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Beneficiary Information --}}
                <div class="space-y-3">
                    <h5 class="font-medium text-gray-700">Beneficiary Information (Optional)</h5>

                    <input type="text"
                           wire:model="fundraiser_beneficiary_name"
                           placeholder="Beneficiary name"
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                           maxlength="255">

                    <textarea wire:model="fundraiser_beneficiary_story"
                              placeholder="Tell the beneficiary's story..."
                              rows="3"
                              class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                              maxlength="5000"></textarea>

                    <div class="grid grid-cols-2 gap-4">
                        <input type="tel"
                               wire:model="fundraiser_contact_phone"
                               placeholder="Contact phone"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                               maxlength="20">

                        <input type="email"
                               wire:model="fundraiser_contact_email"
                               placeholder="Contact email"
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
                               maxlength="255">
                    </div>
                </div>

                {{-- Fundraiser Images --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Campaign Images (Optional)</label>
                    <input type="file"
                           wire:model="fundraiser_images"
                           multiple
                           accept="image/*"
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <p class="text-sm text-gray-600 mt-1">Upload images related to your fundraiser (Max 5, 2MB each)</p>
                    @error('fundraiser_images.*')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                @if($fundraiser_images)
                    <div class="grid grid-cols-3 gap-2">
                        @foreach($fundraiser_images as $index => $image)
                            <div class="relative">
                                <img src="{{ $image->temporaryUrl() }}" alt="Fundraiser image" class="w-full h-20 object-cover rounded-lg">
                                <button type="button"
                                        wire:click="removeFundraiserImage({{ $index }})"
                                        class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">
                                    ×
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            @endif

            {{-- Lost or Found settings --}}
            <div class="">

                <h6 class="text-gray-500 font-medium text-base">Campus Vibe settings</h6>

                <ul>
                    <li>
                        <div class="flex items-center gap-3 justify-between">


                            <span>AD</span>

                            <label class="relative inline-flex items-center mb-5 cursor-pointer">
                                <input wire:model='lost' type="checkbox" value="" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>

                              </label>
                              
                        </div>


                    </li>
                    <li>
                        <div class="flex items-center gap-3 justify-between">


                            <span>NB</span>

                            <label class="relative inline-flex items-center mb-5 cursor-pointer">
                                <input wire:model='found' type="checkbox" value="" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            </label>
                              
                        </div>


                    </li>
                    
                </ul>

            </div>

            {{-- Advanced settings --}}
            <div class="">

                <h6 class="text-gray-500 font-medium text-base">Advanced settings</h6>

                <ul>
                    <li>
                        <div class="flex items-center gap-3 justify-between">


                            <span>Hide like and view counts on this post</span>

                            <label class="relative inline-flex items-center mb-5 cursor-pointer">
                                <input wire:model='hide_like_view' type="checkbox" value="" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>

                              </label>
                              
                        </div>


                    </li>
                    <li>
                        <div class="flex items-center gap-3 justify-between">


                            <span>Allow commenting </span>

                            <label class="relative inline-flex items-center mb-5 cursor-pointer">
                                <input wire:model='allow_commenting' type="checkbox" value="" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>

                              </label>
                              
                        </div>


                    </li>
                    
                </ul>

            </div>


        </aside>


    </main>
</div>
