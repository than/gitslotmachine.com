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
            $totalPayouts = Play::sum('payout');

            // Calculate days since launch (October 15, 2025 - v1.0.0)
            $launchDate = \Carbon\Carbon::parse('2025-10-15');
            $daysSinceLaunch = max(1, now()->diffInDays($launchDate));

            // Calculate theoretical win rate based on pattern probabilities
            $theoreticalWinRate = $this->getTheoreticalWinRate();

            // Calculate avg per win (only winning plays)
            $avgPerWin = $winningPlays > 0 ? $totalPayouts / $winningPlays : 0;

            // Calculate net per play (payout - wager)
            $totalWagers = $totalPlays * 10;
            $netPerPlay = $totalPlays > 0 ? ($totalPayouts - $totalWagers) / $totalPlays : 0;

            return [
                'pattern_distribution' => $this->getPatternDistribution(),
                'total_plays' => $totalPlays,
                'total_payouts' => $totalPayouts,
                'win_rate' => $totalPlays > 0 ? number_format(($winningPlays / $totalPlays) * 100, 1) : '0.0',
                'plays_per_day' => number_format($totalPlays / $daysSinceLaunch, 1),
                'theoretical_win_rate' => number_format($theoreticalWinRate * 100, 1),
                'payouts_per_day' => number_format($totalPayouts / $daysSinceLaunch, 1),
                'avg_per_win' => number_format($avgPerWin, 2),
                'net_per_play' => number_format($netPerPlay, 2),
                'theoretical_vs_actual' => $this->getTheoreticalVsActual($totalPlays),
                'legendary_wins' => $this->getLegendaryWins(),
                'luckiest_repos' => $this->getLuckiestRepos(),
                'unluckiest_repos' => $this->getUnluckiestRepos(),
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

    private function getPatternNames(): array
    {
        return [
            'ALL_SAME' => 'JACKPOT',
            'SIX_OF_KIND' => 'HEXTET',
            'STRAIGHT_7' => 'LUCKY SEVEN',
            'FULLEST_HOUSE' => 'FULLEST HOUSE',
            'FIVE_OF_KIND' => 'FIVE OF A KIND',
            'STRAIGHT_6' => 'BIG STRAIGHT',
            'FOUR_OF_KIND' => 'FOUR OF A KIND',
            'ALL_LETTERS' => 'ALPHABET SOUP',
            'STRAIGHT_5' => 'STRAIGHT',
            'THREE_OF_KIND_PLUS_THREE' => 'DOUBLE TRIPLE',
            'FULL_HOUSE' => 'FULL HOUSE',
            'THREE_PAIR' => 'THREE PAIR',
            'THREE_OF_KIND' => 'THREE OF A KIND',
            'TWO_PAIR' => 'TWO PAIR',
            'ALL_NUMBERS' => 'ALL NUMBERS',
            'ONE_PAIR' => 'ONE PAIR',
        ];
    }

    private function getTheoreticalWinRate(): float
    {
        // Sum all winning pattern probabilities (everything except NO_WIN)
        $winningProbabilities = [
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
            'ALL_NUMBERS' => 1 / 485,
            'ONE_PAIR' => 1 / 7,
        ];

        return array_sum($winningProbabilities);
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
            'ALL_NUMBERS' => 1 / 485,
            'ONE_PAIR' => 1 / 7,
        ];

        // Get actual counts
        $actualCounts = Play::select('pattern_type')
            ->selectRaw('COUNT(*) as count')
            ->where('pattern_type', '!=', 'NO_WIN')
            ->groupBy('pattern_type')
            ->pluck('count', 'pattern_type')
            ->toArray();

        // Get proper pattern display names
        $patternNames = $this->getPatternNames();

        $comparison = [];
        foreach ($theoreticalProbabilities as $type => $probability) {
            $expectedCount = $totalPlays * $probability;
            $actualCount = $actualCounts[$type] ?? 0;
            $actualProbability = $totalPlays > 0 ? $actualCount / $totalPlays : 0;

            $comparison[] = [
                'pattern_type' => $type,
                'pattern_name' => $patternNames[$type] ?? $type,
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

    private function getLuckiestRepos(): array
    {
        // Get repos with highest net profit (total payouts - wagers)
        // Minimum 5 plays to qualify, net profit must be positive
        $repos = Play::select(
            'repositories.id',
            'repositories.owner',
            'repositories.name',
            'repositories.github_url',
            'repositories.balance'
        )
            ->selectRaw('COUNT(*) as total_plays')
            ->selectRaw('SUM(plays.payout) as total_payout')
            ->selectRaw('SUM(plays.payout - 10) as net_profit')
            ->selectRaw('ROUND(AVG(plays.payout), 1) as avg_payout')
            ->selectRaw('ROUND((COUNT(CASE WHEN plays.payout > 0 THEN 1 END) * 100.0 / COUNT(*)), 1) as win_rate')
            ->join('repositories', 'plays.repository_id', '=', 'repositories.id')
            ->groupBy('repositories.id', 'repositories.owner', 'repositories.name', 'repositories.github_url', 'repositories.balance')
            ->having('total_plays', '>=', 5)
            ->havingRaw('SUM(plays.payout - 10) > 0')
            ->orderByDesc('net_profit')
            ->limit(10)
            ->get()
            ->map(function ($repo) {
                $repo->repo_full_name = $repo->owner.'/'.$repo->name;

                return $repo;
            });

        return $repos->toArray();
    }

    private function getUnluckiestRepos(): array
    {
        // Get repos with lowest net profit (total payouts - wagers)
        // Minimum 5 plays to qualify, net profit must be negative
        $repos = Play::select(
            'repositories.id',
            'repositories.owner',
            'repositories.name',
            'repositories.github_url',
            'repositories.balance'
        )
            ->selectRaw('COUNT(*) as total_plays')
            ->selectRaw('SUM(plays.payout) as total_payout')
            ->selectRaw('SUM(plays.payout - 10) as net_profit')
            ->selectRaw('ROUND(AVG(plays.payout), 1) as avg_payout')
            ->selectRaw('ROUND((COUNT(CASE WHEN plays.payout > 0 THEN 1 END) * 100.0 / COUNT(*)), 1) as win_rate')
            ->join('repositories', 'plays.repository_id', '=', 'repositories.id')
            ->groupBy('repositories.id', 'repositories.owner', 'repositories.name', 'repositories.github_url', 'repositories.balance')
            ->having('total_plays', '>=', 5)
            ->havingRaw('SUM(plays.payout - 10) < 0')
            ->orderBy('net_profit', 'asc')
            ->limit(10)
            ->get()
            ->map(function ($repo) {
                $repo->repo_full_name = $repo->owner.'/'.$repo->name;

                return $repo;
            });

        return $repos->toArray();
    }
}
