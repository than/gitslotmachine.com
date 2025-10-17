<x-terminal-layout title="About" activeTab="about">
    <x-slot name="meta">
        <meta name="description" content="Learn how to install Git Slot Machine CLI and start winning with every commit. Global leaderboards, shareable winner cards, and dynamic badges.">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url('/about') }}">
        <meta property="og:title" content="How It Works - Git Slot Machine">
        <meta property="og:description" content="Learn how to install Git Slot Machine CLI and start winning with every commit. Global leaderboards, shareable winner cards, and dynamic badges.">
        <meta property="og:image" content="https://gitslotmachine.com/og-image.png">

        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:url" content="{{ url('/about') }}">
        <meta name="twitter:title" content="How It Works - Git Slot Machine">
        <meta name="twitter:description" content="Learn how to install Git Slot Machine CLI and start winning with every commit. Global leaderboards, shareable winner cards, and dynamic badges.">
        <meta name="twitter:image" content="https://gitslotmachine.com/og-image.png">
    </x-slot>

    <!-- Header -->
    <header class="text-center mb-12 border p-6 bg-black/30" style="border-color: var(--term-accent);">
        <pre class="text-xs sm:text-sm mb-4" style="color: var(--term-text);">
╔═══════════════════════════════════════╗
║          HOW TO PLAY v1.0.0          ║
╚═══════════════════════════════════════╝
</pre>
        <h1 class="text-3xl sm:text-5xl font-bold mb-4" style="color: var(--term-text);">🎰 GET STARTED 🎰</h1>
        <p class="text-sm sm:text-lg" style="color: var(--term-dim);">&gt; Install, commit, win. It's that simple.</p>
    </header>

    <!-- FAQ Section -->
    <div class="border-t pt-8" style="border-color: rgba(var(--term-accent-rgb), 0.3);">
        <h2 class="text-2xl sm:text-3xl font-bold mb-8" style="color: var(--term-text);">&gt; HOW IT WORKS</h2>

        <div class="space-y-6">
            <div class="border p-4 bg-black/30" style="border-color: var(--term-accent);">
                <h3 class="text-lg sm:text-xl font-bold mb-3" style="color: var(--term-text);">$ INSTALLATION</h3>
                <pre class="bg-black border p-4 text-xs sm:text-sm overflow-x-auto" style="border-color: var(--term-accent); color: var(--term-accent);">npm install -g git-slot-machine
cd your-repo
git-slot-machine init</pre>
                <div class="mt-4 flex flex-wrap gap-3">
                    <a href="https://www.npmjs.com/package/git-slot-machine" target="_blank" rel="noopener" class="inline-flex items-center gap-2 border px-3 py-2 text-sm font-mono hover:bg-white/10 transition-colors" style="border-color: var(--term-accent); color: var(--term-text);">
                        <span style="color: var(--term-accent);">[</span>NPM<span style="color: var(--term-accent);">]</span>
                    </a>
                    <a href="https://github.com/than/git-slot-machine" target="_blank" rel="noopener" class="inline-flex items-center gap-2 border px-3 py-2 text-sm font-mono hover:bg-white/10 transition-colors" style="border-color: var(--term-accent); color: var(--term-text);">
                        <span style="color: var(--term-accent);">[</span>CLI SOURCE<span style="color: var(--term-accent);">]</span>
                    </a>
                    <a href="https://github.com/than/gitslotmachine.com" target="_blank" rel="noopener" class="inline-flex items-center gap-2 border px-3 py-2 text-sm font-mono hover:bg-white/10 transition-colors" style="border-color: var(--term-accent); color: var(--term-text);">
                        <span style="color: var(--term-accent);">[</span>API SOURCE<span style="color: var(--term-accent);">]</span>
                    </a>
                </div>
            </div>

            <div class="border p-4 bg-black/30" style="border-color: var(--term-accent);">
                <h3 class="text-lg sm:text-xl font-bold mb-3" style="color: var(--term-text);">$ WHAT HAPPENS?</h3>
                <p class="text-sm sm:text-base" style="color: var(--term-text);">Every time you commit, the slot machine spins using your commit hash. Different patterns win different payouts!</p>
            </div>

            <div class="border p-4 bg-black/30" style="border-color: var(--term-accent);">
                <h3 class="text-lg sm:text-xl font-bold mb-3" style="color: var(--term-text);">$ HOW BALANCES WORK</h3>
                <div class="space-y-2 text-sm sm:text-base" style="color: var(--term-text);">
                    <p><span class="font-bold" style="color: var(--term-accent);">•</span> Every repository starts at <span class="font-bold" style="color: var(--term-win);">100 points</span></p>
                    <p><span class="font-bold" style="color: var(--term-accent);">•</span> Each play (commit) costs <span class="font-bold">10 points</span> (the wager)</p>
                    <p><span class="font-bold" style="color: var(--term-accent);">•</span> Win a pattern? The <span class="font-bold" style="color: var(--term-win);">payout is added</span> to your balance</p>
                    <p><span class="font-bold" style="color: var(--term-accent);">•</span> No win? You just lose the 10 point wager</p>
                    <p><span class="font-bold" style="color: var(--term-accent);">•</span> Net profit = <span class="font-mono" style="color: var(--term-dim);">current balance - 100</span></p>
                </div>
                <div class="mt-4 p-3 bg-black/50 border" style="border-color: rgba(var(--term-accent-rgb), 0.3);">
                    <p class="text-xs sm:text-sm font-mono" style="color: var(--term-dim);">
                        Example: Balance at 250? You're up 150 points!
                    </p>
                </div>
            </div>

            <div class="border p-4 bg-black/30" style="border-color: var(--term-accent);">
                <h3 class="text-lg sm:text-xl font-bold mb-3" style="color: var(--term-text);">$ PATTERN ODDS (TOP WINS)</h3>
                <p class="text-sm sm:text-base mb-4" style="color: var(--term-text);">Quick reference for the most exciting patterns:</p>
                <div class="space-y-2 text-xs sm:text-sm font-mono" style="color: var(--term-text);">
                    <div class="flex justify-between items-center">
                        <span><span style="color: var(--term-accent);">JACKPOT</span> <code class="text-xs" style="color: var(--term-dim);">aaaaaaa</code></span>
                        <span class="font-bold" style="color: var(--term-win);">+10,000 (1 in 16.7M)</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span><span style="color: var(--term-accent);">HEXTET</span> <code class="text-xs" style="color: var(--term-dim);">aaaaaa1</code></span>
                        <span class="font-bold" style="color: var(--term-win);">+5,000 (1 in 160K)</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span><span style="color: var(--term-accent);">LUCKY SEVEN</span> <code class="text-xs" style="color: var(--term-dim);">1234567</code></span>
                        <span class="font-bold" style="color: var(--term-win);">+2,500 (1 in 2.5M)</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span><span style="color: var(--term-accent);">FOUR OF A KIND</span> <code class="text-xs" style="color: var(--term-dim);">aaaa123</code></span>
                        <span class="font-bold" style="color: var(--term-win);">+400 (1 in 800)</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span><span style="color: var(--term-accent);">THREE OF A KIND</span> <code class="text-xs" style="color: var(--term-dim);">aaa1234</code></span>
                        <span class="font-bold" style="color: var(--term-win);">+50 (1 in 133)</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span><span style="color: var(--term-accent);">TWO PAIR</span> <code class="text-xs" style="color: var(--term-dim);">aa1bb2c</code></span>
                        <span class="font-bold" style="color: var(--term-win);">+50 (1 in 45)</span>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t" style="border-color: rgba(var(--term-accent-rgb), 0.3);">
                    <a href="/odds" class="inline-flex items-center gap-2 text-sm hover:underline" style="color: var(--term-accent);">
                        View all patterns and odds →
                    </a>
                </div>
            </div>

            <div class="border p-4 bg-black/30" style="border-color: var(--term-accent);">
                <h3 class="text-lg sm:text-xl font-bold mb-3" style="color: var(--term-text);">$ LEADERBOARD</h3>
                <p class="text-sm sm:text-base" style="color: var(--term-text);">Compete globally! Your plays are automatically tracked here. Daily leaderboard resets at midnight UTC.</p>
            </div>

            <div class="border p-4 bg-black/30" style="border-color: var(--term-accent);">
                <h3 class="text-lg sm:text-xl font-bold mb-3" style="color: var(--term-text);">$ WINNER SHARING</h3>
                <p class="text-sm sm:text-base mb-4" style="color: var(--term-text);">Every win gets a shareable URL with beautiful Open Graph images for social media!</p>
                <div class="space-y-2 text-xs sm:text-sm mb-4" style="color: var(--term-text);">
                    <p><span class="font-bold" style="color: var(--term-accent);">•</span> CLI shows share URL when you win (payout > 0)</p>
                    <p><span class="font-bold" style="color: var(--term-accent);">•</span> Auto-generated 1200x630 terminal-themed OG images</p>
                    <p><span class="font-bold" style="color: var(--term-accent);">•</span> Works on Twitter, Facebook, LinkedIn, Discord, Slack</p>
                    <p><span class="font-bold" style="color: var(--term-accent);">•</span> Permanent links - winner pages never expire</p>
                </div>
                <p class="text-xs" style="color: var(--term-dim);">Format: <span class="font-mono" style="color: var(--term-text);">gitslotmachine.com/winner/{'{uuid}'}</span></p>
            </div>

            <div class="border p-4 bg-black/30" style="border-color: var(--term-accent);">
                <h3 class="text-lg sm:text-xl font-bold mb-3" style="color: var(--term-text);">$ REPOSITORY BADGES</h3>
                <p class="text-sm sm:text-base mb-4" style="color: var(--term-text);">Show off your last commit results with dynamic badges! Add this to your README:</p>
                <div class="relative">
                    <pre id="badge-markdown" class="bg-black border p-4 text-xs sm:text-sm overflow-x-auto mb-2" style="border-color: var(--term-accent); color: var(--term-accent);">[![Git Slot Machine](https://gitslotmachine.com/badge/owner/repo.svg)](https://gitslotmachine.com)</pre>
                    <button
                        id="copy-badge-btn"
                        onclick="copyBadgeMarkdown()"
                        class="w-full border px-4 py-2 text-sm font-mono hover:bg-white/10 transition-colors mb-4"
                        style="border-color: var(--term-accent); color: var(--term-text);"
                    >
                        <span id="copy-btn-text" style="color: var(--term-accent);">[</span>COPY<span style="color: var(--term-accent);">]</span>
                    </button>
                </div>
                <div class="space-y-2 text-xs sm:text-sm" style="color: var(--term-text);">
                    <p><span class="font-bold" style="color: #00ff00;">✓ Green</span> - Last commit won! Shows pattern, payout, and hash</p>
                    <p><span class="font-bold" style="color: #ff0000;">✗ Red</span> - Last commit didn't win. Better luck next time!</p>
                    <p><span class="font-bold" style="color: #9f9f9f;">○ Gray</span> - No plays yet. Install the CLI to start!</p>
                    <p style="color: var(--term-dim);">Badge auto-refreshes every 5 minutes</p>
                </div>
            </div>

            <script>
                function copyBadgeMarkdown() {
                    const markdownText = document.getElementById('badge-markdown').textContent;
                    const button = document.getElementById('copy-badge-btn');
                    const buttonText = button.innerHTML;

                    navigator.clipboard.writeText(markdownText).then(function() {
                        // Success feedback
                        button.innerHTML = '<span style="color: var(--term-accent);">[</span>COPIED!<span style="color: var(--term-accent);">]</span>';
                        button.style.backgroundColor = 'rgba(var(--term-accent-rgb), 0.2)';

                        // Reset after 2 seconds
                        setTimeout(function() {
                            button.innerHTML = buttonText;
                            button.style.backgroundColor = '';
                        }, 2000);
                    }).catch(function(err) {
                        // Error feedback
                        button.innerHTML = '<span style="color: var(--term-accent);">[</span>ERROR<span style="color: var(--term-accent);">]</span>';
                        console.error('Failed to copy:', err);

                        // Reset after 2 seconds
                        setTimeout(function() {
                            button.innerHTML = buttonText;
                        }, 2000);
                    });
                }
            </script>

            <div class="border p-4 bg-black/30" style="border-color: var(--term-accent);">
                <h3 class="text-lg sm:text-xl font-bold mb-3" style="color: var(--term-text);">$ WHAT IS THIS?</h3>
                <p class="text-sm sm:text-base" style="color: var(--term-text);">Ever catch yourself looking at your Git commits and noticing a fun pattern or a run of numbers? Maybe you got really lucky and had something spell out a word, or hit all the same character in a row? That little dopamine hit when you see <span class="font-bold" style="color: var(--term-accent);">aed3333</span> or <span class="font-bold" style="color: var(--term-accent);">abc1234</span> is real. This tool turns that casual observation into an actual game—every commit you make becomes a spin on the slot machine. Different patterns pay out different amounts, and you can compete with developers around the world to see who gets the luckiest (or commits the most!).</p>
            </div>
        </div>
    </div>
</x-terminal-layout>
