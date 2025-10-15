<x-terminal-layout title="Leaderboard" activeTab="home">
    <!-- Header -->
    <header class="text-center mb-12 border p-6 bg-black/30" style="border-color: var(--term-accent);">
        <pre class="text-xs sm:text-sm mb-4" style="color: var(--term-text);">
╔═══════════════════════════════════════╗
║       GIT SLOT MACHINE v1.0.0        ║
╚═══════════════════════════════════════╝
</pre>
        <h1 class="text-3xl sm:text-5xl font-bold mb-4" style="color: var(--term-text);">🎰 COMMIT & SPIN 🎰</h1>
        <p class="text-sm sm:text-lg" style="color: var(--term-dim);">&gt; Every commit is your chance to win.</p>
        <p class="text-sm sm:text-lg font-mono" style="color: var(--term-dim);">&gt; <span id="typewriter"></span></p>
    </header>

    <script>
        const text = "Will you hit the jackpot?";
        const typewriterEl = document.getElementById('typewriter');
        let charIndex = 0;
        let isTyping = true;

        function typewriter() {
            if (isTyping) {
                if (charIndex < text.length) {
                    typewriterEl.textContent = text.substring(0, charIndex + 1);
                    charIndex++;
                    setTimeout(typewriter, 100);
                } else {
                    isTyping = false;
                    setTimeout(typewriter, 2000); // Pause before erasing
                }
            } else {
                if (charIndex > 0) {
                    typewriterEl.textContent = text.substring(0, charIndex - 1);
                    charIndex--;
                    setTimeout(typewriter, 50);
                } else {
                    isTyping = true;
                    setTimeout(typewriter, 1000); // Pause before typing again
                }
            }
        }

        // Start the animation
        typewriter();
    </script>

    <!-- Livewire Leaderboard Component (auto-refreshes every 5 seconds) -->
    <livewire:leaderboard />
</x-terminal-layout>
