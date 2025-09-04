<div class="max-w-3xl mx-auto bg-white border rounded-lg p-6 shadow-md mb-6">
    {{-- In work, do what you enjoy. --}}


    {{-- header --}}

    <header class="flex items-center gap-3">
        @if($post->type === 'live')
            <div class="absolute top-2 left-2 z-10 bg-red-600 text-white px-2 py-1 rounded text-xs font-bold animate-pulse">
                🔴 LIVE
            </div>
        @endif

        <a href="{{ route('profile.home', $post->user->username) }}">
            <x-avatar src="{{ $post->user->getImage() }}" class="h-12 w-12" />
        </a>

        <div class="grid grid-cols-7 w-full gap-2">

            <div class="col-span-5">
                <h5 class="font-semibold truncate text-sm">
                    <a href="{{ route('profile.home', $post->user->username) }}" class="hover:underline">
                        {{$post->user->name}}
                        <x-verified-badge :user="$post->user" size="sm" />
                    </a>
                </h5>

                @if($post->group)
                <p class="text-xs text-gray-500">
                    Posted in
                    <a href="{{ route('groups.show', $post->group->slug) }}" class="text-blue-600 hover:underline">
                        {{ $post->group->name }}
                    </a>
                </p>
                @endif
            </div>



            <div class="col-span-2 flex text-right justify-end">

                <button class="text-gray-500 ml-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                        <path
                            d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                    </svg>
                </button>
            </div>


        </div>


    </header>

    @php
    $description = preg_replace_callback(
    '/#(\w+)/',
    function ($matches) {
    $hashtag = $matches[1];
    $url = url('/tags/' . $hashtag); // You can customize the route
    return '<a href="' . $url . '" class="text-blue-600 hover:underline">#' . $hashtag . '</a>';
    },
    e($post->description)
    );
    @endphp


    <div class="text-base text-gray-700 leading-relaxed tracking-wide font-normal px-4 py-2 rounded-lg">
        <p>
            {!! $description !!}
        </p>
    </div>

    {{-- Poll Display --}}
    @if($post->poll)
        <div class="bg-white border rounded-lg p-4 my-2">
            <h3 class="font-semibold text-lg mb-3">{{ $post->poll->question }}</h3>

            @if($post->poll->isExpired())
                <div class="text-gray-500 text-sm mb-3">Poll ended</div>
            @elseif($post->poll->ends_at)
                <div class="text-gray-500 text-sm mb-3">
                    Ends {{ $post->poll->ends_at->diffForHumans() }}
                </div>
            @endif

            <div class="space-y-2">
                @foreach($post->poll->options as $option)
                    @php
                        $percentage = $post->poll->total_votes > 0 ? ($option->votes_count / $post->poll->total_votes) * 100 : 0;
                        $hasUserVoted = $option->hasUserVoted(auth()->id());
                    @endphp

                    <div class="relative">
                        <button
                            wire:click="voteOnPollOption({{ $option->id }})"
                            @disabled($post->poll->isExpired() || $post->poll->hasUserVoted(auth()->id()) || !$post->poll->multiple_choice && $post->poll->hasUserVoted(auth()->id()))
                            class="w-full text-left p-3 border rounded-lg hover:bg-gray-50 disabled:hover:bg-white transition-colors {{ $hasUserVoted ? 'bg-blue-50 border-blue-200' : '' }}"
                        >
                            <div class="flex items-center justify-between">
                                <span class="flex items-center gap-2">
                                    @if($hasUserVoted)
                                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                    {{ $option->option_text }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    {{ $option->votes_count }} vote{{ $option->votes_count !== 1 ? 's' : '' }}
                                    @if($post->poll->total_votes > 0)
                                        ({{ number_format($percentage, 1) }}%)
                                    @endif
                                </span>
                            </div>
                        </button>

                        @if($post->poll->total_votes > 0)
                            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gray-200 rounded-b-lg">
                                <div class="h-full bg-blue-600 rounded-b-lg transition-all duration-300"
                                     style="width: {{ $percentage }}%"></div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            @if($post->poll->total_votes > 0)
                <div class="mt-3 text-sm text-gray-500">
                    {{ $post->poll->total_votes }} total vote{{ $post->poll->total_votes !== 1 ? 's' : '' }}
                </div>
            @endif
        </div>
    @endif

    {{-- main --}}
    <main class="block rounded-lg">
        <div class=" my-2">
            <!-- Swiper container -->
            <div x-init="
            new Swiper($el, {
                modules: [Navigation, Pagination],
                loop: true,
                pagination: {
                    el: '.swiper-pagination',
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        "
                class=" relative w-full bg-white rounded-lg shadow border overflow-hidden">

                {{-- Ribbons --}}
                @if ($post->found == 0 && $post->lost == 1)
                <div class="absolute top-0 left-0 z-20">
                    <div class="w-32 bg-gray-500 text-white text-center font-bold absolute top-4 left-[-40px] transform -rotate-45 shadow">
                        AD
                    </div>
                </div>
                @elseif ($post->found == 1 && $post->lost == 0)
                <div class="absolute top-0 left-0 z-20">
                    <div class="w-36 bg-green-600 text-white text-center font-bold absolute top-4 left-[-50px] transform -rotate-45 shadow">
                        NB
                    </div>
                </div>
                @endif

                <!-- Slides -->
                <ul x-cloak class="swiper-wrapper">
                    @if($post->video_url)
                        <li class="swiper-slide flex justify-center items-center bg-gray-100 max-h-[80vh]">
                            @php
                                // Extract YouTube video ID from URL
                                $videoId = '';
                                if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $post->video_url, $matches)) {
                                    $videoId = $matches[1];
                                }
                            @endphp
                            @if($videoId)
                                <div class="relative w-full" style="padding-bottom: 56.25%;">
                                    <iframe
                                        src="https://www.youtube.com/embed/{{ $videoId }}"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen
                                        class="absolute top-0 left-0 w-full h-full rounded-md">
                                    </iframe>
                                </div>
                            @endif
                        </li>
                    @else
                        @foreach ($post->media as $file)
                        <li class="swiper-slide flex justify-center items-center bg-gray-100 max-h-[80vh]">
                            @switch($file->mime)
                            @case('video')
                            <x-video source="{{ $file->url }}" class="max-h-[75vh] w-auto object-contain" />
                            @break

                            @case('image')
                            <img src="{{ $file->url }}" alt="" class="max-h-[75vh] w-auto object-contain rounded-md">
                            @break
                            @endswitch
                        </li>
                        @endforeach
                    @endif
                </ul>

                <!-- Pagination -->
                <div class="swiper-pagination"></div>

                @if (count($post->media) > 1)
                <!-- Navigation buttons -->
                <div class="swiper-button-prev absolute top-1/2 left-2 z-10 transform -translate-y-1/2">
                    <div class="bg-white/90 border p-1 rounded-full text-gray-900 shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" fill="none">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                        </svg>
                    </div>
                </div>

                <div class="swiper-button-next absolute top-1/2 right-2 z-10 transform -translate-y-1/2">
                    <div class="bg-white/90 border p-1 rounded-full text-gray-900 shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" fill="none">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </div>
                </div>
                @endif

                <!-- Optional: Scrollbar (if enabled in Swiper config) -->
                <div class="swiper-scrollbar"></div>
            </div>

            @php
            $imageMedia = $post->media->where('mime', 'image')->values();
            $optionLabels = range('A','Z');
            @endphp
            @if ($imageMedia->count() > 1)
            <div class="mt-3 bg-white border rounded-md p-3">
                <h6 class="font-semibold text-sm mb-2">Vote your favorite</h6>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    @foreach ($imageMedia as $idx => $media)
                    <button
                        wire:click="voteOnMedia({{ $media->id }})"
                        class="flex items-center gap-3 p-2 border rounded-md hover:bg-gray-50">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-600 text-white text-xs font-bold">
                            {{ $optionLabels[$idx] }}
                        </span>
                        <img src="{{ $media->url }}" class="w-10 h-10 object-cover rounded" />
                        <span class="text-sm text-gray-700">Option {{ $optionLabels[$idx] }}</span>
                        <span class="ml-auto text-xs text-gray-500">{{ $media->likers()->count() }} votes</span>
                    </button>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </main>





    {{-- footer --}}
    <footer>

        {{-- actions --}}
        <div class="flex gap-5 items-center my-4 px-2">

            {{-- heart --}}

            @if ($post->isLikedBy(auth()->user()))
            <button wire:click='togglePostLike()'>

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                    class="w-6 h-6 text-rose-500">
                    <path
                        d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                </svg>
            </button>

            @else
            <button wire:click='togglePostLike()'>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.9"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                </svg>

            </button>
            @endif

            {{-- likes and views --}}
            @if ($post->totalLikers>0 && !$post->hide_like_view)
            <p class="font-bold text-sm text-gray-700 ml-1">{{$post->totalLikers}} {{$post->totalLikers>1? 'likes':'like'}}</p>
            @endif



            @if ($post->allow_commenting)

            {{-- comment --}}
            <button
                onclick="Livewire.dispatch('openModal',{component:'post.view.modal',arguments:{'post':{{$post->id}}}})">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 01-.923 1.785A5.969 5.969 0 006 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337z" />
                </svg>

            </button>
            @endif

            {{-- Comments --}}
            @if ($post->allow_commenting && $post->comments->count() > 0)
            <p class="font-bold text-sm text-gray-700 ml-1"> {{$post->comments->count()}} {{$post->comments->count()>1? 'comments':'comment'}}</p>
            @endif

            {{-- repost --}}
            <button wire:click="repost" title="Repost">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25 3.75 12l3.75 3.75m-3.75-3.75H16.5a3 3 0 0 1 3 3V21M16.5 15.75 20.25 12 16.5 8.25m3.75 3.75H7.5a3 3 0 0 1-3-3V3" />
                </svg>
            </button>

            {{-- forward --}}
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-send w-5 h-5" viewBox="0 0 16 16">
                    <path
                        d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z" />
                </svg>
            </span>

            {{-- Bookmark/favorites --}}
            <span class="ml-auto">

                @if ($post->hasBeenFavoritedBy(auth()->user()))

                <button wire:click='toggleFavorite()'>

                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                        <path fill-rule="evenodd"
                            d="M6.32 2.577a49.255 49.255 0 0111.36 0c1.497.174 2.57 1.46 2.57 2.93V21a.75.75 0 01-1.085.67L12 18.089l-7.165 3.583A.75.75 0 013.75 21V5.507c0-1.47 1.073-2.756 2.57-2.93z"
                            clip-rule="evenodd" />
                    </svg>
                </button>


                @else
                <button wire:click='toggleFavorite()'>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z" />
                    </svg>
                </button>
                @endif


            </span>

        </div>



        {{-- name and comment --}}



        @if ($post->allow_commenting)
        @auth
        {{-- show comments for auth --}}
        <ul class="my-3 px-2">
            @foreach ($post->comments()->where('user_id',auth()->id())->get() as $comment )


            <li class="grid grid-cols-12 text-sm items-center">
                <span class="font-bold col-span-3 mb-auto">{{auth()->user()->name}} </span>
                <span class="col-span-8">{{$comment->body}} </span>
                <button class="col-span-1 mb-auto flex justify-end pr-px">
                    {{-- heart --}}
                    @if ($comment->isLikedBy(auth()->user()))
                    <span wire:click='toggleCommentLike({{$comment->id}})'>

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="w-3 h-3 text-rose-500">
                            <path
                                d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                        </svg>
                    </span>

                    @else
                    <span wire:click='toggleCommentLike({{$comment->id}})'>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.9"
                            stroke="currentColor" class="w-3 h-3">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>

                    </span>
                    @endif
                </button>
            </li>
            @endforeach
        </ul>
        @endauth

        {{-- leave comment --}}
        <form wire:key="comment-form-{{ $post->id }}" @submit.prevent="$wire.addComment()" x-data="{body:@entangle('body')}"
            class="grid grid-cols-12 items-center w-full px-2">
            @csrf

            <input x-model="body" type="text" placeholder=" Leave a comment "
                class="border-0 bg-transparent col-span-10 placeholder:text-sm outline-none focus:outline-none px-0 rounded-lg hover:ring-0 focus:ring-0 w-full">
            <div class="col-span-1 ml-auto flex justify-end text-right">
                <button type="submit" x-cloak x-show="body.length >0"
                    class="text-sm font-semibold flex justify-end text-blue-500">
                    Post
                </button>
            </div>

            <span class="col-span-1 ml-auto">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm-.375 0h.008v.015h-.008V9.75zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75zm-.375 0h.008v.015h-.008V9.75z" />
                </svg>
            </span>

        </form>
        @endif


    </footer>
</div>