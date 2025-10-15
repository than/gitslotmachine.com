<!DOCTYPE html>
<html lang="en" class="dark" data-theme="green">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $play->user->github_username }} won {{ $play->pattern_name }} - Git Slot Machine</title>

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('winner.show', $play->uuid) }}">
    <meta property="og:title" content="{{ $play->user->github_username }} hit {{ $play->pattern_name }}!">
    <meta property="og:description" content="Won +{{ $play->payout - 10 }} points on {{ $play->repository->displayFullName() }} • Hash: {{ $play->commit_hash }}">
    <meta property="og:image" content="{{ route('winner.image', $play->uuid) }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ route('winner.show', $play->uuid) }}">
    <meta property="twitter:title" content="{{ $play->user->github_username }} hit {{ $play->pattern_name }}!">
    <meta property="twitter:description" content="Won +{{ $play->payout - 10 }} points on {{ $play->repository->displayFullName() }}">
    <meta property="twitter:image" content="{{ route('winner.image', $play->uuid) }}">

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
    <div class="max-w-2xl mx-auto px-4 py-8">
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
                        <span class="text-xs font-mono text-center" style="color: var(--term-dim);">{{ $play->commit_hash }}</span>
                        <span class="text-xs text-right" style="color: var(--term-dim);">git-slot-machine v1.0.0</span>
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

        <div>
        <!-- Winner Card -->
        <div class="border bg-black/30 p-8" style="border-color: var(--term-accent);">
            <div class="text-center mb-8">
                <h1 class="text-5xl font-bold mb-4" style="color: var(--term-win);">🎰 BIG WIN! 🎰</h1>
                <p class="text-xl" style="color: var(--term-text);">{{ $play->user->github_username }} hit a <span class="font-bold" style="color: var(--term-win);">{{ $play->pattern_name }}</span>!</p>
            </div>

            <!-- Terminal Output -->
            <div class="border bg-black p-6 font-mono text-sm" style="border-color: var(--term-accent);">
                <div class="mb-4">
                    <span style="color: var(--term-dim);">Repository:</span>
                    <span class="font-bold ml-2" style="color: var(--term-text);">{{ $play->repository->displayFullName() }}</span>
                </div>
                <div class="mb-4">
                    <span style="color: var(--term-dim);">Commit Hash:</span>
                    <span class="font-bold ml-2 text-lg" style="color: var(--term-text);">{{ $play->commit_hash }}</span>
                </div>
                <div class="mb-4">
                    <span style="color: var(--term-dim);">Pattern:</span>
                    <span class="font-bold ml-2 text-xl" style="color: var(--term-win);">{{ $play->pattern_name }}</span>
                </div>
                <div class="mb-4">
                    <span style="color: var(--term-dim);">Payout:</span>
                    <span class="font-bold ml-2 text-2xl" style="color: var(--term-win);">+{{ $play->payout }}</span>
                </div>
                <div>
                    <span style="color: var(--term-dim);">Net:</span>
                    <span class="font-bold ml-2 text-2xl" style="color: var(--term-win);">+{{ $play->payout - 10 }}</span>
                </div>
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('home') }}" class="inline-block px-6 py-3 border font-bold hover:bg-white/10 transition" style="border-color: var(--term-accent); color: var(--term-text);">
                    [VIEW LEADERBOARD]
                </a>
            </div>
        </div>

        <!-- Install CTA -->
        <div class="mt-12 text-center">
            <p class="text-lg mb-4" style="color: var(--term-dim);">Want to play too?</p>
            <pre class="border bg-black/30 p-4 text-left inline-block" style="border-color: var(--term-accent); color: var(--term-text);">npm install -g git-slot-machine
cd your-repo
git-slot-machine init</pre>
        </div>
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
</body>
</html>
