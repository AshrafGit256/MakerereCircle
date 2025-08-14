<div x-data="{ }" class="max-w-3xl mx-auto p-4">
    <header class="mb-4">
        <h1 class="text-2xl font-bold">Appearance Settings</h1>
        <p class="text-gray-500 text-sm">Customize how the platform looks just for you. These settings are saved per browser session, you can later persist to your profile.</p>
    </header>

    <form wire:submit.prevent="save" class="space-y-6">
        <section class="bg-white border rounded-lg p-4">
            <h2 class="font-semibold mb-3">Default font</h2>
            <div class="grid grid-cols-2 gap-3">
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
            </div>
        </section>

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

        <section class="bg-white border rounded-lg p-4">
            <h2 class="font-semibold mb-3">Accent color</h2>
            <input type="color" wire:model="accent" class="h-10 w-20 p-0 border rounded" />
            <span class="ml-2 text-sm text-gray-600">{{ $accent }}</span>
        </section>

        <section class="bg-white border rounded-lg p-4">
            <h2 class="font-semibold mb-3">Other preferences</h2>
            <div class="space-y-2 text-sm text-gray-700">
                <label class="flex items-center gap-2">
                    <input type="checkbox" wire:model.live="show_compact" disabled />
                    <span class="text-gray-400">Compact mode (coming soon)</span>
                </label>
                <label class="flex items-center gap-2">
                    <input type="checkbox" wire:model.live="reduce_motion" disabled />
                    <span class="text-gray-400">Reduce animations (coming soon)</span>
                </label>
            </div>
        </section>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-md">Save</button>
        </div>
    </form>

    <script>
        document.addEventListener('livewire:load', () => {
            const applyUI = () => {
                const font = @json(session('ui.font', 'sans'));
                const theme = @json(session('ui.theme', 'light'));
                const accent = @json(session('ui.accent', '#0ea5e9'));

                // font
                document.body.classList.remove('font-sans','font-serif','font-mono');
                document.body.classList.add('font-' + font);

                // theme
                document.documentElement.classList.remove('dark');
                if (theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                }

                // accent via CSS custom property
                document.documentElement.style.setProperty('--accent', accent);
            };

            applyUI();
            Livewire.on('ui-updated', applyUI);
        });
    </script>
</div>
