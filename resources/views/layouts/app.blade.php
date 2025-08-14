<!DOCTYPE html>
@php
    $uiFont = session('ui.font', 'sans');
    $uiTheme = session('ui.theme', 'light');
    $uiAccent = session('ui.accent', '#0ea5e9');
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ $uiTheme === 'dark' ? 'dark' : '' }}" data-theme="{{ $uiTheme }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Makerere Circle') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
<!-- 
        <style>
            :root {
                --accent: {{ $uiAccent }};
            }
            [x-cloak]{
                display: none !important;
            }
            /* Accent helpers (lightweight) */
            .accent-bg{ background-color: var(--accent) !important; }
            .accent-text{ color: var(--accent) !important; }
            .accent-border{ border-color: var(--accent) !important; }

            /* Global dark mode overrides */
            html.dark body { background-color: #0f172a; color: #e5e7eb; }
            html.dark .bg-white { background-color: #111827 !important; color: #e5e7eb !important; }
            html.dark .border { border-color: #374151 !important; }
            html.dark .text-gray-700 { color: #d1d5db !important; }
            html.dark .text-gray-600 { color: #d1d5db !important; }
            html.dark .text-gray-500 { color: #9ca3af !important; }
        </style> -->
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-{{ $uiFont }}">

        <div class="drawer lg:drawer-open">
            <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
            <div class="drawer-content items-center justify-center">
              <!-- Page content here -->
              {{-- <label for="my-drawer-2" class="btn btn-primary drawer-button lg:hidden">Open drawer</label> --}}
                {{$slot}}
            </div> 
            <div class="drawer-side overflow-visible z-10">
              <label for="my-drawer-2" class="drawer-overlay"></label> 
            
              {{-- @include('layouts.sidebar') --}}
              <livewire:components.sidebar />
            
            </div>
          </div>
  
          @livewire('wire-elements-modal')

    </body>

    <script src="//unpkg.com/alpinejs" defer></script>
   `` <!-- <script>
        // Apply system theme when selected
        (function(){
            const theme = document.documentElement.getAttribute('data-theme');
            const apply = () => {
                if(theme === 'system'){
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    document.documentElement.classList.toggle('dark', prefersDark);
                }
            };
            apply();
            if(theme === 'system'){
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', apply);
            }
        })();
    </script>`` -->
</html>
