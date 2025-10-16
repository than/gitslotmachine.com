<div wire:poll.30s class="space-y-8">
    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="border bg-black/30 p-6 font-mono" style="border-color: var(--term-accent);">
            <div class="text-xs uppercase tracking-wide mb-2" style="color: var(--term-dim);">Total Plays</div>
            <div class="text-3xl sm:text-4xl font-bold" style="color: var(--term-text);">{{ number_format($total_plays) }}</div>
        </div>
        <div class="border bg-black/30 p-6 font-mono" style="border-color: var(--term-accent);">
            <div class="text-xs uppercase tracking-wide mb-2" style="color: var(--term-dim);">Win Rate</div>
            <div class="text-3xl sm:text-4xl font-bold" style="color: var(--term-win);">{{ $win_rate }}%</div>
        </div>
        <div class="border bg-black/30 p-6 font-mono" style="border-color: var(--term-accent);">
            <div class="text-xs uppercase tracking-wide mb-2" style="color: var(--term-dim);">Total Payouts</div>
            <div class="text-3xl sm:text-4xl font-bold" style="color: var(--term-text);">{{ number_format($total_payouts) }}</div>
        </div>
        <div class="border bg-black/30 p-6 font-mono" style="border-color: var(--term-accent);">
            <div class="text-xs uppercase tracking-wide mb-2" style="color: var(--term-dim);">Avg Per Play</div>
            <div class="text-3xl sm:text-4xl font-bold" style="color: var(--term-text);">{{ $total_plays > 0 ? round($total_payouts / $total_plays, 2) : 0 }}</div>
        </div>
    </div>

    @if(count($pattern_distribution) > 0)
    <!-- Pattern Distribution Chart -->
    <div class="border bg-black/30 p-6 font-mono" style="border-color: var(--term-accent);">
        <h3 class="text-xl sm:text-2xl font-bold mb-6" style="color: var(--term-text);">&gt; PATTERN DISTRIBUTION</h3>
        <canvas id="patternChart" height="100"></canvas>
    </div>

    <!-- Most Common Patterns -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="border bg-black/30 p-6 font-mono" style="border-color: var(--term-accent);">
            <h3 class="text-lg sm:text-xl font-bold mb-4" style="color: var(--term-text);">$ HIGHEST PAYOUTS</h3>
            <div class="space-y-3">
                @foreach($most_common_patterns as $pattern)
                <div class="flex justify-between items-center border-b pb-2" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                    <div>
                        <div style="color: var(--term-text);">{{ $pattern['pattern_name'] }}</div>
                        <div class="text-xs" style="color: var(--term-dim);">{{ number_format($pattern['count']) }} hits</div>
                    </div>
                    <span class="font-bold" style="color: var(--term-win);">{{ number_format($pattern['total_payout']) }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="border bg-black/30 p-6 font-mono" style="border-color: var(--term-accent);">
            <h3 class="text-lg sm:text-xl font-bold mb-4" style="color: var(--term-text);">$ RAREST PATTERNS</h3>
            <div class="space-y-3">
                @foreach($rarest_patterns as $pattern)
                <div class="flex justify-between items-center border-b pb-2" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                    <div>
                        <div style="color: var(--term-text);">{{ $pattern['pattern_name'] }}</div>
                        <div class="text-xs" style="color: var(--term-dim);">{{ number_format($pattern['count']) }} hits @ {{ $pattern['count'] > 0 ? number_format($pattern['net_profit'] / $pattern['count'], 1) : 0 }} avg</div>
                    </div>
                    <span class="font-bold {{ $pattern['net_profit'] >= 0 ? '' : 'text-red-400' }}" style="{{ $pattern['net_profit'] >= 0 ? 'color: var(--term-win);' : '' }}">
                        {{ $pattern['net_profit'] >= 0 ? '+' : '' }}{{ number_format($pattern['net_profit']) }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Theoretical vs Actual Comparison -->
    <div class="border bg-black/30 p-6 font-mono" style="border-color: var(--term-accent);">
        <h3 class="text-xl sm:text-2xl font-bold mb-6" style="color: var(--term-text);">&gt; THEORY VS REALITY</h3>
        <p class="mb-4 text-sm" style="color: var(--term-dim);">How do real-world probabilities compare to the math?</p>

        <div class="border bg-black/30 overflow-x-auto" style="border-color: var(--term-accent);">
            <table class="w-full font-mono text-xs sm:text-sm">
                <thead>
                    <tr class="border-b" style="color: var(--term-text); border-color: var(--term-accent);">
                        <th class="p-3 text-left">PATTERN</th>
                        <th class="p-3 text-right">EXPECTED</th>
                        <th class="p-3 text-right">ACTUAL</th>
                        <th class="p-3 text-right">VARIANCE</th>
                        <th class="p-3 text-right hidden sm:table-cell">ACTUAL %</th>
                    </tr>
                </thead>
                <tbody style="color: var(--term-dim);">
                    @foreach($theoretical_vs_actual as $stat)
                    <tr class="border-b hover:bg-white/5" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                        <td class="p-3 font-bold" style="color: var(--term-text);">{{ $stat['pattern_name'] }}</td>
                        <td class="p-3 text-right">{{ number_format($stat['expected_count'], 1) }}</td>
                        <td class="p-3 text-right font-bold" style="color: {{ $stat['actual_count'] > 0 ? 'var(--term-win)' : 'var(--term-dim)' }};">{{ number_format($stat['actual_count'], 1) }}</td>
                        <td class="p-3 text-right {{ $stat['variance'] > 0 ? '' : 'opacity-50' }}" style="color: {{ $stat['variance'] > 0 ? 'var(--term-win)' : 'var(--term-dim)' }};">
                            {{ $stat['variance'] > 0 ? '+' : '' }}{{ number_format($stat['variance'], 1) }}
                        </td>
                        <td class="p-3 text-right hidden sm:table-cell">{{ number_format($stat['actual_probability'] * 100, 3) }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Legendary Wins -->
    @if(count($legendary_wins) > 0)
    <div class="border bg-black/30 p-6 font-mono" style="border-color: var(--term-accent);">
        <h3 class="text-xl sm:text-2xl font-bold mb-6" style="color: var(--term-text);">🏆 LEGENDARY WINS 🏆</h3>
        <p class="mb-4 text-sm" style="color: var(--term-dim);">The rarest patterns that have actually been hit</p>

        <div class="space-y-3">
            @foreach($legendary_wins as $win)
            <div class="border bg-black/40 p-4" style="border-color: var(--term-accent);">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-lg sm:text-xl font-bold" style="color: var(--term-win);">{{ $win['pattern_name'] }}</span>
                            <code class="text-base sm:text-lg" style="color: var(--term-text);">{{ $win['hash_short'] }}</code>
                        </div>
                        <div class="flex items-center gap-4 text-xs sm:text-sm">
                            <a href="https://github.com/{{ $win['github_username'] }}" target="_blank" class="hover:underline" style="color: var(--term-accent);">@{{ $win['github_username'] }}</a>
                            <span style="color: var(--term-dim);">{{ \Carbon\Carbon::parse($win['played_at'])->diffForHumans() }}</span>
                            @if($win['repo_url'] && $win['repo_url'] !== 'private')
                            <a href="{{ $win['repo_url'] }}/commit/{{ $win['hash_full'] }}" target="_blank" class="hover:underline hidden sm:inline" style="color: var(--term-dim);">{{ $win['repo_owner'] }}/{{ $win['repo_name'] }}</a>
                            @endif
                        </div>
                    </div>
                    <div class="text-2xl sm:text-3xl font-bold" style="color: var(--term-win);">+{{ number_format($win['payout']) }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Luckiest Repos -->
    @if(isset($luckiest_repos) && count($luckiest_repos) > 0)
    <div class="border bg-black/30 p-6 font-mono" style="border-color: var(--term-accent);">
        <h3 class="text-xl sm:text-2xl font-bold mb-6" style="color: var(--term-text);">🍀 LUCKIEST REPOS 🍀</h3>
        <p class="mb-4 text-sm" style="color: var(--term-dim);">Repositories with the highest net profit (min. 5 plays)</p>

        <div class="space-y-3">
            @foreach($luckiest_repos as $repo)
            <div class="border bg-black/40 p-4" style="border-color: var(--term-accent);">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <a href="{{ $repo['github_url'] }}" target="_blank" class="text-lg sm:text-xl font-bold hover:underline" style="color: var(--term-win);">{{ $repo['repo_full_name'] }}</a>
                            <span class="text-xs px-2 py-1 border rounded" style="color: var(--term-dim); border-color: var(--term-accent);">Balance: {{ number_format($repo['balance']) }}</span>
                        </div>
                        <div class="flex items-center gap-4 text-xs sm:text-sm">
                            <span style="color: var(--term-dim);">{{ number_format($repo['total_plays']) }} plays</span>
                            <span style="color: var(--term-dim);">{{ $repo['win_rate'] }}% win rate</span>
                            <span style="color: var(--term-dim);">{{ number_format($repo['avg_payout'], 1) }} avg payout</span>
                        </div>
                    </div>
                    <div class="text-2xl sm:text-3xl font-bold" style="color: var(--term-win);">{{ $repo['net_profit'] >= 0 ? '+' : '' }}{{ number_format($repo['net_profit']) }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        if (document.getElementById('patternChart')) {
            const ctx = document.getElementById('patternChart').getContext('2d');
            const patternData = @json($pattern_distribution);

            // Get current theme colors
            const styles = getComputedStyle(document.documentElement);
            const accentColor = styles.getPropertyValue('--term-accent').trim();
            const textColor = styles.getPropertyValue('--term-text').trim();

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: patternData.map(p => p.pattern_name),
                    datasets: [{
                        label: 'Frequency',
                        data: patternData.map(p => p.count),
                        backgroundColor: accentColor + '80',
                        borderColor: accentColor,
                        borderWidth: 2
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                color: accentColor + '20'
                            },
                            ticks: {
                                color: textColor,
                                font: {
                                    family: 'monospace'
                                }
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: textColor,
                                font: {
                                    family: 'monospace',
                                    size: 11
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
    </script>
    @else
    <div class="border bg-black/30 p-12 text-center font-mono" style="border-color: var(--term-accent);">
        <p class="text-lg sm:text-xl" style="color: var(--term-dim);">&gt; No plays yet. Start playing to see statistics!</p>
    </div>
    @endif
</div>
