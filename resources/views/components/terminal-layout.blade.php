@props(['title', 'activeTab' => 'home'])

<!DOCTYPE html>
<html lang="en" class="dark" data-theme="green">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - Git Slot Machine</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🎰</text></svg>">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background-color: var(--term-bg);
            color: var(--term-text);
            font-family: var(--font-mono), monospace;
        }
    </style>
</head>
<body class="font-mono transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Terminal Header with Theme Picker -->
        <div class="border bg-black/30 mb-8 p-4" style="border-color: var(--term-accent);">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <span style="color: var(--term-accent);">●</span>
                    <span style="color: var(--term-accent);">●</span>
                    <span style="color: var(--term-accent);">●</span>
                </div>

                <!-- Theme Picker -->
                <div class="flex items-center gap-2">
                    <span class="text-xs mr-2" style="color: var(--term-dim);">THEME:</span>
                    <button onclick="setTheme('green')" title="Classic Green"
                        class="w-6 h-6 rounded border-2 hover:scale-110 transition-transform"
                        style="background: #0a0e14; border-color: #00ff41;"></button>
                    <button onclick="setTheme('amber')" title="Amber"
                        class="w-6 h-6 rounded border-2 hover:scale-110 transition-transform"
                        style="background: #000000; border-color: #ffb000;"></button>
                    <button onclick="setTheme('white-blue')" title="DOS Blue"
                        class="w-6 h-6 rounded border-2 hover:scale-110 transition-transform"
                        style="background: #0000aa; border-color: #ffffff;"></button>
                    <button onclick="setTheme('green-blue')" title="IBM Green"
                        class="w-6 h-6 rounded border-2 hover:scale-110 transition-transform"
                        style="background: #001f3f; border-color: #00ff00;"></button>
                    <button onclick="setTheme('monokai')" title="Monokai"
                        class="w-6 h-6 rounded border-2 hover:scale-110 transition-transform"
                        style="background: #272822; border-color: #66d9ef;"></button>
                    <button onclick="setTheme('dracula')" title="Dracula"
                        class="w-6 h-6 rounded border-2 hover:scale-110 transition-transform"
                        style="background: #282a36; border-color: #bd93f9;"></button>
                    <button onclick="setTheme('solarized')" title="Solarized Dark"
                        class="w-6 h-6 rounded border-2 hover:scale-110 transition-transform"
                        style="background: #002b36; border-color: #2aa198;"></button>
                </div>

                <span class="text-xs" style="color: var(--term-dim);">git-slot-machine v1.0.0</span>
            </div>
            <div class="border-t pt-2" style="border-color: rgba(var(--term-accent-rgb), 0.3);"></div>
        </div>

        <!-- Navigation -->
        <nav class="flex justify-center gap-8 mb-8 font-mono">
            <a href="{{ route('home') }}"
               class="font-bold hover:opacity-80 transition-opacity"
               style="color: {{ $activeTab === 'home' ? 'var(--term-text)' : 'var(--term-dim)' }};">
                [LEADERBOARD]
            </a>
            <a href="{{ route('stats') }}"
               class="font-bold hover:opacity-80 transition-opacity"
               style="color: {{ $activeTab === 'stats' ? 'var(--term-text)' : 'var(--term-dim)' }};">
                [STATS]
            </a>
        </nav>

        {{ $slot }}
    </div>

    <!-- Theme Switcher Script -->
    <script>
        function setTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('terminal-theme', theme);
        }

        // Load saved theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('terminal-theme') || 'green';
            setTheme(savedTheme);
        });
    </script>

    <!-- Animated Favicon Slot Machine -->
    <script>
        const slotEmojis = ['🎰', '🎲', '🃏', '💎', '🍒', '🍋', '⭐', '7️⃣', '💰', '🎯'];

        function changeFavicon(emoji) {
            const link = document.querySelector("link[rel*='icon']") || document.createElement('link');
            link.type = 'image/svg+xml';
            link.rel = 'icon';
            link.href = `data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>${emoji}</text></svg>`;
            document.head.appendChild(link);
        }

        function spinFavicon() {
            let frame = 0;
            const totalFrames = 25;

            function spin() {
                if (frame < totalFrames) {
                    const emoji = slotEmojis[Math.floor(Math.random() * slotEmojis.length)];
                    changeFavicon(emoji);
                    const progress = frame / totalFrames;
                    const delay = 50 + (progress * progress * 250);
                    frame++;
                    setTimeout(spin, delay);
                } else {
                    changeFavicon('🎰');
                }
            }

            spin();
        }

        setTimeout(spinFavicon, 2000);
        setInterval(spinFavicon, 60000);
    </script>
</body>
</html>
