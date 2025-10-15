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
            return [
                'pattern_distribution' => $this->getPatternDistribution(),
                'total_plays' => Play::count(),
                'total_payouts' => Play::sum('payout'),
                'rarest_patterns' => $this->getRarestPatterns(),
                'most_common_patterns' => $this->getMostCommonPatterns(),
            ];
        });

        return view('livewire.stats.global-stats', $stats);
    }

    private function getPatternDistribution(): array
    {
        return Play::select('pattern_type', 'pattern_name')
            ->selectRaw('COUNT(*) as count')
            ->selectRaw('SUM(payout) as total_payout')
            ->groupBy('pattern_type', 'pattern_name')
            ->orderByDesc('count')
            ->get()
            ->toArray();
    }

    private function getRarestPatterns(): array
    {
        return Play::select('pattern_type', 'pattern_name')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('pattern_type', 'pattern_name')
            ->orderBy('count', 'asc')
            ->limit(5)
            ->get()
            ->toArray();
    }

    private function getMostCommonPatterns(): array
    {
        return Play::select('pattern_type', 'pattern_name')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('pattern_type', 'pattern_name')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->toArray();
    }
}
