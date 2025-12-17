<x-terminal-layout title="Odds" activeTab="odds">
    <x-slot name="meta">
        <meta name="description" content="Complete guide to winning patterns and payouts. From Jackpot (10,000 points) to Three of a Kind (50 points). Know your odds before you commit.">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url('/odds') }}">
        <meta property="og:title" content="Winning Patterns & Odds - Git Slot Machine">
        <meta property="og:description" content="Complete guide to winning patterns and payouts. From Jackpot (10,000 points) to Three of a Kind (50 points). Know your odds before you commit.">
        <meta property="og:image" content="https://gitslotmachine.com/og-image.png">

        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:url" content="{{ url('/odds') }}">
        <meta name="twitter:title" content="Winning Patterns & Odds - Git Slot Machine">
        <meta name="twitter:description" content="Complete guide to winning patterns and payouts. From Jackpot (10,000 points) to Three of a Kind (50 points). Know your odds before you commit.">
        <meta name="twitter:image" content="https://gitslotmachine.com/og-image.png">
    </x-slot>

    <!-- Header -->
    <header class="text-center mb-12 border p-6 bg-black/30" style="border-color: var(--term-accent);">
        <pre class="text-xs sm:text-sm mb-4" style="color: var(--term-text);">
╔═══════════════════════════════════════╗
║       WINNING PATTERNS & ODDS        ║
╚═══════════════════════════════════════╝
</pre>
        <h1 class="text-3xl sm:text-5xl font-bold mb-4" style="color: var(--term-text);">🎰 KNOW THE ODDS 🎰</h1>
        <p class="text-sm sm:text-lg" style="color: var(--term-dim);">&gt; Every commit costs 10 points. Here's what you're playing for.</p>
    </header>

    <!-- Patterns Reference -->
    <div class="border-t pt-8" style="border-color: rgba(var(--term-accent-rgb), 0.3);">
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
                    <tr class="border-b hover:bg-white/5 odds-row" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                        <td class="p-3 font-bold" style="color: var(--term-text);">JACKPOT</td>
                        <td class="p-3"><span class="hash-display" data-hash="aaaaaaa"></span></td>
                        <td class="p-3 text-right font-bold" style="color: var(--term-win);">+100,000</td>
                        <td class="p-3 text-right hidden sm:table-cell">1 in 16.7M</td>
                        <td class="p-3 description-cell" data-calculation="(1/16)^7"><span class="description-text">All same character</span></td>
                    </tr>
                    <tr class="border-b hover:bg-white/5 odds-row" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                        <td class="p-3 font-bold" style="color: var(--term-text);">LUCKY SEVEN</td>
                        <td class="p-3"><span class="hash-display" data-hash="1234567"></span></td>
                        <td class="p-3 text-right font-bold" style="color: var(--term-win);">+50,000</td>
                        <td class="p-3 text-right hidden sm:table-cell">1 in 13.4M</td>
                        <td class="p-3 description-cell" data-calculation="20 × 16^0 / 16^7"><span class="description-text">Seven sequential hex digits</span></td>
                    </tr>
                    <tr class="border-b hover:bg-white/5 odds-row" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                        <td class="p-3 font-bold" style="color: var(--term-text);">BIG STRAIGHT</td>
                        <td class="p-3"><span class="hash-display" data-hash="012345a"></span></td>
                        <td class="p-3 text-right font-bold" style="color: var(--term-win);">+25,000</td>
                        <td class="p-3 text-right hidden sm:table-cell">1 in 400K</td>
                        <td class="p-3 description-cell" data-calculation="2 × 22 × 16 / 16^7"><span class="description-text">Six sequential hex digits</span></td>
                    </tr>
                    <tr class="border-b hover:bg-white/5 odds-row" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                        <td class="p-3 font-bold" style="color: var(--term-text);">HEXTET</td>
                        <td class="p-3"><span class="hash-display" data-hash="aaaaaa1"></span></td>
                        <td class="p-3 text-right font-bold" style="color: var(--term-win);">+10,000</td>
                        <td class="p-3 text-right hidden sm:table-cell">1 in 160K</td>
                        <td class="p-3 description-cell" data-calculation="7·(15/16)·(1/16)^6"><span class="description-text">Six of a kind</span></td>
                    </tr>
                    <tr class="border-b hover:bg-white/5 odds-row" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                        <td class="p-3 font-bold" style="color: var(--term-text);">FULLEST HOUSE</td>
                        <td class="p-3"><span class="hash-display" data-hash="aaaabbb"></span></td>
                        <td class="p-3 text-right font-bold" style="color: var(--term-win);">+5,000</td>
                        <td class="p-3 text-right hidden sm:table-cell">1 in 32K</td>
                        <td class="p-3 description-cell" data-calculation="16·15·(1/16)^7"><span class="description-text">4 + 3 of a kind</span></td>
                    </tr>
                    <tr class="border-b hover:bg-white/5 odds-row" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                        <td class="p-3 font-bold" style="color: var(--term-text);">STRAIGHT</td>
                        <td class="p-3"><span class="hash-display" data-hash="01234ab"></span></td>
                        <td class="p-3 text-right font-bold" style="color: var(--term-win);">+2,500</td>
                        <td class="p-3 text-right hidden sm:table-cell">1 in 38K</td>
                        <td class="p-3 description-cell" data-calculation="3 × 24 × 16^2 / 16^7"><span class="description-text">Five sequential hex digits</span></td>
                    </tr>
                    <tr class="border-b hover:bg-white/5 odds-row" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                        <td class="p-3 font-bold" style="color: var(--term-text);">FIVE OF A KIND</td>
                        <td class="p-3"><span class="hash-display" data-hash="aaaaa12"></span></td>
                        <td class="p-3 text-right font-bold" style="color: var(--term-win);">+2,000</td>
                        <td class="p-3 text-right hidden sm:table-cell">1 in 3.8K</td>
                        <td class="p-3 description-cell" data-calculation="C(7,5)×16×15^2/16^7"><span class="description-text">Five of a kind</span></td>
                    </tr>
                    <tr class="border-b hover:bg-white/5 odds-row" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                        <td class="p-3 font-bold" style="color: var(--term-text);">DOUBLE TRIPLE</td>
                        <td class="p-3"><span class="hash-display" data-hash="aaabbb1"></span></td>
                        <td class="p-3 text-right font-bold" style="color: var(--term-win);">+1,000</td>
                        <td class="p-3 text-right hidden sm:table-cell">1 in 2K</td>
                        <td class="p-3 description-cell" data-calculation="35·16·15·(1/16)^7"><span class="description-text">Two threes of a kind</span></td>
                    </tr>
                    <tr class="border-b hover:bg-white/5 odds-row" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                        <td class="p-3 font-bold" style="color: var(--term-text);">THREE PAIR</td>
                        <td class="p-3"><span class="hash-display" data-hash="aabbcc1"></span></td>
                        <td class="p-3 text-right font-bold" style="color: var(--term-win);">+500</td>
                        <td class="p-3 text-right hidden sm:table-cell">1 in 1.5K</td>
                        <td class="p-3 description-cell" data-calculation="C(16,3)×16/16^7"><span class="description-text">Three consecutive pairs</span></td>
                    </tr>
                    <tr class="border-b hover:bg-white/5 odds-row" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                        <td class="p-3 font-bold" style="color: var(--term-text);">FULLER HOUSE</td>
                        <td class="p-3"><span class="hash-display" data-hash="aaabbcc"></span></td>
                        <td class="p-3 text-right font-bold" style="color: var(--term-win);">+400</td>
                        <td class="p-3 text-right hidden sm:table-cell">1 in 762</td>
                        <td class="p-3 description-cell" data-calculation="C(7,3)×C(4,2)×C(16,3)/16^7"><span class="description-text">3 + 2 + 2 of a kind</span></td>
                    </tr>
                    <tr class="border-b hover:bg-white/5 odds-row" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                        <td class="p-3 font-bold" style="color: var(--term-text);">ALPHABET SOUP</td>
                        <td class="p-3"><span class="hash-display" data-hash="abcdefa"></span></td>
                        <td class="p-3 text-right font-bold" style="color: var(--term-win);">+250</td>
                        <td class="p-3 text-right hidden sm:table-cell">1 in 960</td>
                        <td class="p-3 description-cell" data-calculation="(6/16)^7"><span class="description-text">Only letters (a-f)</span></td>
                    </tr>
                    <tr class="border-b hover:bg-white/5 odds-row" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                        <td class="p-3 font-bold" style="color: var(--term-text);">FOUR OF A KIND</td>
                        <td class="p-3"><span class="hash-display" data-hash="aaaa123"></span></td>
                        <td class="p-3 text-right font-bold" style="color: var(--term-win);">+200</td>
                        <td class="p-3 text-right hidden sm:table-cell">1 in 176</td>
                        <td class="p-3 description-cell" data-calculation="C(7,4)×16×15^3/16^7"><span class="description-text">Four of a kind</span></td>
                    </tr>
                    <tr class="border-b hover:bg-white/5 odds-row" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                        <td class="p-3 font-bold" style="color: var(--term-text);">FULL HOUSE</td>
                        <td class="p-3"><span class="hash-display" data-hash="aaabb12"></span></td>
                        <td class="p-3 text-right font-bold" style="color: var(--term-win);">+50</td>
                        <td class="p-3 text-right hidden sm:table-cell">1 in 29</td>
                        <td class="p-3 description-cell" data-calculation="C(7,3)×C(4,2)×16×15×14^2/16^7"><span class="description-text">3 + 2 of a kind (3-2-1-1)</span></td>
                    </tr>
                    <tr class="border-b hover:bg-white/5 odds-row" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                        <td class="p-3 font-bold" style="color: var(--term-text);">THREE OF A KIND</td>
                        <td class="p-3"><span class="hash-display" data-hash="aaa1234"></span></td>
                        <td class="p-3 text-right font-bold" style="color: var(--term-win);">+25</td>
                        <td class="p-3 text-right hidden sm:table-cell">1 in 15</td>
                        <td class="p-3 description-cell" data-calculation="C(7,3)×16×15^4/16^7"><span class="description-text">Three of a kind</span></td>
                    </tr>
                    <tr class="border-b hover:bg-white/5 odds-row" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                        <td class="p-3 font-bold" style="color: var(--term-text);">ALL NUMBERS</td>
                        <td class="p-3"><span class="hash-display" data-hash="1230984"></span></td>
                        <td class="p-3 text-right font-bold" style="color: var(--term-win);">+50</td>
                        <td class="p-3 text-right hidden sm:table-cell">1 in 485</td>
                        <td class="p-3 description-cell" data-calculation="(10/16)^7"><span class="description-text">Only numbers (0-9)</span></td>
                    </tr>
                    <tr class="border-b hover:bg-white/5 odds-row" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                        <td class="p-3 font-bold" style="color: var(--term-text);">TWO PAIR</td>
                        <td class="p-3"><span class="hash-display" data-hash="aa1bb2c"></span></td>
                        <td class="p-3 text-right font-bold" style="color: var(--term-win);">+25</td>
                        <td class="p-3 text-right hidden sm:table-cell">1 in 25</td>
                        <td class="p-3 description-cell" data-calculation="15×C(16,2)×16^3/16^7"><span class="description-text">Two consecutive pairs (e.g., aa...bb)</span></td>
                    </tr>
                    <tr class="border-b hover:bg-white/5 odds-row" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                        <td class="p-3 font-bold" style="color: var(--term-text);">ONE PAIR</td>
                        <td class="p-3"><span class="hash-display" data-hash="aa1b3d5"></span></td>
                        <td class="p-3 text-right font-bold" style="color: var(--term-text);">+10</td>
                        <td class="p-3 text-right hidden sm:table-cell">~1 in 4</td>
                        <td class="p-3 description-cell" data-calculation="6×16×15^5/16^7"><span class="description-text">One consecutive pair (e.g., aa...)</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if($discoveries->count() > 0)
        <div class="mt-8 border p-4 bg-black/30" style="border-color: var(--term-win);">
            <h3 class="text-lg sm:text-xl font-bold mb-4" style="color: var(--term-win);">🔓 DISCOVERED SECRETS</h3>
            <p class="mb-4 text-xs sm:text-sm" style="color: var(--term-dim);">Hidden patterns discovered by lucky players. More secrets remain undiscovered...</p>
            <div class="overflow-x-auto">
                <table class="w-full font-mono text-xs sm:text-sm">
                    <thead>
                        <tr class="border-b" style="color: var(--term-text); border-color: var(--term-win);">
                            <th class="p-2 text-left">SECRET</th>
                            <th class="p-2 text-right">PAYOUT</th>
                            <th class="p-2 text-left">DISCOVERED BY</th>
                            <th class="p-2 text-left hidden sm:table-cell">DATE</th>
                        </tr>
                    </thead>
                    <tbody style="color: var(--term-dim);">
                        @foreach($discoveries as $discovery)
                        <tr class="border-b hover:bg-white/5" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                            <td class="p-2 font-bold" style="color: var(--term-win);">{{ $discovery->secret_name }}</td>
                            <td class="p-2 text-right font-bold" style="color: var(--term-win);">
                                @php
                                    $payouts = ['BAD FOOD' => 42069, 'COFFEES' => 42069, 'DEAD BED' => 42069, 'DISEASE' => 42069, 'ICE COLD' => 6969, 'SAD FACE' => 6969, 'BOASTED' => 6969, 'BAD CAFE' => 1337, 'DEFACED' => 1337];
                                @endphp
                                +{{ number_format($payouts[$discovery->secret_name] ?? 0) }}
                            </td>
                            <td class="p-2">
                                <a href="https://github.com/{{ $discovery->user->github_username }}" target="_blank" class="hover:underline" style="color: var(--term-text);">
                                    {{ $discovery->user->github_username }}
                                </a>
                            </td>
                            <td class="p-2 hidden sm:table-cell">{{ $discovery->discovered_at->format('M j, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="mt-4 text-xs" style="color: var(--term-dim);">{{ 9 - $discoveries->count() }} secrets remain hidden...</p>
        </div>
        @endif

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="border p-4 bg-black/30" style="border-color: var(--term-accent);">
                <h3 class="text-base sm:text-lg font-bold mb-2" style="color: var(--term-text);">STATS FOR NERDS</h3>
                <ul class="space-y-1 text-xs sm:text-sm" style="color: var(--term-dim);">
                    <li><span style="color: var(--term-win);">Check the Stats page</span> for real-world pattern frequencies</li>
                    <li><span style="color: var(--term-text);">Three of a Kind</span> is the most common win (~7% of commits)</li>
                    <li><span style="color: var(--term-text);">Two Pair</span> hits about 1 in 45 commits (~2%)</li>
                    <li><span style="color: var(--term-text);">All Numbers</span> breaks even (~3% of commits)</li>
                </ul>
            </div>
            <div class="border p-4 bg-black/30" style="border-color: var(--term-accent);">
                <h3 class="text-base sm:text-lg font-bold mb-2" style="color: var(--term-text);">PATTERN RULES</h3>
                <ul class="space-y-1 text-xs sm:text-sm" style="color: var(--term-dim);">
                    <li><span style="color: var(--term-text);">Straights</span> - Sequential hex values (ascending or descending)</li>
                    <li><span style="color: var(--term-text);">Pairs</span> - Consecutive identical characters (aa, bb, etc.)</li>
                    <li><span style="color: var(--term-text);">Priority</span> - Checked from rarest to most common</li>
                    <li><span style="color: var(--term-text);">All hashes</span> - Every commit gets a fair shake!</li>
                </ul>
            </div>
        </div>
    </div>
</x-terminal-layout>
