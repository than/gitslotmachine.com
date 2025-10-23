<?php

namespace App\Console\Commands;

use App\Models\Play;
use App\Models\Repository;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RemovePlay extends Command
{
    protected $signature = 'play:remove
                            {commit_hash : The 7-character commit hash to remove}
                            {username : The GitHub username who made the play}';

    protected $description = 'Remove a play and recalculate all related statistics';

    public function handle(): int
    {
        $commitHash = strtolower($this->argument('commit_hash'));
        $username = $this->argument('username');

        // Find the user
        $user = User::where('github_username', $username)->first();

        if (!$user) {
            $this->error("User '{$username}' not found.");
            return 1;
        }

        // Find the play
        $play = Play::where('commit_hash', $commitHash)
            ->where('user_id', $user->id)
            ->with('repository')
            ->first();

        if (!$play) {
            $this->error("Play with hash '{$commitHash}' not found for user '{$username}'.");
            return 1;
        }

        // Display play information
        $this->info('Found play:');
        $this->table(
            ['Field', 'Value'],
            [
                ['ID', $play->id],
                ['User', $user->github_username],
                ['Repository', $play->repository->displayFullName()],
                ['Commit Hash', $play->commit_hash],
                ['Pattern', $play->pattern_name],
                ['Payout', $play->payout],
                ['Played At', $play->played_at->format('Y-m-d H:i:s')],
            ]
        );

        // Proceed with deletion
        $this->newLine();
        $this->info('Removing play and recalculating statistics...');

        DB::beginTransaction();

        try {
            $repository = $play->repository;

            // Delete the play
            $this->info("Removing play ID {$play->id}...");
            $play->delete();

            // Recalculate repository stats
            $this->info('Recalculating repository statistics...');
            $this->recalculateRepositoryStats($repository);

            // Recalculate user stats
            $this->info('Recalculating user statistics...');
            $this->recalculateUserStats($user);

            DB::commit();

            $this->info('✓ Play removed successfully!');
            $this->newLine();
            $this->info('Updated statistics:');

            $user->refresh();
            $repository->refresh();

            $this->table(
                ['Metric', 'Value'],
                [
                    ['User Total Balance', $user->total_balance],
                    ['User Total Commits', $user->total_commits],
                    ['User Biggest Win', $user->biggest_win ?? 'N/A'],
                    ['User Current Streak', $user->current_streak],
                    ['User Longest Streak', $user->longest_streak],
                    ['Repo Balance', $repository->balance],
                    ['Repo Total Commits', $repository->total_commits],
                ]
            );

            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Failed to remove play: ' . $e->getMessage());
            return 1;
        }
    }

    private function recalculateRepositoryStats(Repository $repository): void
    {
        $plays = Play::where('repository_id', $repository->id)
            ->orderBy('played_at')
            ->get();

        $balance = 100; // Starting balance
        $wager = 10;

        foreach ($plays as $play) {
            $balance = $balance - $wager + $play->payout;

            // Update the repo_balance_after for this play
            $play->update(['repo_balance_after' => $balance]);
        }

        $lastPlay = $plays->last();

        $repository->update([
            'balance' => $balance,
            'total_commits' => $plays->count(),
            'last_commit_hash' => $lastPlay?->commit_hash,
            'last_played_at' => $lastPlay?->played_at,
        ]);
    }

    private function recalculateUserStats(User $user): void
    {
        $plays = Play::where('user_id', $user->id)
            ->orderBy('played_at')
            ->get();

        $wager = 10;
        $totalBalance = 0;
        $biggestWin = 0;
        $biggestWinPattern = null;
        $biggestWinHash = null;
        $currentStreak = 0;
        $longestStreak = 0;
        $longestStreakEndedAt = null;

        foreach ($plays as $play) {
            // Calculate total balance
            $netWinnings = $play->payout - $wager;
            $totalBalance += $netWinnings;

            // Track biggest win
            if ($play->payout > $biggestWin) {
                $biggestWin = $play->payout;
                $biggestWinPattern = $play->pattern_name;
                $biggestWinHash = $play->commit_hash;
            }

            // Track streaks
            if ($play->payout > 0) {
                $currentStreak++;
                if ($currentStreak >= $longestStreak) {
                    $longestStreak = $currentStreak;
                    $longestStreakEndedAt = $play->played_at;
                }
            } else {
                $currentStreak = 0;
            }
        }

        $user->update([
            'total_balance' => $totalBalance,
            'total_commits' => $plays->count(),
            'biggest_win' => $biggestWin ?: 0,
            'biggest_win_pattern' => $biggestWinPattern,
            'biggest_win_hash' => $biggestWinHash,
            'current_streak' => $currentStreak,
            'longest_streak' => $longestStreak,
            'longest_streak_ended_at' => $longestStreakEndedAt,
        ]);
    }
}
