<div wire:poll.30s class="space-y-8">
    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="border bg-black/30 p-6 font-mono" style="border-color: var(--term-accent);">
            <div class="text-xs uppercase tracking-wide mb-2" style="color: var(--term-dim);">Total Plays</div>
            <div class="text-3xl sm:text-4xl font-bold" style="color: var(--term-text);">{{ number_format($total_plays) }}</div>
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
            <h3 class="text-lg sm:text-xl font-bold mb-4" style="color: var(--term-text);">$ MOST COMMON</h3>
            <div class="space-y-3">
                @foreach($most_common_patterns as $pattern)
                <div class="flex justify-between items-center border-b pb-2" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                    <span style="color: var(--term-text);">{{ $pattern['pattern_name'] }}</span>
                    <span class="font-bold" style="color: var(--term-dim);">{{ number_format($pattern['count']) }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="border bg-black/30 p-6 font-mono" style="border-color: var(--term-accent);">
            <h3 class="text-lg sm:text-xl font-bold mb-4" style="color: var(--term-text);">$ RAREST</h3>
            <div class="space-y-3">
                @foreach($rarest_patterns as $pattern)
                <div class="flex justify-between items-center border-b pb-2" style="border-color: rgba(var(--term-accent-rgb), 0.2);">
                    <span style="color: var(--term-text);">{{ $pattern['pattern_name'] }}</span>
                    <span class="text-yellow-400 font-bold">{{ number_format($pattern['count']) }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

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
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        y: {
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
                        x: {
                            grid: {
                                color: accentColor + '20'
                            },
                            ticks: {
                                color: textColor,
                                font: {
                                    family: 'monospace'
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
