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
                <p class="text-sm sm:text-base" style="color: var(--term-dim);">Ever catch yourself looking at your Git commits and noticing a fun pattern or a run of numbers? Maybe you got really lucky and had something spell out a word, or hit all the same character in a row? That little dopamine hit when you see <span class="font-bold" style="color: var(--term-text);">aed3333</span> or <span class="font-bold" style="color: var(--term-text);">abc1234</span> is real. This tool turns that casual observation into an actual game—every commit you make becomes a spin on the slot machine. Different patterns pay out different amounts, and you can compete with developers around the world to see who gets the luckiest (or commits the most!).</p>
            </div>
        </div>

        <!-- Patterns Reference -->
        <div class="mt-16 border-t pt-16" style="border-color: rgba(var(--term-accent-rgb), 0.3);">
            <h2 class="text-2xl sm:text-3xl font-bold mb-8" style="color: var(--term-text);">&gt; WINNING PATTERNS</h2>

            <p class="mb-6 text-sm sm:text-base" style="color: var(--term-dim);">Every commit costs 10 points. You start with 100. Here's what you're playing for:</p>

            <div class="border bg-black/30 overflow-x-auto" style="border-color: var(--term-accent);">
                <table class="w-full font-mono text-xs sm:text-sm">
                    <thead>
                        <tr class="border-b" style="color: var(--term-text); border-color: var(--term-accent);">
                            <th class="p-3 text-left">PATTERN</th>
                            <th class="p-3 text-left">EXAMPLE</th>
                            <th class="p-3 text-right">PAYOUT</th>
                            <th class="p-3 text-right hidden sm:table-cell">PROBABILITY</th>
                            <th class="p-3 text-left">DESCRIPTION</th>
                        </tr>
                    </thead>
                    <tbody style="color: var(--term-dim);">
                        <tr class="border-b hover:bg-white/5" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                            <td class="p-3 font-bold" style="color: var(--term-text);">JACKPOT</td>
                            <td class="p-3"><code>aaaaaaa</code></td>
                            <td class="p-3 text-right font-bold" style="color: var(--term-win);">+10,000</td>
                            <td class="p-3 text-right hidden sm:table-cell">1 in 16.7M</td>
                            <td class="p-3">All same character</td>
                        </tr>
                        <tr class="border-b hover:bg-white/5" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                            <td class="p-3 font-bold" style="color: var(--term-text);">HEXTET</td>
                            <td class="p-3"><code>aaaaaa1</code></td>
                            <td class="p-3 text-right font-bold" style="color: var(--term-win);">+5,000</td>
                            <td class="p-3 text-right hidden sm:table-cell">1 in 160K</td>
                            <td class="p-3">Six of a kind</td>
                        </tr>
                        <tr class="border-b hover:bg-white/5" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                            <td class="p-3 font-bold" style="color: var(--term-text);">LUCKY SEVEN</td>
                            <td class="p-3"><code>1234567</code></td>
                            <td class="p-3 text-right font-bold" style="color: var(--term-win);">+2,500</td>
                            <td class="p-3 text-right hidden sm:table-cell">1 in 2.5M</td>
                            <td class="p-3">Seven in a row</td>
                        </tr>
                        <tr class="border-b hover:bg-white/5" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                            <td class="p-3 font-bold" style="color: var(--term-text);">FULLEST HOUSE</td>
                            <td class="p-3"><code>aaaabbb</code></td>
                            <td class="p-3 text-right font-bold" style="color: var(--term-win);">+2,000</td>
                            <td class="p-3 text-right hidden sm:table-cell">1 in 32K</td>
                            <td class="p-3">4 + 3 of a kind</td>
                        </tr>
                        <tr class="border-b hover:bg-white/5" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                            <td class="p-3 font-bold" style="color: var(--term-text);">FIVE OF A KIND</td>
                            <td class="p-3"><code>aaaaa12</code></td>
                            <td class="p-3 text-right font-bold" style="color: var(--term-win);">+1,000</td>
                            <td class="p-3 text-right hidden sm:table-cell">1 in 8K</td>
                            <td class="p-3">Five of a kind</td>
                        </tr>
                        <tr class="border-b hover:bg-white/5" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                            <td class="p-3 font-bold" style="color: var(--term-text);">BIG STRAIGHT</td>
                            <td class="p-3"><code>012345a</code></td>
                            <td class="p-3 text-right font-bold" style="color: var(--term-win);">+500</td>
                            <td class="p-3 text-right hidden sm:table-cell">1 in 280K</td>
                            <td class="p-3">Six in a row</td>
                        </tr>
                        <tr class="border-b hover:bg-white/5" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                            <td class="p-3 font-bold" style="color: var(--term-text);">FOUR OF A KIND</td>
                            <td class="p-3"><code>aaaa123</code></td>
                            <td class="p-3 text-right font-bold" style="color: var(--term-win);">+400</td>
                            <td class="p-3 text-right hidden sm:table-cell">1 in 800</td>
                            <td class="p-3">Four of a kind</td>
                        </tr>
                        <tr class="border-b hover:bg-white/5" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                            <td class="p-3 font-bold" style="color: var(--term-text);">ALL LETTERS</td>
                            <td class="p-3"><code>abcdefa</code></td>
                            <td class="p-3 text-right font-bold" style="color: var(--term-win);">+300</td>
                            <td class="p-3 text-right hidden sm:table-cell">1 in 960</td>
                            <td class="p-3">Only letters (a-f)</td>
                        </tr>
                        <tr class="border-b hover:bg-white/5" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                            <td class="p-3 font-bold" style="color: var(--term-text);">STRAIGHT</td>
                            <td class="p-3"><code>01234ab</code></td>
                            <td class="p-3 text-right font-bold" style="color: var(--term-win);">+200</td>
                            <td class="p-3 text-right hidden sm:table-cell">1 in 9K</td>
                            <td class="p-3">Five in a row</td>
                        </tr>
                        <tr class="border-b hover:bg-white/5" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                            <td class="p-3 font-bold" style="color: var(--term-text);">DOUBLE TRIPLE</td>
                            <td class="p-3"><code>aaabbb1</code></td>
                            <td class="p-3 text-right font-bold" style="color: var(--term-win);">+150</td>
                            <td class="p-3 text-right hidden sm:table-cell">1 in 2K</td>
                            <td class="p-3">Two threes of a kind</td>
                        </tr>
                        <tr class="border-b hover:bg-white/5" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                            <td class="p-3 font-bold" style="color: var(--term-text);">THREE PAIR</td>
                            <td class="p-3"><code>aabbcc1</code></td>
                            <td class="p-3 text-right font-bold" style="color: var(--term-win);">+150</td>
                            <td class="p-3 text-right hidden sm:table-cell">1 in 400</td>
                            <td class="p-3">Three consecutive pairs</td>
                        </tr>
                        <tr class="border-b hover:bg-white/5" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                            <td class="p-3 font-bold" style="color: var(--term-text);">FULL HOUSE</td>
                            <td class="p-3"><code>aaaabb1</code></td>
                            <td class="p-3 text-right font-bold" style="color: var(--term-win);">+100</td>
                            <td class="p-3 text-right hidden sm:table-cell">1 in 1K</td>
                            <td class="p-3">Three and two of a kind</td>
                        </tr>
                        <tr class="border-b hover:bg-white/5" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                            <td class="p-3 font-bold" style="color: var(--term-text);">THREE OF A KIND</td>
                            <td class="p-3"><code>aaa1234</code></td>
                            <td class="p-3 text-right font-bold" style="color: var(--term-win);">+50</td>
                            <td class="p-3 text-right hidden sm:table-cell">1 in 133</td>
                            <td class="p-3">Three of a kind</td>
                        </tr>
                        <tr class="border-b hover:bg-white/5" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                            <td class="p-3 font-bold" style="color: var(--term-text);">TWO PAIR</td>
                            <td class="p-3"><code>aabb1cd</code></td>
                            <td class="p-3 text-right font-bold" style="color: var(--term-win);">+50</td>
                            <td class="p-3 text-right hidden sm:table-cell">1 in 30</td>
                            <td class="p-3">Two consecutive pairs</td>
                        </tr>
                        <tr class="border-b hover:bg-white/5" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                            <td class="p-3 font-bold" style="color: var(--term-text);">ALL NUMBERS</td>
                            <td class="p-3"><code>1230984</code></td>
                            <td class="p-3 text-right font-bold" style="color: var(--term-text);">+10</td>
                            <td class="p-3 text-right hidden sm:table-cell">1 in 27</td>
                            <td class="p-3">Break even</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="border p-4 bg-black/30" style="border-color: var(--term-accent);">
                    <h3 class="text-base sm:text-lg font-bold mb-2" style="color: var(--term-text);">STATS FOR NERDS</h3>
                    <ul class="space-y-1 text-xs sm:text-sm" style="color: var(--term-dim);">
                        <li><span style="color: var(--term-win);">~65% win rate</span> - Most commits win something!</li>
                        <li><span style="color: var(--term-text);">Positive expected value</span> - Math is on your side</li>
                        <li>In 100 commits: expect ~35 no-wins, ~3 two pairs, ~1 three of a kind</li>
                        <li>JACKPOT odds: 1 in 16.7M (you'd need to commit 46K times/day for 1,000 years)</li>
                    </ul>
                </div>
                <div class="border p-4 bg-black/30" style="border-color: var(--term-accent);">
                    <h3 class="text-base sm:text-lg font-bold mb-2" style="color: var(--term-text);">PATTERN RULES</h3>
                    <ul class="space-y-1 text-xs sm:text-sm" style="color: var(--term-dim);">
                        <li><span style="color: var(--term-text);">Straights</span> - Sequential hex values (ascending or descending)</li>
                        <li><span style="color: var(--term-text);">Consecutive pairs</span> - Must be adjacent identical chars (aa, bb, cc)</li>
                        <li><span style="color: var(--term-text);">Priority</span> - Checked from rarest to most common</li>
                        <li><span style="color: var(--term-text);">All hashes</span> - Every commit gets a fair shake!</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-terminal-layout>
