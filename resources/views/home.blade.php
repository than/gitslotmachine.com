<x-terminal-layout title="Leaderboard" activeTab="home">
    <!-- Header -->
    <header class="text-center mb-12 border p-6 bg-black/30" style="border-color: var(--term-accent);">
        <pre class="text-xs sm:text-sm mb-4" style="color: var(--term-text);">
╔═══════════════════════════════════════╗
║       GIT SLOT MACHINE v1.0.0        ║
╚═══════════════════════════════════════╝
</pre>
        <h1 class="text-3xl sm:text-5xl font-bold mb-4" style="color: var(--term-text);">🎰 COMMIT & SPIN 🎰</h1>
        <p class="text-sm sm:text-lg" style="color: var(--term-dim);">&gt; Every commit is a spin. Will you hit the jackpot?</p>
    </header>

    <!-- Livewire Leaderboard Component (auto-refreshes every 5 seconds) -->
    <livewire:leaderboard />

    <!-- FAQ Section -->
    <div class="mt-16 border-t pt-16" style="border-color: rgba(var(--term-accent-rgb), 0.3);">
        <h2 class="text-2xl sm:text-3xl font-bold mb-8" style="color: var(--term-text);">&gt; HOW IT WORKS</h2>

        <div class="space-y-6">
            <div class="border p-4 bg-black/30" style="border-color: var(--term-accent);">
                <h3 class="text-lg sm:text-xl font-bold mb-3" style="color: var(--term-text);">$ INSTALLATION</h3>
                <pre class="bg-black border p-4 text-xs sm:text-sm overflow-x-auto" style="border-color: rgba(var(--term-accent-rgb), 0.5); color: var(--term-dim);">npm install -g git-slot-machine
cd your-repo
git-slot-machine init</pre>
            </div>

            <div class="border p-4 bg-black/30" style="border-color: var(--term-accent);">
                <h3 class="text-lg sm:text-xl font-bold mb-3" style="color: var(--term-text);">$ WHAT HAPPENS?</h3>
                <p class="text-sm sm:text-base" style="color: var(--term-dim);">Every time you commit, the slot machine spins using your commit hash. Different patterns win different payouts!</p>
            </div>

            <div class="border p-4 bg-black/30" style="border-color: var(--term-accent);">
                <h3 class="text-lg sm:text-xl font-bold mb-3" style="color: var(--term-text);">$ LEADERBOARD</h3>
                <p class="text-sm sm:text-base" style="color: var(--term-dim);">Compete globally! Your plays are automatically tracked here. Daily leaderboard resets at midnight UTC.</p>
            </div>

            <div class="border p-4 bg-black/30" style="border-color: var(--term-accent);">
                <h3 class="text-lg sm:text-xl font-bold mb-3" style="color: var(--term-text);">$ WHAT IS THIS?</h3>
                <p class="text-sm sm:text-base" style="color: var(--term-dim);">Ever catch yourself looking at your Git commits and noticing a fun pattern or a run of numbers? Maybe you got really lucky and had something spell out a word, or hit all the same character in a row? That little dopamine hit when you see <span class="font-bold" style="color: var(--term-text);">a1b2c3d</span> or <span class="font-bold" style="color: var(--term-text);">7777777</span> is real. This tool turns that casual observation into an actual game—every commit you make becomes a spin on the slot machine. Different patterns pay out different amounts, and you can compete with developers around the world to see who gets the luckiest (or commits the most!).</p>
            </div>
        </div>
    </div>
</x-terminal-layout>
