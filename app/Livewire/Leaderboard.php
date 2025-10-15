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

    public ?int $expandedStreak = null;

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

    public function toggleStreak(int $userId): void
    {
        $this->expandedStreak = $this->expandedStreak === $userId ? null : $userId;
    }

    public function render()
    {
        // Daily leaderboard - optimized to use index (no DATE() function)
        $todayStart = today()->startOfDay();
        $todayEnd = today()->endOfDay();

        $dailyLeaderboard = User::select('users.*')
            ->selectRaw('SUM(plays.payout - 10) as daily_winnings')
            ->selectRaw('COUNT(plays.id) as daily_commits')
            ->join('plays', 'plays.user_id', '=', 'users.id')
            ->whereBetween('plays.played_at', [$todayStart, $todayEnd])
            ->groupBy('users.id')
            ->orderByDesc('daily_winnings')
            ->limit(100)
            ->get();

        // All-time leaderboard
        $allTimeLeaderboard = User::orderBy($this->sortBy, $this->sortDirection)
            ->limit(100)
            ->get();

        // Longest streaks leaderboard
        $streaksLeaderboard = User::where('longest_streak', '>', 0)
            ->orderByDesc('longest_streak')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                // Get recent winning plays for this user
                $user->streak_plays = Play::with('repository')
                    ->where('user_id', $user->id)
                    ->where('payout', '>', 0)
                    ->orderByDesc('played_at')
                    ->limit($user->longest_streak)
                    ->get();

                return $user;
            });

        // Recent plays
        $recentPlays = Play::with(['user', 'repository'])
            ->orderByDesc('played_at')
            ->limit(20)
            ->get();

        return view('livewire.leaderboard', [
            'dailyLeaderboard' => $dailyLeaderboard,
            'allTimeLeaderboard' => $allTimeLeaderboard,
            'streaksLeaderboard' => $streaksLeaderboard,
            'recentPlays' => $recentPlays,
        ]);
    }
}
