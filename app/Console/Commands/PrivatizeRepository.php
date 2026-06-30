<?php

namespace App\Console\Commands;

use App\Models\Play;
use App\Models\Repository;
use App\Services\Ruleset;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PrivatizeRepository extends Command
{
    protected $signature = 'repo:privatize
                            {owner : The repository owner whose plays should be anonymized}
                            {--dry-run : Show what would change without writing}';

    protected $description = 'Anonymize a repository owner to private/private (folds its plays into the private repo) so it no longer appears on the public stats page';

    public function handle(): int
    {
        $owner = $this->argument('owner');
        $dryRun = (bool) $this->option('dry-run');

        $repos = Repository::where('owner', $owner)->get();

        if ($repos->isEmpty()) {
            $this->info("No repositories found for owner '{$owner}'.");

            return self::SUCCESS;
        }

        $playCount = Play::whereIn('repository_id', $repos->pluck('id'))->count();

        $this->info(($dryRun ? '[DRY RUN] ' : '')."Anonymizing {$repos->count()} repo(s) for '{$owner}' → private/private ({$playCount} plays):");
        $this->table(
            ['Repo ID', 'Owner/Name', 'Plays'],
            $repos->map(fn (Repository $repo) => [
                $repo->id,
                $repo->owner.'/'.$repo->name,
                $repo->plays()->count(),
            ])->all()
        );

        $existingPrivate = Repository::where('owner', 'private')->where('name', 'private')->first();
        $this->line($existingPrivate
            ? "Plays will be folded into existing private repo (ID {$existingPrivate->id})."
            : 'A new private/private repo will be created to hold the plays.');

        if ($dryRun) {
            $this->newLine();
            $this->info('Dry run only — no changes written.');

            return self::SUCCESS;
        }

        DB::transaction(function () use ($repos, $existingPrivate) {
            // Reuse one repo as the private/private target (keeps a stable FK target), or the
            // existing private repo if there already is one.
            $target = $existingPrivate ?? $repos->first();
            $sourceIds = $repos->pluck('id')->reject(fn ($id) => $id === $target->id)->values();

            $target->update(['owner' => 'private', 'name' => 'private', 'github_url' => 'private']);

            if ($sourceIds->isNotEmpty()) {
                Play::whereIn('repository_id', $sourceIds)->update(['repository_id' => $target->id]);
                Repository::whereIn('id', $sourceIds)->delete();
            }

            $this->recalculateRepositoryStats($target);
        });

        $this->newLine();
        $this->info("✓ Anonymized '{$owner}' → private/private. {$playCount} plays retained, hidden from public stats.");
        $this->line('Note: user balances/streaks are unchanged (plays keep their original owners).');

        return self::SUCCESS;
    }

    /**
     * Recompute the private repo's running balance + counters across its (now merged) plays.
     * User stats are intentionally untouched — anonymizing only changes which repo a play
     * belongs to, not who made it or what it paid.
     */
    private function recalculateRepositoryStats(Repository $repository): void
    {
        $plays = Play::where('repository_id', $repository->id)->orderBy('played_at')->get();

        $balance = Ruleset::startingBalance();
        $wager = Ruleset::cost();

        foreach ($plays as $play) {
            $balance = $balance - $wager + $play->payout;
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
}
