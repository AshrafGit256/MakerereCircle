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
            --accent: {
                    {
                    $uiAccent
                }
            }

            ;
        }

        [x-cloak] {
            display: none !important;
        }

        /* Accent helpers (lightweight) */
        .accent-bg {
            background-color: var(--accent) !important;
        }

        .accent-text {
            color: var(--accent) !important;
        }

        .accent-border {
            border-color: var(--accent) !important;
        }

        /* Global dark mode overrides */
        html.dark body {
            background-color: #0f172a;
            color: #e5e7eb;
        }

        html.dark .bg-white {
            background-color: #111827 !important;
            color: #e5e7eb !important;
        }

        html.dark .bg-gray-100 {
            background-color: #1f2937 !important;
        }

        html.dark .bg-gray-50 {
            background-color: #374151 !important;
        }

        html.dark .border {
            border-color: #374151 !important;
        }

        html.dark .text-gray-700 {
            color: #d1d5db !important;
        }

        html.dark .text-gray-600 {
            color: #d1d5db !important;
        }

        html.dark .text-gray-500 {
            color: #9ca3af !important;
        }

        html.dark .text-gray-800 {
            color: #f9fafb !important;
        }

        html.dark .text-gray-900 {
            color: #ffffff !important;
        }

        html.dark input, html.dark textarea, html.dark select {
            background-color: #1f2937 !important;
            border-color: #374151 !important;
            color: #e5e7eb !important;
        }

        html.dark input::placeholder, html.dark textarea::placeholder {
            color: #9ca3af !important;
        }

        html.dark button {
            background-color: #374151 !important;
            color: #e5e7eb !important;
        }

        html.dark button:hover {
            background-color: #4b5563 !important;
        }
    </style>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased font-{{ $uiFont }}" x-data="{}" x-init="
    applyUI({
        font: '{{ $uiFont }}',
        theme: localStorage.getItem('theme') || '{{ $uiTheme }}',
        accent: '{{ $uiAccent }}'
    })
">

    <!-- Quick Theme Toggle -->
    <div class="fixed top-4 right-4 z-50">
        <button
            onclick="toggleTheme()"
            class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-full p-2 shadow-lg hover:shadow-xl transition-all duration-200"
            title="Toggle theme"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </button>
    </div>

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
    (function() {
        let systemListener = null;

        function applyTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else if (theme === 'light') {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else if (theme === 'system') {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                document.documentElement.classList.toggle('dark', prefersDark);
                localStorage.setItem('theme', 'system');
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

        function applyUI(opts) {
            if (!opts || Object.keys(opts).length === 0) return; // ignore empty payloads
            const font = opts.font || 'sans';
            const theme = opts.theme || 'light';
            const accent = opts.accent || '#0ea5e9';

            document.body.classList.remove('font-sans', 'font-serif', 'font-mono');
            document.body.classList.add('font-' + font);

            applyTheme(theme);

            document.documentElement.style.setProperty('--accent', accent);
        }

        // Listen globally for Livewire UI updates
        Livewire.on('ui-updated', (payload) => {
            applyUI(payload);
        });

        // Quick theme toggle function
        window.toggleTheme = function() {
            const currentTheme = localStorage.getItem('theme') || '{{ $uiTheme }}';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            applyUI({
                font: '{{ $uiFont }}',
                theme: newTheme,
                accent: '{{ $uiAccent }}'
            });

            // Also update session via Livewire if settings component exists
            if (typeof Livewire !== 'undefined') {
                Livewire.dispatch('ui-updated', {
                    font: '{{ $uiFont }}',
                    theme: newTheme,
                    accent: '{{ $uiAccent }}'
                });
            }
        };

        // Apply initial server-rendered values
        applyUI({
            font: '{{ $uiFont }}',
            theme: localStorage.getItem('theme') || '{{ $uiTheme }}',
            accent: '{{ $uiAccent }}'
        });
    })();
</script>

</html>