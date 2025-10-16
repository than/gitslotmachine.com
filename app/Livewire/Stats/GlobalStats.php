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
                'theoretical_vs_actual' => $this->getTheoreticalVsActual($totalPlays),
                'legendary_wins' => $this->getLegendaryWins(),
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

    private function getTheoreticalVsActual(int $totalPlays): array
    {
        // Theoretical probabilities (approximate)
        $theoreticalProbabilities = [
            'ALL_SAME' => 1 / 16777216,
            'SIX_OF_KIND' => 1 / 159784,
            'STRAIGHT_7' => 1 / 2500000,
            'FULLEST_HOUSE' => 1 / 31956,
            'FIVE_OF_KIND' => 1 / 7989,
            'STRAIGHT_6' => 1 / 280000,
            'FOUR_OF_KIND' => 1 / 799,
            'ALL_LETTERS' => 1 / 959,
            'STRAIGHT_5' => 1 / 9000,
            'THREE_OF_KIND_PLUS_THREE' => 1 / 2000,
            'FULL_HOUSE' => 1 / 1000,
            'THREE_PAIR' => 1 / 1600,
            'THREE_OF_KIND' => 1 / 133,
            'TWO_PAIR' => 1 / 45,
            'ALL_NUMBERS' => 1 / 27,
        ];

        // Get actual counts
        $actualCounts = Play::select('pattern_type')
            ->selectRaw('COUNT(*) as count')
            ->where('pattern_type', '!=', 'NO_WIN')
            ->groupBy('pattern_type')
            ->pluck('count', 'pattern_type')
            ->toArray();

        // Get pattern names
        $patternNames = Play::select('pattern_type', 'pattern_name')
            ->groupBy('pattern_type', 'pattern_name')
            ->pluck('pattern_name', 'pattern_type')
            ->toArray();

        $comparison = [];
        foreach ($theoreticalProbabilities as $type => $probability) {
            $expectedCount = $totalPlays * $probability;
            $actualCount = $actualCounts[$type] ?? 0;
            $actualProbability = $totalPlays > 0 ? $actualCount / $totalPlays : 0;

            $comparison[] = [
                'pattern_type' => $type,
                'pattern_name' => $patternNames[$type] ?? ucwords(str_replace('_', ' ', strtolower($type))),
                'theoretical_probability' => $probability,
                'actual_count' => $actualCount,
                'expected_count' => $expectedCount,
                'actual_probability' => $actualProbability,
                'variance' => $actualCount - $expectedCount,
            ];
        }

        // Sort by payout/rarity (highest theoretical probability = rarest)
        usort($comparison, function ($a, $b) {
            return $a['theoretical_probability'] <=> $b['theoretical_probability'];
        });

        return $comparison;
    }

    private function getLegendaryWins(): array
    {
        // Get the rarest patterns that have actually occurred
        $legendaryPatterns = ['ALL_SAME', 'SIX_OF_KIND', 'STRAIGHT_7', 'FULLEST_HOUSE', 'FIVE_OF_KIND'];

        // PostgreSQL-compatible ordering using CASE
        $plays = Play::select(
            'plays.*',
            'users.github_username',
            'repositories.github_url as repo_url',
            'repositories.owner as repo_owner',
            'repositories.name as repo_name'
        )
            ->join('users', 'plays.user_id', '=', 'users.id')
            ->join('repositories', 'plays.repository_id', '=', 'repositories.id')
            ->whereIn('plays.pattern_type', $legendaryPatterns)
            ->orderByRaw("CASE
                WHEN plays.pattern_type = 'ALL_SAME' THEN 1
                WHEN plays.pattern_type = 'SIX_OF_KIND' THEN 2
                WHEN plays.pattern_type = 'STRAIGHT_7' THEN 3
                WHEN plays.pattern_type = 'FULLEST_HOUSE' THEN 4
                WHEN plays.pattern_type = 'FIVE_OF_KIND' THEN 5
                ELSE 6
            END")
            ->orderByDesc('plays.played_at')
            ->limit(10)
            ->get()
            ->map(function ($play) {
                // Add computed attributes
                $play->hash_short = $play->commit_hash;
                $play->hash_full = $play->commit_hash;

                return $play;
            });

        return $plays->toArray();
    }
}
