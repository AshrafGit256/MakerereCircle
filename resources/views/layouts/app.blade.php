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
        </style>
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
    <script>
        // Global UI application: font, theme (including system), and accent color
        (function(){
            let systemListener = null;

            function applyTheme(theme) {
                document.documentElement.setAttribute('data-theme', theme);
                if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                } else if (theme === 'light') {
                    document.documentElement.classList.remove('dark');
                } else if (theme === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    document.documentElement.classList.toggle('dark', prefersDark);
                }
                if (systemListener) {
                    window.matchMedia('(prefers-color-scheme: dark)').removeEventListener('change', systemListener);
                    systemListener = null;
                }
                if (theme === 'system') {
                    systemListener = () => {
                        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                        document.documentElement.classList.toggle('dark', prefersDark);
                    };
                    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', systemListener);
                }
            }

            function applyUI(opts){
                if (!opts || Object.keys(opts).length === 0) return; // ignore empty payloads
                const font = opts.font || 'sans';
                const theme = opts.theme || 'light';
                const accent = opts.accent || '#0ea5e9';

                document.body.classList.remove('font-sans','font-serif','font-mono');
                document.body.classList.add('font-' + font);

                applyTheme(theme);

                document.documentElement.style.setProperty('--accent', accent);
            }

            // Apply initial server-rendered values
            applyUI({
                font: '{{ $uiFont }}',
                theme: '{{ $uiTheme }}',
                accent: '{{ $uiAccent }}'
            });

            // Listen globally for Livewire UI updates
            document.addEventListener('livewire:load', function(){
                if (window.Livewire) {
                    Livewire.on('ui-updated', (payload = {}) => applyUI(payload));
                }
            });
        })();
    </script>
</html>
