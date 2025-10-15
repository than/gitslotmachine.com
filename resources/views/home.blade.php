<x-terminal-layout title="Leaderboard" activeTab="home">
    <!-- Header -->
    <header class="text-center mb-12 border p-6 bg-black/30" style="border-color: var(--term-accent);">
        <pre id="machine-title" class="text-xs sm:text-sm mb-4" style="color: var(--term-text);">
╔═══════════════════════════════════════╗
║       <span class="letter">G</span><span class="letter">I</span><span class="letter">T</span> <span class="letter">S</span><span class="letter">L</span><span class="letter">O</span><span class="letter">T</span> <span class="letter">M</span><span class="letter">A</span><span class="letter">C</span><span class="letter">H</span><span class="letter">I</span><span class="letter">N</span><span class="letter">E</span> v1.0.0        ║
╚═══════════════════════════════════════╝
</pre>
        <h1 class="text-3xl sm:text-5xl font-bold mb-4" style="color: var(--term-text);">🎰 <span class="hover-trigger">COMMIT</span> & <span class="hover-trigger">SPIN</span> 🎰</h1>
        <p class="text-sm sm:text-lg" style="color: var(--term-dim);">&gt; Every commit is a spin. Will you hit the jackpot?</p>
    </header>

    <style>
        .letter {
            display: inline-block;
            transition: transform 0.3s ease;
        }

        .hover-trigger:hover ~ #machine-title .letter,
        h1:has(.hover-trigger:hover) ~ header #machine-title .letter,
        .hover-trigger:hover {
            cursor: pointer;
        }

        /* Target the header when hovering over COMMIT or SPIN */
        h1:has(.hover-trigger:hover) + * #machine-title .letter,
        header:has(.hover-trigger:hover) #machine-title .letter {
            animation: spinIn 0.5s ease forwards;
        }

        @keyframes spinIn {
            0% {
                transform: rotateY(90deg);
                opacity: 0;
            }
            100% {
                transform: rotateY(0deg);
                opacity: 1;
            }
        }

        /* Stagger the animation for each letter */
        header:has(.hover-trigger:hover) #machine-title .letter:nth-child(1) { animation-delay: 0.05s; }
        header:has(.hover-trigger:hover) #machine-title .letter:nth-child(2) { animation-delay: 0.1s; }
        header:has(.hover-trigger:hover) #machine-title .letter:nth-child(3) { animation-delay: 0.15s; }
        header:has(.hover-trigger:hover) #machine-title .letter:nth-child(4) { animation-delay: 0.2s; }
        header:has(.hover-trigger:hover) #machine-title .letter:nth-child(5) { animation-delay: 0.25s; }
        header:has(.hover-trigger:hover) #machine-title .letter:nth-child(6) { animation-delay: 0.3s; }
        header:has(.hover-trigger:hover) #machine-title .letter:nth-child(7) { animation-delay: 0.35s; }
        header:has(.hover-trigger:hover) #machine-title .letter:nth-child(8) { animation-delay: 0.4s; }
        header:has(.hover-trigger:hover) #machine-title .letter:nth-child(9) { animation-delay: 0.45s; }
        header:has(.hover-trigger:hover) #machine-title .letter:nth-child(10) { animation-delay: 0.5s; }
        header:has(.hover-trigger:hover) #machine-title .letter:nth-child(11) { animation-delay: 0.55s; }
        header:has(.hover-trigger:hover) #machine-title .letter:nth-child(12) { animation-delay: 0.6s; }
        header:has(.hover-trigger:hover) #machine-title .letter:nth-child(13) { animation-delay: 0.65s; }
        header:has(.hover-trigger:hover) #machine-title .letter:nth-child(14) { animation-delay: 0.7s; }
    </style>

    <!-- Livewire Leaderboard Component (auto-refreshes every 5 seconds) -->
    <livewire:leaderboard />
</x-terminal-layout>
