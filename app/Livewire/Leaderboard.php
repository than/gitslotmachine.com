<?php

namespace App\Livewire;

use App\Models\Play;
use App\Models\User;
use Livewire\Component;

class Leaderboard extends Component
{
    public string $tab = 'daily';

    public string $sortBy = 'total_balance';

    public string $sortDirection = 'desc';

    public function sortBy(string $column): void
    {
        if ($this->sortBy === $column) {
            // Toggle direction if already sorting by this column
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // Default to desc for new column
            $this->sortBy = $column;
            $this->sortDirection = 'desc';
        }
    }

    public function render()
    {
        // Daily leaderboard
        $dailyLeaderboard = User::select('users.*')
            ->selectRaw('SUM(plays.payout - 10) as daily_winnings')
            ->selectRaw('COUNT(plays.id) as daily_commits')
            ->join('plays', 'plays.user_id', '=', 'users.id')
            ->whereDate('plays.played_at', today())
            ->groupBy('users.id')
            ->orderByDesc('daily_winnings')
            ->limit(100)
            ->get();

        // All-time leaderboard
        $allTimeLeaderboard = User::orderBy($this->sortBy, $this->sortDirection)
            ->limit(100)
            ->get();

        // Recent plays
        $recentPlays = Play::with(['user', 'repository'])
            ->orderByDesc('played_at')
            ->limit(20)
            ->get();

        return view('livewire.leaderboard', [
            'dailyLeaderboard' => $dailyLeaderboard,
            'allTimeLeaderboard' => $allTimeLeaderboard,
            'recentPlays' => $recentPlays,
        ]);
    }
}
