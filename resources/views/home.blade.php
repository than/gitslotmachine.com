<x-terminal-layout title="Leaderboard" activeTab="home">
    <!-- Header -->
    <header class="text-center mb-12 border p-6 bg-black/30" style="border-color: var(--term-accent);">
        <pre class="text-xs sm:text-sm mb-4" style="color: var(--term-text);">
╔═══════════════════════════════════════╗
║       GIT SLOT MACHINE v1.0.0        ║
╚═══════════════════════════════════════╝
</pre>
        <h1 class="text-3xl sm:text-5xl font-bold mb-4" style="color: var(--term-text);">🎰 COMMIT & SPIN 🎰</h1>

        <p class="text-sm sm:text-lg font-mono mb-6" style="color: var(--term-dim);">
            &gt; Every commit is your chance to win.
        </p>

        <!-- Live Demo -->
        <div class="p-4 bg-black border font-mono text-sm" style="border-color: var(--term-accent);">
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
            { hash: 'aaa1234', pattern: 'THREE OF A KIND', payout: 50, win: true },
            { hash: 'abc1234', pattern: 'NO WIN', payout: 0, win: false },
            { hash: 'aaaa123', pattern: 'FOUR OF A KIND', payout: 400, win: true },
            { hash: '3456789', pattern: 'NO WIN', payout: 0, win: false },
            { hash: 'abcdef1', pattern: 'ALL LETTERS', payout: 300, win: true },
            { hash: '1234098', pattern: 'ALL NUMBERS', payout: 10, win: true },
            { hash: 'aabbccd', pattern: 'TWO PAIR', payout: 50, win: true },
            { hash: 'f1e2d3c', pattern: 'NO WIN', payout: 0, win: false },
            { hash: '1234567', pattern: 'LUCKY SEVEN', payout: 2500, win: true },
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

        async function animateSlot() {
            const play = demoPlays[currentPlayIndex];

            // First run: show full setup sequence
            if (isFirstRun) {
                // Show: git-slot-machine init
                commandEl.textContent = 'git-slot-machine init';
                await sleep(800);
                line2El.innerHTML = '<span style="color: #00ff00;">✓</span> Post-commit hook installed';
                await sleep(1500);

                // Show: git commit
                line2El.innerHTML = '&nbsp;';
                commandEl.textContent = 'git commit -m "add the best feature in the world"';
                await sleep(800);
                line2El.innerHTML = '[main ' + play.hash + '] add the best feature in the world';
                await sleep(1200);

                isFirstRun = false;
            }

            // Show spin command
            line2El.innerHTML = '&nbsp;';
            commandEl.textContent = 'git-slot-machine spin --small';
            await sleep(500);

            // Animate spinning hash (8 frames)
            for (let i = 0; i < 8; i++) {
                const randomHash = generateRandomHash();
                line2El.innerHTML = `<span style="color: var(--term-accent);">${randomHash}</span>`;
                await sleep(80);
            }

            // Show final hash
            line2El.innerHTML = `<span style="color: var(--term-accent);">${play.hash}</span>`;
            await sleep(300);

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

            // Wait before next play
            await sleep(3000);

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
