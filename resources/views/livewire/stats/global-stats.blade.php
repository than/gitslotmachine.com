<div wire:poll.30s class="space-y-8">
    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gray-800 rounded-lg p-6">
            <div class="text-gray-400 text-sm">Total Plays</div>
            <div class="text-4xl font-bold text-white mt-2">{{ number_format($total_plays) }}</div>
        </div>
        <div class="bg-gray-800 rounded-lg p-6">
            <div class="text-gray-400 text-sm">Total Payouts</div>
            <div class="text-4xl font-bold text-green-400 mt-2">{{ number_format($total_payouts) }}</div>
        </div>
        <div class="bg-gray-800 rounded-lg p-6">
            <div class="text-gray-400 text-sm">Average Per Play</div>
            <div class="text-4xl font-bold text-cyan-400 mt-2">{{ $total_plays > 0 ? round($total_payouts / $total_plays, 2) : 0 }}</div>
        </div>
    </div>

    @if(count($pattern_distribution) > 0)
    <!-- Pattern Distribution Chart -->
    <div class="bg-gray-800 rounded-lg p-6">
        <h3 class="text-2xl font-bold text-white mb-6">Pattern Distribution</h3>
        <canvas id="patternChart" height="100"></canvas>
    </div>

    <!-- Most Common Patterns -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gray-800 rounded-lg p-6">
            <h3 class="text-xl font-bold text-white mb-4">Most Common</h3>
            <div class="space-y-3">
                @foreach($most_common_patterns as $pattern)
                <div class="flex justify-between items-center">
                    <span class="text-white">{{ $pattern['pattern_name'] }}</span>
                    <span class="text-gray-400">{{ number_format($pattern['count']) }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg p-6">
            <h3 class="text-xl font-bold text-white mb-4">Rarest Patterns</h3>
            <div class="space-y-3">
                @foreach($rarest_patterns as $pattern)
                <div class="flex justify-between items-center">
                    <span class="text-white">{{ $pattern['pattern_name'] }}</span>
                    <span class="text-yellow-400">{{ number_format($pattern['count']) }}</span>
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

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: patternData.map(p => p.pattern_name),
                    datasets: [{
                        label: 'Frequency',
                        data: patternData.map(p => p.count),
                        backgroundColor: '#06b6d4',
                        borderColor: '#0891b2',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#374151'
                            },
                            ticks: {
                                color: '#9ca3af'
                            }
                        },
                        x: {
                            grid: {
                                color: '#374151'
                            },
                            ticks: {
                                color: '#9ca3af'
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
    <div class="bg-gray-800 rounded-lg p-12 text-center">
        <p class="text-gray-400 text-xl">No plays yet. Start playing to see statistics!</p>
    </div>
    @endif
</div>
