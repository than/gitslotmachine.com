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
                            <th class="p-4">#</th>
                            <th class="p-4">PLAYER</th>
                            <th class="p-4">COMMITS</th>
                            <th class="p-4">BALANCE</th>
                            <th class="p-4">BIGGEST</th>
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
                            <td class="p-4 font-bold" style="color: var(--term-win);">
                                {{ $user->biggest_win }}
                                @if($user->biggest_win_pattern)
                                    <span class="text-xs opacity-75">({{ $user->biggest_win_pattern }})</span>
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
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <span class="hash-display" data-hash="{{ $play->commit_hash }}" style="color: var(--term-dim);"></span>
                        <span class="font-bold {{ $play->payout > 0 ? '' : 'text-red-400' }}"
                             style="{{ $play->payout > 0 ? 'color: var(--term-win);' : '' }}">
                            {{ $play->pattern_name }} {{ $play->payout > 0 ? '+' : '' }}{{ $play->payout }}
                        </span>
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
                            html += `<span class="px-0.5" style="background: var(--term-text); color: var(--term-bg); font-weight: 900;">${hash[i]}</span>`;
                        } else {
                            html += hash[i];
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
