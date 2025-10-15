<?php

namespace App\Livewire\Stats;

use App\Models\Play;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class GlobalStats extends Component
{
    public function render()
    {
        // Cache for 5 minutes
        $stats = Cache::remember('global-stats', 300, function () {
            $totalPlays = Play::count();
            $winningPlays = Play::where('payout', '>', 0)->count();

            return [
                'pattern_distribution' => $this->getPatternDistribution(),
                'total_plays' => $totalPlays,
                'total_payouts' => Play::sum('payout'),
                'win_rate' => $totalPlays > 0 ? round(($winningPlays / $totalPlays) * 100, 2) : 0,
                'rarest_patterns' => $this->getRarestPatterns(),
                'most_common_patterns' => $this->getMostCommonPatterns(),
            ];
        });

        return view('livewire.stats.global-stats', $stats);
    }

    private function getPatternDistribution(): array
    {
        // Exclude NO_WIN from the chart
        return Play::select('pattern_type', 'pattern_name')
            ->selectRaw('COUNT(*) as count')
            ->selectRaw('SUM(payout) as total_payout')
            ->where('pattern_type', '!=', 'NO_WIN')
            ->groupBy('pattern_type', 'pattern_name')
            ->orderByDesc('count')
            ->get()
            ->toArray();
    }

    private function getRarestPatterns(): array
    {
        // Get patterns with best profit margin (payout - wager)
        return Play::select('pattern_type', 'pattern_name')
            ->selectRaw('COUNT(*) as count')
            ->selectRaw('SUM(payout - 10) as net_profit')
            ->selectRaw('AVG(payout - 10) as avg_profit')
            ->where('pattern_type', '!=', 'NO_WIN')
            ->groupBy('pattern_type', 'pattern_name')
            ->orderByDesc('net_profit')
            ->limit(5)
            ->get()
            ->toArray();
    }

    private function getMostCommonPatterns(): array
    {
        // Get patterns with highest total payouts
        return Play::select('pattern_type', 'pattern_name')
            ->selectRaw('SUM(payout) as total_payout')
            ->selectRaw('COUNT(*) as count')
            ->where('pattern_type', '!=', 'NO_WIN')
            ->groupBy('pattern_type', 'pattern_name')
            ->orderByDesc('total_payout')
            ->limit(5)
            ->get()
            ->toArray();
    }
}
