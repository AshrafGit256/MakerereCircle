<x-profile-layout :user="$user">

    @if($posts->count() > 0)
    <ul class="grid grid-cols-3 gap-3">


        @foreach ($posts as $post)

        @php
        $cover= $post->media()->first();
        @endphp


        <li
            wire:click="$dispatch('openModal', {component: 'post.view.modal', arguments: {post: {{ $post->id }} }})"
            class="h-32 md:h-72 w-full cursor-pointer border rounded">


            @switch($cover?->mime)
            @case('video')

            <x-video source="{{$cover->url}}" />
            @break
            @case('image')

            <img src="{{$cover->url}}" alt="image" class="h-full w-full object-cover">

            @break
            @default

            @endswitch

        </li>

        @endforeach
    </ul>
    @else
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
        </svg>
        <h3 class="mt-2 text-lg font-medium text-gray-900">No posts yet</h3>
        <p class="mt-1 text-gray-500">This user hasn't posted anything yet.</p>
    </div>
    @endif

    </ul>
</x-profile-layout>