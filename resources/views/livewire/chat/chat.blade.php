<!-- @section('style')

<link href="{{ asset('assets/emojionearea/emojionearea.min.css') }}" rel="stylesheet"/>

@endsection -->

<div 
 x-data="{
    height:0,
    conversationElement: document.getElementById('conversation'),
 }"

 x-init="
    height=conversationElement.scrollHeight;
    $nextTick(()=> conversationElement.scrollTop=height);

    Echo.private('users.{{auth()->user()->id}}')
    .notification((notification) => {

        if(
            notification['type']=='App\\Notifications\\MessageSentNotification' &&
            notification['conversation_id']=={{$conversation->id}}
        )
        {

            $wire.listenBroadcastedMessage(notification);
        }
     
    });
 "

 @scroll-bottom.window="
 $nextTick(()=> conversationElement.scrollTop= conversationElement.scrollHeight );
 
 "


class=" w-full overflow-hidden  h-full ">

    <div class="  border-r   flex flex-col overflow-y-scroll grow  h-full">
        {{--------------}}
        {{-----Header---}}
        {{--------------}}

        <header   class="w-full sticky inset-x-0 flex pb-[5px] pt-[7px] top-0 z-10 border-b"
                 style="background: linear-gradient(90deg,#000000 0%, #C00000 50%, #006400 100%); color: #fff;">
>

            <div class="  flex  w-full items-center   px-2   lg:px-4 gap-2 md:gap-5 ">
                {{-- Return --}}
                <a href="{{route('chat')}}" class=" shrink-0 lg:hidden  dark:text-white" id="chatReturn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </a>

                {{--Avatar --}}
                <div class=" shrink-0 ">
                    <a href="{{route('profile.home',$receiver->username)}}">
                        <x-avatar wire:ignore  class="h-8 w-8 lg:w-10 lg:h-10 " />
                    </a>
                 
                </div>
                <a href="{{route('profile.home',$receiver->username)}}">
                  <h6 class="font-bold truncate"> {{$receiver->name}} </h6>
                </a>

                {{-- Actions --}}
                <div class="flex gap-4 items-center ml-auto">
                    {{-- Phone --}}
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.9" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                          </svg>
                    </span>

                    {{-- video --}}
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" />
                          </svg>                          
                    </span>

                    {{-- Info --}}
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.9" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                          </svg>
                          
                    </span>

                </div>

            </div>

        </header>

        {{--------------}}
        {{---Messages---}}
        {{--------------}}
        <main 

        @scroll="
         scrollTop= $el.scrollTop;
         console.log(scrollTop);
         if(scrollTop<=0){
            @this.dispatch('loadMore');
         }
        
        "

        @update-height.window="
            $nextTick(()=>{

                newHeight=$el.scrollHeight;

                oldHeight= height;

                $el.scrollTop=newHeight-oldHeight;

                height=newHeight;



            });
        
        "

        id="conversation"
        class="flex flex-col   gap-5   p-2.5  overflow-y-auto flex-grow  overscroll-contain overflow-x-hidden w-full my-auto ">

            <!--Message-->
           
            @foreach ($loadedMessages as $message)

            @php
                $belongsToAuth= $message->sender_id==auth()->id();
            @endphp
                
                <div @class([
                    'max-w-[85%] md:max-w-[78%] flex  w-auto  gap-2 relative mt-2',
                    'ml-auto'=>$belongsToAuth// SET true if belongs to auth
                     ])>
                    {{-- Avatar --}}
                    <div @class([
                        'shrink-0',
                         'invisible'=>$belongsToAuth //SET true if belongs to auth
                        ])>
                        <x-avatar class="h-7 w-7 lg:w-11 lg:h-11 " src="https://randomuser.me/api/portraits/men/{{ rand(1, 99) }}.jpg" />
                    </div>

                    {{-- message body --}}
                    <div @class(['flex flex-wrap text-[15px] border border-gray-200/40 rounded-xl p-2.5 flex flex-col',
                                 'bg-green-700 text-white'=> $belongsToAuth,
                                 'bg-white text-black'=> ! $belongsToAuth,
                                ])>

                        @if(!empty($message->body))
                        <p class="whitespace-normal break-words text-sm md:text-base tracking-wide lg:tracking-normal">
                            {{$message->body}}
                        </p>
                        @endif

                        @if($message->media && $message->media->count())
                            @foreach($message->media as $file)
                                @switch($file->mime)
                                    @case('image')
                                        <img src="{{ $file->url }}" class="mt-2 max-h-72 rounded-md border object-contain" />
                                        @break
                                    @case('audio')
                                        <audio controls class="mt-2 w-64">
                                            <source src="{{ $file->url }}" />
                                        </audio>
                                        @break
                                    @default
                                        <a href="{{ $file->url }}" target="_blank" class="mt-2 text-blue-600 underline">Download attachment</a>
                                @endswitch
                            @endforeach
                        @endif
                   
                    </div>
>

                </div>
 
                @endforeach

        </main>
        

        {{--------------------}}
        {{--Send Message -----}}
        {{--------------------}}

        <footer class="shrink-0 z-10 inset-x-0 py-2" style="background: linear-gradient(90deg,#000000 0%, #C00000 50%, #006400 100%);">
            <div class="border px-3 py-1.5 rounded-3xl grid grid-cols-12 gap-4 items-center overflow-hidden w-full max-w-[95%] mx-auto bg-white/95">
               
                {{-- Emoji icon + picker (emojionearea already included in assets) --}}
                <span class="col-span-1">
                    <label for="chatMedia" class="cursor-pointer" title="Attach image/audio">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-gray-700">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z" />
                        </svg>
                    </label>
                </span>
                
                <form wire:submit='sendMessage' method="POST" autocapitalize="off" class="col-span-11 md:col-span-9">
                    @csrf
                    <div class="grid grid-cols-12 gap-2">
                        <input autocomplete="off" wire:model='body' id="sendMessage"
                            autofocus type="text" name="message"
                            placeholder="Message"
                            class="col-span-9 border-0 outline-0 focus:border-0 focus:ring-0 hover:ring-0 rounded-lg dark:text-gray-800 focus:outline-none" />

                        <input type="file" id="chatMedia" class="hidden" wire:model="media" accept="image/*,audio/*" />

                        <div class="col-span-3 flex items-center justify-end gap-3">
                            {{-- Record audio (use native media recorder via browser) --}}
                            <button type="button" x-data x-on:click="$dispatch('start-audio-record')" title="Record audio" class="text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.9" stroke="currentColor" class="w-7 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 006-6v-1.5m-6 7.5a6 6 0 01-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 01-3-3V4.5a3 3 0 116 0v8.25a3 3 0 01-3 3z" />
                                </svg>
                            </button>

                            {{-- Send --}}
                            <button type="submit" class="text-blue-600 font-bold">Send</button>
                        </div>
                    </div>
                </form>

                {{-- Audio recording script (simple) --}}
                <script>
                    document.addEventListener('start-audio-record', async () => {
                        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) return alert('Audio recording not supported');
                        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                        const recorder = new MediaRecorder(stream);
                        const chunks = [];
                        recorder.ondataavailable = e => chunks.push(e.data);
                        recorder.onstop = async () => {
                            const blob = new Blob(chunks, { type: 'audio/webm' });
                            const file = new File([blob], 'recording.webm', { type: 'audio/webm' });
                            // Bridge to Livewire: use a hidden file input via DataTransfer
                            const dt = new DataTransfer();
                            dt.items.add(file);
                            const input = document.getElementById('chatMedia');
                            input.files = dt.files;
                            input.dispatchEvent(new Event('input', { bubbles: true }));
                        };
                        recorder.start();
                        setTimeout(() => recorder.stop(), 5000); // simple 5s recording
                    });
                </script>

             </div>
             @error('body') <p class="text-red-600"> {{$message}} </p> @enderror

        </footer>
>
        
    </div>

</div>

<!-- @section('script')

<script src="https://code.jquery.com/jquery-3.x.x.min.js"></script>

<script src="{{ asset('assets/emojionearea/emojionearea.min.js') }}"></script>

<script type="text/javascript">
    $(".emojionearea").emojioneArea();
</script>
@endsection -->