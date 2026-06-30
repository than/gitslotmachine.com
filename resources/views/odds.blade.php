<x-terminal-layout title="Odds" activeTab="odds">
    <x-slot name="meta">
        <meta name="description" content="Complete guide to winning patterns and payouts. From the Jackpot (250,000 points) down to One Pair (10). Exact, enumerated odds for every commit.">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url('/odds') }}">
        <meta property="og:title" content="Winning Patterns & Odds - Git Slot Machine">
        <meta property="og:description" content="Complete guide to winning patterns and payouts. From the Jackpot (250,000 points) down to One Pair (10). Exact, enumerated odds for every commit.">
        <meta property="og:image" content="https://gitslotmachine.com/og-image.png">

        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:url" content="{{ url('/odds') }}">
        <meta name="twitter:title" content="Winning Patterns & Odds - Git Slot Machine">
        <meta name="twitter:description" content="Complete guide to winning patterns and payouts. From the Jackpot (250,000 points) down to One Pair (10). Exact, enumerated odds for every commit.">
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
        <p class="text-xs mt-2" style="color: var(--term-dim);">Ruleset v{{ $rulesetVersion }} · payouts return ~{{ number_format($rtp * 100, 1) }}% over the long run · odds enumerated over all 16⁷ hashes</p>
    </header>

    <!-- Patterns Reference -->
    <div class="border-t pt-8" style="border-color: rgba(var(--term-accent-rgb), 0.3);">
        <h2 class="text-2xl sm:text-3xl font-bold mb-8" style="color: var(--term-text);">&gt; WINNING PATTERNS</h2>

        <p class="mb-6 text-sm sm:text-base" style="color: var(--term-dim);">Every commit costs 10 points. You start with 100. Patterns are checked rarest-first, so the best match always wins. Here's what you're playing for:</p>

        <div class="border bg-black/30 overflow-x-auto" style="border-color: var(--term-accent);">
            <table class="w-full font-mono text-xs sm:text-sm">
                <thead>
                    <tr class="border-b" style="color: var(--term-text); border-color: var(--term-accent);">
                        <th class="p-3 text-left">PATTERN</th>
                        <th class="p-3 text-left">EXAMPLE</th>
                        <th class="p-3 text-right">PAYOUT</th>
                        <th class="p-3 text-right hidden sm:table-cell">ODDS</th>
                        <th class="p-3 text-left hidden md:table-cell">PROBABILITY</th>
                    </tr>
                </thead>
                <tbody style="color: var(--term-dim);">
                    @foreach ($patterns as $pattern)
                        @php
                            $oneIn = $pattern['oneIn'];
                            $odds = $oneIn >= 1000000
                                ? rtrim(rtrim(number_format($oneIn / 1000000, 1), '0'), '.').'M'
                                : ($oneIn >= 1000
                                    ? rtrim(rtrim(number_format($oneIn / 1000, 1), '0'), '.').'K'
                                    : number_format($oneIn));
                        @endphp
                        <tr class="border-b hover:bg-white/5" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                            <td class="p-3 font-bold align-top" style="color: var(--term-text);">
                                {{ $pattern['name'] }}
                                <div class="text-xs font-normal mt-1" style="color: var(--term-dim);">{{ $pattern['description'] }}</div>
                            </td>
                            <td class="p-3 align-top"><span class="hash-display" data-hash="{{ $pattern['example'] }}"></span></td>
                            <td class="p-3 text-right font-bold align-top" style="color: {{ $pattern['payout'] >= 1000 ? 'var(--term-win)' : 'var(--term-text)' }};">+{{ number_format($pattern['payout']) }}</td>
                            <td class="p-3 text-right align-top hidden sm:table-cell">1 in {{ $odds }}</td>
                            <td class="p-3 align-top hidden md:table-cell"><span class="katex-formula" data-latex="{{ $pattern['formulaLatex'] }}"></span></td>
                        </tr>
                    @endforeach
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
                    <li><span style="color: var(--term-win);">{{ number_format($winRate * 100, 1) }}%</span> of commits win something — the other {{ number_format((1 - $winRate) * 100, 1) }}% are NO WIN</li>
                    <li><span style="color: var(--term-text);">Three of a Kind</span> is the most common win (~1 in 15, ~6.8%)</li>
                    <li><span style="color: var(--term-text);">One Pair</span> pays 10 — exactly your ante back (a push), ~1 in 5</li>
                    <li><span style="color: var(--term-text);">Hover the formulas</span> — every probability is exact over all 16⁷ hashes</li>
                </ul>
            </div>
            <div class="border p-4 bg-black/30" style="border-color: var(--term-accent);">
                <h3 class="text-base sm:text-lg font-bold mb-2" style="color: var(--term-text);">PATTERN RULES</h3>
                <ul class="space-y-1 text-xs sm:text-sm" style="color: var(--term-dim);">
                    <li><span style="color: var(--term-text);">Straights</span> - Sequential hex values in a row (ascending or descending)</li>
                    <li><span style="color: var(--term-text);">Pairs</span> - Must be <em>adjacent</em> identical characters (aa, bb)</li>
                    <li><span style="color: var(--term-text);">Priority</span> - Rarest pattern wins when a hash matches several</li>
                    <li><span style="color: var(--term-text);">Letters beat numbers</span> - ALPHABET SOUP outranks ALL NUMBERS (rarer)</li>
                </ul>
            </div>
        </div>
    </div>
</x-terminal-layout>
