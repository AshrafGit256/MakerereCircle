<div x-data="{ }" class="max-w-3xl mx-auto p-4 space-y-6">
    <header class="mb-2">
        <h1 class="text-2xl font-bold">Appearance Settings</h1>
        <p class="text-gray-500 text-sm">Customize the look & feel of the entire app. Changes apply instantly and are saved in your session.</p>
    </header>

    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Font family -->
        <section class="bg-white border rounded-lg p-4">
            <h2 class="font-semibold mb-3">Font family</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                <label class="inline-flex items-center gap-2">
                    <input type="radio" wire:model="font" value="sans" />
                    <span class="font-sans">Sans</span>
                </label>
                <label class="inline-flex items-center gap-2">
                    <input type="radio" wire:model="font" value="serif" />
                    <span class="font-serif">Serif</span>
                </label>
                <label class="inline-flex items-center gap-2">
                    <input type="radio" wire:model="font" value="mono" />
                    <span class="font-mono">Monospace</span>
                </label>
                <label class="inline-flex items-center gap-2">
                    <input type="radio" wire:model="font" value="figtree" />
                    <span style="font-family: 'Figtree', ui-sans-serif, system-ui">Figtree</span>
                </label>
                <label class="inline-flex items-center gap-2">
                    <input type="radio" wire:model="font" value="inter" />
                    <span style="font-family: 'Inter', ui-sans-serif, system-ui">Inter</span>
                </label>
                <label class="inline-flex items-center gap-2">
                    <input type="radio" wire:model="font" value="rubik" />
                    <span style="font-family: 'Rubik', ui-sans-serif, system-ui">Rubik</span>
                </label>
            </div>
            <p class="text-xs text-gray-500 mt-2">Note: Additional web fonts may load lazily on first use.</p>
        </section>

        <!-- Theme -->
        <section class="bg-white border rounded-lg p-4">
            <h2 class="font-semibold mb-3">Theme</h2>
            <div class="grid grid-cols-3 gap-3">
                <label class="inline-flex items-center gap-2">
                    <input type="radio" wire:model="theme" value="light" />
                    <span>Light</span>
                </label>
                <label class="inline-flex items-center gap-2">
                    <input type="radio" wire:model="theme" value="dark" />
                    <span>Dark</span>
                </label>
                <label class="inline-flex items-center gap-2">
                    <input type="radio" wire:model="theme" value="system" />
                    <span>System</span>
                </label>
            </div>
        </section>

        <!-- Accent color -->
        <section class="bg-white border rounded-lg p-4">
            <h2 class="font-semibold mb-3">Accent color</h2>
            <div class="flex items-center gap-3 flex-wrap">
                <input type="color" wire:model="accent" class="h-10 w-20 p-0 border rounded" />
                <span class="text-sm text-gray-600">{{ $accent }}</span>
                <div class="flex gap-2 ml-auto">
                    @foreach (['#0ea5e9','#22c55e','#ef4444','#f59e0b','#a855f7','#06b6d4','#e11d48'] as $preset)
                    <button type="button" wire:click="$set('accent','{{ $preset }}')" class="w-6 h-6 rounded-full border" style="background: {{ $preset }}"></button>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Typography & spacing -->
        <section class="bg-white border rounded-lg p-4">
            <h2 class="font-semibold mb-3">Typography & spacing</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="flex items-center gap-3">
                    <span class="w-32 text-sm text-gray-600">Base font size</span>
                    <input type="range" min="14" max="20" step="1" wire:model.live="font_size" />
                    <span class="text-sm w-10">{{ $font_size }}px</span>
                </label>
                <label class="flex items-center gap-3">
                    <span class="w-32 text-sm text-gray-600">Corner radius</span>
                    <input type="range" min="0" max="20" step="1" wire:model.live="radius" />
                    <span class="text-sm w-10">{{ $radius }}px</span>
                </label>
                <label class="flex items-center gap-3">
                    <span class="w-32 text-sm text-gray-600">Link style</span>
                    <select wire:model="link_style" class="border rounded p-1 text-sm">
                        <option value="hover">Underline on hover</option>
                        <option value="always">Always underline</option>
                        <option value="none">No underline</option>
                    </select>
                </label>
                <label class="flex items-center gap-2">
                    <input type="checkbox" wire:model="compact" />
                    <span class="text-sm text-gray-700">Compact mode</span>
                </label>
                <label class="flex items-center gap-2">
                    <input type="checkbox" wire:model="reduce_motion" />
                    <span class="text-sm text-gray-700">Reduce animations</span>
                </label>
            </div>
        </section>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-md">Save</button>
        </div>
    </form>

    <script>
        document.addEventListener('livewire:load', () => {
            const loadFonts = (font) => {
                const heads = document.getElementsByTagName('head');
                if (!heads.length) return;
                const head = heads[0];
                const existing = document.getElementById('ui-extra-fonts');
                if (existing) existing.remove();
                const link = document.createElement('link');
                link.rel = 'stylesheet';
                link.id = 'ui-extra-fonts';
                if (font === 'inter') {
                    link.href = 'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap';
                } else if (font === 'rubik') {
                    link.href = 'https://fonts.googleapis.com/css2?family=Rubik:wght@400;600;700&display=swap';
                } else if (font === 'figtree') {
                    link.href = 'https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap';
                } else {
                    return; // system fonts
                }
                head.appendChild(link);
            };

            const applyUI = (payload) => {
                const font = (payload && payload.font) ?? @json(session('ui.font', 'sans'));
                const theme = (payload && payload.theme) ?? @json(session('ui.theme', 'light'));
                const accent = (payload && payload.accent) ?? @json(session('ui.accent', '#0ea5e9'));
                const fontSize = (payload && payload.font_size) ?? @json((int) session('ui.font_size', 16));
                const radius = (payload && payload.radius) ?? @json((int) session('ui.radius', 8));
                const linkStyle = (payload && payload.link_style) ?? @json(session('ui.link_style', 'hover'));
                const reduceMotion = (payload && payload.reduce_motion) ?? @json((bool) session('ui.reduce_motion', false));
                const compact = (payload && payload.compact) ?? @json((bool) session('ui.compact', false));

                // font
                document.body.classList.remove('font-sans','font-serif','font-mono');
                if (['inter','rubik','figtree'].includes(font)) {
                    loadFonts(font);
                    document.body.style.fontFamily = font.charAt(0).toUpperCase() + font.slice(1) + ", ui-sans-serif, system-ui";
                } else {
                    document.body.removeAttribute('style');
                    document.body.classList.add('font-' + font);
                }

                // theme
                document.documentElement.classList.remove('dark');
                if (theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                }

                // CSS custom properties
                document.documentElement.style.setProperty('--accent', accent);
                document.documentElement.style.setProperty('--radius', radius + 'px');
                document.documentElement.style.setProperty('--base-font-size', fontSize + 'px');

                // link style
                document.documentElement.classList.remove('link-hover','link-always','link-none');
                document.documentElement.classList.add('link-' + linkStyle);

                // reduced motion
                document.documentElement.classList.toggle('reduce-motion', !!reduceMotion);
                // compact mode
                document.documentElement.classList.toggle('compact', !!compact);
            };

            applyUI();
            Livewire.on('ui-updated', applyUI);
        });
    </script>

    <style>
        :root{
            --radius: 8px;
            --base-font-size: 16px;
        }
        html .rounded, html .rounded-lg, html .rounded-md, html .rounded-xl{ border-radius: var(--radius) !important; }
        html{ font-size: var(--base-font-size); }
        html.link-always a{ text-decoration: underline; }
        html.link-none a{ text-decoration: none !important; }
        html.link-hover a{ text-decoration: none; }
        html.link-hover a:hover{ text-decoration: underline; }
        html.reduce-motion *{ transition: none !important; animation: none !important; }
        html.compact .p-4{ padding: .75rem !important; }
        html.compact .p-3{ padding: .5rem !important; }
        html.compact .p-2{ padding: .375rem !important; }
        html.compact .py-2{ padding-top: .375rem !important; padding-bottom: .375rem !important; }
    </style>
</div>
