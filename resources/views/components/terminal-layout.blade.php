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

        /* Flip Container */
        .flip-container {
            perspective: 1000px;
            position: relative;
            margin-bottom: 2rem;
        }

        .flipper {
            position: relative;
            width: 100%;
            transform-style: preserve-3d;
            transition: transform 0.6s ease;
        }

        .flip-container:hover .flipper {
            transform: rotateX(180deg);
        }

        .flip-face {
            width: 100%;
            backface-visibility: hidden;
        }

        .flip-front {
            position: relative;
        }

        .flip-back {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            transform: rotateX(180deg);
        }
    </style>
</head>
<body class="font-mono transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Terminal Header with Flip Effect -->
        <div class="flip-container">
            <div class="flipper">
                <!-- Front Face - Terminal Chrome -->
                <div class="flip-face flip-front border bg-black/30 p-4" style="border-color: var(--term-accent);">
                    <div class="grid grid-cols-3 items-center mb-2">
                        <div class="flex items-center gap-2">
                            <span style="color: var(--term-accent);">●</span>
                            <span style="color: var(--term-accent);">●</span>
                            <span style="color: var(--term-accent);">●</span>
                        </div>
                        <span id="random-hash" class="text-xs font-mono text-center" style="color: var(--term-dim);"></span>
                        <span class="text-xs text-right" style="color: var(--term-dim);">git-slot-machine v1.0.1</span>
                    </div>
                    <div class="border-t pt-2" style="border-color: rgba(var(--term-accent-rgb), 0.3);"></div>
                </div>

                <!-- Back Face - Theme Picker -->
                <div class="flip-face flip-back border bg-black/30 p-4" style="border-color: var(--term-accent);">
                    <div class="flex items-center justify-center gap-3 mb-2">
                        <span class="text-xs font-bold" style="color: var(--term-text);">SELECT THEME:</span>
                        <button onclick="setTheme('green')" title="Classic Green"
                            class="w-8 h-8 rounded border-2 hover:scale-125 transition-transform"
                            style="background: #0a0e14; border-color: #00ff41;"></button>
                        <button onclick="setTheme('amber')" title="Amber"
                            class="w-8 h-8 rounded border-2 hover:scale-125 transition-transform"
                            style="background: #000000; border-color: #ffb000;"></button>
                        <button onclick="setTheme('white-blue')" title="DOS Blue"
                            class="w-8 h-8 rounded border-2 hover:scale-125 transition-transform"
                            style="background: #0000aa; border-color: #ffffff;"></button>
                        <button onclick="setTheme('green-blue')" title="IBM Green"
                            class="w-8 h-8 rounded border-2 hover:scale-125 transition-transform"
                            style="background: #001f3f; border-color: #00ff00;"></button>
                        <button onclick="setTheme('monokai')" title="Monokai"
                            class="w-8 h-8 rounded border-2 hover:scale-125 transition-transform"
                            style="background: #272822; border-color: #66d9ef;"></button>
                        <button onclick="setTheme('dracula')" title="Dracula"
                            class="w-8 h-8 rounded border-2 hover:scale-125 transition-transform"
                            style="background: #282a36; border-color: #bd93f9;"></button>
                        <button onclick="setTheme('solarized')" title="Solarized Dark"
                            class="w-8 h-8 rounded border-2 hover:scale-125 transition-transform"
                            style="background: #002b36; border-color: #2aa198;"></button>
                    </div>
                    <div class="border-t pt-2" style="border-color: rgba(var(--term-accent-rgb), 0.3);"></div>
                </div>
            </div>
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
            <a href="{{ route('odds') }}"
               class="font-bold hover:opacity-80 transition-opacity"
               style="color: {{ $activeTab === 'odds' ? 'var(--term-text)' : 'var(--term-dim)' }};">
                [ODDS]
            </a>
            <a href="{{ route('changelog') }}"
               class="font-bold hover:opacity-80 transition-opacity"
               style="color: {{ $activeTab === 'changelog' ? 'var(--term-text)' : 'var(--term-dim)' }};">
                [CHANGELOG]
            </a>
            <a href="{{ route('about') }}"
               class="font-bold hover:opacity-80 transition-opacity"
               style="color: {{ $activeTab === 'about' ? 'var(--term-text)' : 'var(--term-dim)' }};">
                [HOW TO PLAY]
            </a>
        </nav>

        {{ $slot }}
    </div>

    <!-- Livewire Reconnection Handling -->
    <script>
        document.addEventListener('livewire:init', () => {
            let reconnectBanner = null;

            // Show banner when disconnected
            Livewire.hook('request', ({ fail }) => {
                fail(() => {
                    if (!reconnectBanner) {
                        reconnectBanner = document.createElement('div');
                        reconnectBanner.style.cssText = `
                            position: fixed;
                            top: 0;
                            left: 0;
                            right: 0;
                            background: #ff4444;
                            color: white;
                            text-align: center;
                            padding: 0.5rem;
                            font-family: monospace;
                            font-weight: bold;
                            z-index: 9999;
                        `;
                        reconnectBanner.textContent = '⚠️ Connection lost. Reconnecting...';
                        document.body.prepend(reconnectBanner);
                    }
                });
            });

            // Remove banner and refresh when reconnected
            Livewire.hook('request', ({ succeed }) => {
                succeed(() => {
                    if (reconnectBanner) {
                        reconnectBanner.style.background = '#00ff41';
                        reconnectBanner.style.color = '#000';
                        reconnectBanner.textContent = '✓ Reconnected! Refreshing...';
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                    }
                });
            });
        });
    </script>

    <!-- Theme Switcher Script -->
    <script>
        function setTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('terminal-theme', theme);
        }

        // Load saved theme on page load, or pick random theme for first-time visitors
        document.addEventListener('DOMContentLoaded', function() {
            let savedTheme = localStorage.getItem('terminal-theme');

            // If no saved theme, pick a random one
            if (!savedTheme) {
                const themes = ['green', 'amber', 'white-blue', 'green-blue', 'monokai', 'dracula', 'solarized'];
                savedTheme = themes[Math.floor(Math.random() * themes.length)];
            }

            setTheme(savedTheme);
        });

        // Slot machine hash animation
        function generateRandomHash() {
            const chars = '0123456789abcdef';
            let hash = '';
            for (let i = 0; i < 7; i++) {
                hash += chars[Math.floor(Math.random() * chars.length)];
            }
            return hash;
        }

        function animateSlotHash() {
            const element = document.getElementById('random-hash');
            if (!element) return;

            const chars = '0123456789abcdef';
            const finalHash = generateRandomHash();
            const positions = new Array(7).fill(0);
            let frame = 0;
            const totalFrames = 20;

            function spin() {
                if (frame < totalFrames) {
                    let hash = '';
                    for (let i = 0; i < 7; i++) {
                        // Slow down each position as it gets closer to the end
                        const stopFrame = 8 + (i * 2);
                        if (frame < stopFrame) {
                            hash += chars[Math.floor(Math.random() * chars.length)];
                        } else {
                            hash += finalHash[i];
                        }
                    }
                    element.textContent = hash;
                    frame++;
                    setTimeout(spin, 50);
                } else {
                    element.textContent = finalHash;
                }
            }

            spin();
        }

        // Spin hash every 3 seconds
        setInterval(animateSlotHash, 3000);
        animateSlotHash(); // Initial call
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
