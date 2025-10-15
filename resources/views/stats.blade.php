<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stats - Git Slot Machine</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🎰</text></svg>">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-white">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Navigation -->
        <nav class="flex justify-center gap-6 mb-8">
            <a href="{{ route('home') }}" class="text-gray-400 hover:text-cyan-300">Leaderboard</a>
            <a href="{{ route('stats') }}" class="text-cyan-400 hover:text-cyan-300 font-semibold border-b-2 border-cyan-400">Statistics</a>
        </nav>

        <header class="text-center mb-12">
            <h1 class="text-5xl font-bold mb-4">📊 Statistics</h1>
            <p class="text-xl text-gray-400">Real-world pattern distribution</p>
        </header>

        <livewire:stats.global-stats />
    </div>

    <script>
        // Animated favicon slot machine
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
                    // Random emoji from array
                    const emoji = slotEmojis[Math.floor(Math.random() * slotEmojis.length)];
                    changeFavicon(emoji);

                    // Calculate delay - start fast (50ms), end slow (300ms)
                    const progress = frame / totalFrames;
                    const delay = 50 + (progress * progress * 250); // Quadratic easing

                    frame++;
                    setTimeout(spin, delay);
                } else {
                    // Land on slot machine emoji
                    changeFavicon('🎰');
                }
            }

            spin();
        }

        // Spin once on page load after 2 seconds
        setTimeout(spinFavicon, 2000);

        // Then spin every minute
        setInterval(spinFavicon, 60000);
    </script>
</body>
</html>
