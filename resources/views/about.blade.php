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
