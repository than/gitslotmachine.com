<x-terminal-layout title="Leaderboard" activeTab="home">
    <x-slot name="meta">
        <meta name="description" content="Turn every Git commit into a slot machine spin. Install the CLI, compete on global leaderboards, and win big with lucky commit hashes.">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url('/') }}">
        <meta property="og:title" content="Git Slot Machine - Commit & Spin">
        <meta property="og:description" content="Turn every Git commit into a slot machine spin. Install the CLI, compete on global leaderboards, and win big with lucky commit hashes.">
        <meta property="og:image" content="https://gitslotmachine.com/og-image.png">

        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:url" content="{{ url('/') }}">
        <meta name="twitter:title" content="Git Slot Machine - Commit & Spin">
        <meta name="twitter:description" content="Turn every Git commit into a slot machine spin. Install the CLI, compete on global leaderboards, and win big with lucky commit hashes.">
        <meta name="twitter:image" content="https://gitslotmachine.com/og-image.png">
    </x-slot>

    <!-- Header -->
    <header class="text-center mb-12 border p-6 bg-black/30" style="border-color: var(--term-accent);">
        <pre class="text-xs sm:text-sm mb-4" style="color: var(--term-text);">
╔═══════════════════════════════════════╗
║            COMMIT & SPIN             ║
╚═══════════════════════════════════════╝
</pre>
        <h1 class="text-3xl sm:text-5xl font-bold mb-4" style="color: var(--term-text);">🎰 GIT SLOT MACHINE 🎰</h1>

        <p class="text-sm sm:text-lg font-mono mb-6 text-center" style="color: var(--term-dim);">
            &gt; Every commit is your chance to win.
        </p>

        <!-- Live Demo -->
        <div class="p-4 bg-black border font-mono text-sm text-left mx-auto" style="border-color: var(--term-accent); max-width: 66.666667%;">
            <div id="slot-demo-line1" style="color: var(--term-text); min-height: 1.25rem;">
                <span style="color: var(--term-dim);">$</span> <span id="slot-demo-command"></span>
            </div>
            <div id="slot-demo-line2" style="color: var(--term-text); min-height: 1.25rem;">
                &nbsp;
            </div>
        </div>
    </header>

    <script>
        // Slot machine demo patterns (curated for visual interest, first one always wins)
        const demoPlays = [
            { hash: 'aaa1234', pattern: 'THREE OF A KIND', payout: 50, win: true, commit: 'add the best feature in the world' },
            { hash: 'abc1234', pattern: 'NO WIN', payout: 0, win: false, commit: 'fix typo in readme' },
            { hash: 'aaaa123', pattern: 'FOUR OF A KIND', payout: 400, win: true, commit: 'refactor authentication logic' },
            { hash: '3456789', pattern: 'NO WIN', payout: 0, win: false, commit: 'update dependencies' },
            { hash: 'abcdefa', pattern: 'ALL LETTERS', payout: 300, win: true, commit: 'implement dark mode' },
            { hash: '1234098', pattern: 'ALL NUMBERS', payout: 10, win: true, commit: 'add unit tests' },
            { hash: 'aabbccd', pattern: 'TWO PAIR', payout: 50, win: true, commit: 'optimize database queries' },
            { hash: 'f1e2d3c', pattern: 'NO WIN', payout: 0, win: false, commit: 'update changelog' },
            { hash: '1234567', pattern: 'LUCKY SEVEN', payout: 2500, win: true, commit: 'launch v2.0' },
        ];

        let currentPlayIndex = 0;
        let slotDemoBalance = 100;
        let isFirstRun = true;
        const commandEl = document.getElementById('slot-demo-command');
        const line2El = document.getElementById('slot-demo-line2');
        const dimColor = getComputedStyle(document.documentElement).getPropertyValue('--term-dim');

        function getRandomHex() {
            return '0123456789abcdef'[Math.floor(Math.random() * 16)];
        }

        function generateRandomHash() {
            return Array.from({ length: 7 }, () => getRandomHex()).join('');
        }

        async function typeText(element, text, speed = 50) {
            element.textContent = '';
            for (let i = 0; i < text.length; i++) {
                element.textContent += text[i];
                await sleep(speed);
            }
        }

        async function animateSlot() {
            const play = demoPlays[currentPlayIndex];

            // First run: show full setup sequence
            if (isFirstRun) {
                // Type: npm install -g git-slot-machine
                await typeText(commandEl, 'npm install -g git-slot-machine', 40);
                await sleep(800);
                line2El.innerHTML = '<span style="color: var(--term-dim);">added 3 packages in 1s</span>';
                await sleep(2000);

                // Type: git-slot-machine init
                line2El.innerHTML = '&nbsp;';
                await typeText(commandEl, 'git-slot-machine init', 40);
                await sleep(600);
                line2El.innerHTML = '<span style="color: #00ff00;">✓</span> Post-commit hook installed';
                await sleep(2000);

                isFirstRun = false;
            }

            // Type git commit command
            line2El.innerHTML = '&nbsp;';
            await typeText(commandEl, `git commit -m "${play.commit}"`, 30);
            await sleep(800);

            // Show git commit output
            line2El.innerHTML = `[main ${play.hash}] ${play.commit}`;
            await sleep(800);

            // Animate spinning hash (10 frames, slower)
            for (let i = 0; i < 10; i++) {
                const randomHash = generateRandomHash();
                line2El.innerHTML = `<span style="color: var(--term-accent);">${randomHash}</span>`;
                await sleep(100);
            }

            // Show final hash
            line2El.innerHTML = `<span style="color: var(--term-accent);">${play.hash}</span>`;
            await sleep(500);

            // Show result with CLI colors
            const netResult = play.payout - 10;
            let resultHTML = `<span style="color: var(--term-accent);">${play.hash}</span> <span style="color: ${dimColor};">•</span> `;

            if (play.win) {
                // Win: cyan/green for pattern name and payout
                resultHTML += `<span style="color: #00ffff; font-weight: bold;">${play.pattern}</span> <span style="color: #00ff00; font-weight: bold;">+${play.payout}</span>`;
            } else {
                // Loss: red
                resultHTML += `<span style="color: #ff0000;">NO WIN -10</span>`;
            }

            slotDemoBalance += netResult;
            const balanceColor = slotDemoBalance >= 0 ? '#00ff00' : '#ff0000';
            resultHTML += ` <span style="color: ${dimColor};">•</span> <span style="color: #ffffff;">Balance: <span style="color: ${balanceColor}; font-weight: bold;">${slotDemoBalance}</span></span>`;

            line2El.innerHTML = resultHTML;

            // Wait longer before next play (more relaxed)
            await sleep(4000);

            // Move to next play
            currentPlayIndex = (currentPlayIndex + 1) % demoPlays.length;

            // Restart the animation
            animateSlot();
        }

        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }

        // Start slot demo after a brief delay
        setTimeout(() => animateSlot(), 1000);
    </script>

    <!-- Livewire Leaderboard Component (auto-refreshes every 5 seconds) -->
    <livewire:leaderboard />
</x-terminal-layout>
