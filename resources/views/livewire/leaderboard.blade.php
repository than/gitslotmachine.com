<div wire:poll.5s class="space-y-8">
    <!-- Leaderboard Tabs -->
    <div>
        <div class="border bg-black/30 p-4" style="border-color: var(--term-accent);">
            <nav class="flex space-x-8 justify-center">
                <button
                    wire:click="$set('tab', 'daily')"
                    class="py-2 px-4 font-mono font-bold transition-all"
                    style="color: {{ $tab === 'daily' ? 'var(--term-text)' : 'var(--term-dim)' }}; {{ $tab === 'daily' ? 'background: rgba(var(--term-accent-rgb), 0.2); border: 1px solid var(--term-accent);' : '' }}">
                    [DAILY]
                </button>
                <button
                    wire:click="$set('tab', 'alltime')"
                    class="py-2 px-4 font-mono font-bold transition-all"
                    style="color: {{ $tab === 'alltime' ? 'var(--term-text)' : 'var(--term-dim)' }}; {{ $tab === 'alltime' ? 'background: rgba(var(--term-accent-rgb), 0.2); border: 1px solid var(--term-accent);' : '' }}">
                    [ALL-TIME]
                </button>
                <button
                    wire:click="$set('tab', 'streaks')"
                    class="py-2 px-4 font-mono font-bold transition-all"
                    style="color: {{ $tab === 'streaks' ? 'var(--term-text)' : 'var(--term-dim)' }}; {{ $tab === 'streaks' ? 'background: rgba(var(--term-accent-rgb), 0.2); border: 1px solid var(--term-accent);' : '' }}">
                    [STREAKS]
                </button>
            </nav>
        </div>

        <!-- Daily Leaderboard -->
        @if($tab === 'daily')
        <div class="mt-8 border bg-black/30" style="border-color: var(--term-accent);">
            @if($dailyLeaderboard->isEmpty())
            <div class="text-center py-12" style="color: var(--term-dim);">
                &gt; No plays today yet. Be the first!
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full font-mono">
                    <thead>
                        <tr class="text-left border-b" style="color: var(--term-text); border-color: var(--term-accent);">
                            <th class="p-4">#</th>
                            <th class="p-4">PLAYER</th>
                            <th class="p-4">COMMITS</th>
                            <th class="p-4">NET</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dailyLeaderboard as $index => $user)
                        <tr class="border-b hover:bg-white/5" style="border-color: rgba(var(--term-accent-rgb), 0.3);">
                            <td class="p-4 text-xl" style="color: var(--term-text);">{{ $index + 1 }}</td>
                            <td class="p-4 font-bold" style="color: var(--term-text);">{{ $user->github_username }}</td>
                            <td class="p-4" style="color: var(--term-dim);">{{ $user->daily_commits }}</td>
                            <td class="p-4 font-bold {{ $user->daily_winnings >= 0 ? '' : 'text-red-400' }}"
                                style="{{ $user->daily_winnings >= 0 ? 'color: var(--term-win);' : '' }}">
                                {{ $user->daily_winnings >= 0 ? '+' : '' }}{{ $user->daily_winnings }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
        @endif

        <!-- All-Time Leaderboard -->
        @if($tab === 'alltime')
        <div class="mt-8 border bg-black/30" style="border-color: var(--term-accent);">
            @if($allTimeLeaderboard->isEmpty())
            <div class="text-center py-12" style="color: var(--term-dim);">
                &gt; No plays yet. Install git-slot-machine to get started!
            </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full font-mono">
                    <thead>
                        <tr class="text-left border-b" style="color: var(--term-text); border-color: var(--term-accent);">
                            <th class="p-4 w-16">#</th>
                            <th class="p-4 cursor-pointer hover:opacity-75 w-1/4" wire:click="sortBy('github_username')">
                                PLAYER
                                @if($sortBy === 'github_username')
                                    <span class="text-xs">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                @endif
                            </th>
                            <th class="p-4 cursor-pointer hover:opacity-75 w-1/6" wire:click="sortBy('total_commits')">
                                COMMITS
                                @if($sortBy === 'total_commits')
                                    <span class="text-xs">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                @endif
                            </th>
                            <th class="p-4 cursor-pointer hover:opacity-75 w-1/6" wire:click="sortBy('total_balance')">
                                BALANCE
                                @if($sortBy === 'total_balance')
                                    <span class="text-xs">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                @endif
                            </th>
                            <th class="p-4 cursor-pointer hover:opacity-75 whitespace-nowrap" wire:click="sortBy('biggest_win')">
                                BIGGEST WIN
                                @if($sortBy === 'biggest_win')
                                    <span class="text-xs">{{ $sortDirection === 'asc' ? '▲' : '▼' }}</span>
                                @endif
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allTimeLeaderboard as $index => $user)
                        <tr class="border-b hover:bg-white/5" style="border-color: rgba(var(--term-accent-rgb), 0.3);">
                            <td class="p-4 text-xl" style="color: var(--term-text);">{{ $index + 1 }}</td>
                            <td class="p-4 font-bold" style="color: var(--term-text);">{{ $user->github_username }}</td>
                            <td class="p-4" style="color: var(--term-dim);">{{ $user->total_commits }}</td>
                            <td class="p-4 font-bold {{ $user->total_balance >= 0 ? '' : 'text-red-400' }}"
                                style="{{ $user->total_balance >= 0 ? 'color: var(--term-win);' : '' }}">
                                {{ $user->total_balance }}
                            </td>
                            <td class="p-4 whitespace-nowrap">
                                @if($user->biggest_win > 0)
                                    <span class="font-bold" style="color: var(--term-win);">
                                        {{ $user->biggest_win_pattern }} +{{ $user->biggest_win }}
                                    </span>
                                    @if($user->biggest_win_hash)
                                        <span class="text-xs ml-2 font-mono hash-display" data-hash="{{ $user->biggest_win_hash }}" style="color: var(--term-text);"></span>
                                    @endif
                                @else
                                    <span style="color: var(--term-dim);">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
        @endif

        <!-- Streaks Leaderboard -->
        @if($tab === 'streaks')
        <div class="mt-8 border bg-black/30" style="border-color: var(--term-accent);">
            @if($streaksLeaderboard->isEmpty())
            <div class="text-center py-12" style="color: var(--term-dim);">
                &gt; No streaks yet. Start winning to build a streak!
            </div>
            @else
            <div class="space-y-2 p-4">
                @foreach($streaksLeaderboard as $index => $user)
                <div class="border bg-black/20" style="border-color: var(--term-accent);">
                    <!-- Streak Header (Clickable) -->
                    <button
                        wire:click="toggleStreak({{ $user->id }})"
                        class="w-full px-4 py-3 flex items-center justify-between hover:bg-white/5 transition font-mono">
                        <div class="flex items-center gap-4">
                            <span class="text-xl font-bold" style="color: var(--term-text);">{{ $index + 1 }}</span>
                            <span class="font-bold" style="color: var(--term-text);">{{ $user->github_username }}</span>
                            <span class="font-bold" style="color: var(--term-win);">{{ $user->longest_streak }} wins</span>
                            @if($user->longest_streak_ended_at)
                                <span class="text-xs" style="color: var(--term-dim);">{{ $user->longest_streak_ended_at->diffForHumans() }}</span>
                            @else
                                <span class="text-xs" style="color: var(--term-win);">ACTIVE</span>
                            @endif
                        </div>
                        <span class="text-xl" style="color: var(--term-text);">{{ $expandedStreak === $user->id ? '▼' : '▶' }}</span>
                    </button>

                    <!-- Streak Details (Accordion Content) -->
                    @if($expandedStreak === $user->id)
                    <div class="px-4 pb-4 border-t" style="border-color: rgba(var(--term-accent-rgb), 0.3);">
                        <div class="mt-3 space-y-2">
                            @forelse($user->streak_plays as $play)
                            <div class="flex items-center justify-between py-2 px-3 bg-black/30 rounded text-sm">
                                <div class="flex items-center gap-3">
                                    <span class="font-bold" style="color: var(--term-win);">{{ $play->pattern_name }}</span>
                                    <span style="color: var(--term-dim);"> @ </span>
                                    <span style="color: var(--term-text);">{{ $play->repository->owner }}/{{ $play->repository->name }}</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="font-bold" style="color: var(--term-win);">+{{ $play->payout }}</span>
                                    <span class="hash-display text-xs" data-hash="{{ $play->commit_hash }}" style="color: var(--term-dim);"></span>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-4" style="color: var(--term-dim);">
                                &gt; No winning plays found
                            </div>
                            @endforelse
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
        </div>
        @endif
    </div>

    <!-- Recent Plays Feed -->
    <div class="mt-16">
        <h2 class="text-2xl sm:text-3xl font-bold mb-6" style="color: var(--term-text);">&gt; RECENT PLAYS</h2>
        @if($recentPlays->isEmpty())
        <div class="text-center py-12 border bg-black/30" style="border-color: var(--term-accent);">
            <p style="color: var(--term-dim);">&gt; No plays yet. Install git-slot-machine and start playing!</p>
        </div>
        @else
        <div class="space-y-3">
            @foreach($recentPlays as $play)
            <div class="border bg-black/30 px-3 py-2 hover:bg-white/5 font-mono text-sm" style="border-color: var(--term-accent);">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex-1 truncate">
                        <span class="font-bold" style="color: var(--term-text);">{{ $play->user->github_username }}</span>
                        <span style="color: var(--term-dim);"> @ </span>
                        <span style="color: var(--term-text);">{{ $play->repository->owner }}/{{ $play->repository->name }}</span>
                        <span style="color: var(--term-dim);"> • {{ $play->played_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        @php
                            $net = $play->payout - 10;
                        @endphp
                        <span class="font-bold {{ $net >= 0 ? '' : 'text-red-400' }}"
                             style="{{ $net >= 0 ? 'color: var(--term-win);' : '' }}">
                            {{ $play->pattern_name }} {{ $net >= 0 ? '+' : '' }}{{ $net }}
                        </span>
                        <span class="hash-display" data-hash="{{ $play->commit_hash }}" style="color: var(--term-dim);"></span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <script>
        // Highlight all hashes on page load and Livewire updates
        function highlightHashes() {
            document.querySelectorAll('.hash-display').forEach(el => {
                const hash = el.dataset.hash;
                if (!hash) return;

                try {
                    const pattern = window.detectPattern(hash);
                    const highlights = pattern.highlightIndices || [];

                    let html = '';
                    for (let i = 0; i < hash.length; i++) {
                        if (highlights.includes(i)) {
                            html += `<span class="inline-block px-1" style="background: var(--term-text); color: var(--term-bg); font-weight: 900;">${hash[i]}</span>`;
                        } else {
                            html += `<span class="inline-block px-1">${hash[i]}</span>`;
                        }
                    }
                    el.innerHTML = html;
                } catch (e) {
                    el.textContent = hash;
                }
            });
        }

        // Run on page load
        document.addEventListener('DOMContentLoaded', highlightHashes);

        // Run after Livewire updates
        document.addEventListener('livewire:init', () => {
            Livewire.hook('morph.updated', () => {
                highlightHashes();
            });
        });
    </script>
</div>
