<?php

namespace App\Console\Commands;

use App\Models\Play;
use App\Models\User;
use Illuminate\Console\Command;

class BackfillStreaks extends Command
{
    protected $signature = 'streaks:backfill';

    protected $description = 'Backfill streak data from historical plays';

    public function handle(): int
    {
        $this->info('Backfilling streaks from historical plays...');

        $users = User::all();
        $bar = $this->output->createProgressBar($users->count());

        foreach ($users as $user) {
            $this->backfillUserStreaks($user);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Streak backfill complete!');

        return Command::SUCCESS;
    }

    private function backfillUserStreaks(User $user): void
    {
        // Get all plays for this user in chronological order
        $plays = Play::where('user_id', $user->id)
            ->orderBy('played_at')
            ->get();

        $currentStreak = 0;
        $longestStreak = 0;
        $longestStreakEndedAt = null;

        foreach ($plays as $play) {
            if ($play->payout > 0) {
                // Win: increment current streak
                $currentStreak++;

                // Update longest if current exceeds it
                if ($currentStreak > $longestStreak) {
                    $longestStreak = $currentStreak;
                }
            } else {
                // Loss: record when longest streak ended and reset
                if ($currentStreak > 0 && $currentStreak === $longestStreak) {
                    $longestStreakEndedAt = $play->played_at;
                }
                $currentStreak = 0;
            }
        }

        // Update user with calculated streaks
        $user->update([
            'current_streak' => $currentStreak,
            'longest_streak' => $longestStreak,
            'longest_streak_ended_at' => $longestStreakEndedAt,
        ]);
    }
}
