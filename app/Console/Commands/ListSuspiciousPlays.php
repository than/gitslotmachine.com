<?php

namespace App\Console\Commands;

use App\Models\Play;
use Illuminate\Console\Command;

class ListSuspiciousPlays extends Command
{
    protected $signature = 'plays:suspicious
                            {--limit=50 : Number of plays to show}
                            {--user= : Filter by specific username}';

    protected $description = 'List all plays flagged for suspicious hash grinding activity';

    public function handle(): int
    {
        $this->info('═══════════════════════════════════════');
        $this->info('   SUSPICIOUS PLAYS - HASH GRINDING   ');
        $this->info('═══════════════════════════════════════');
        $this->newLine();

        // Get statistics
        $totalSuspicious = Play::where('suspicious', true)->count();
        $totalPlays = Play::count();
        $percentage = $totalPlays > 0 ? round(($totalSuspicious / $totalPlays) * 100, 2) : 0;
        $avgAmends = Play::where('suspicious', true)->avg('amend_count') ?? 0;
        $maxAmends = Play::where('suspicious', true)->max('amend_count') ?? 0;

        // Display stats
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Suspicious Plays', number_format($totalSuspicious)],
                ['Total Plays', number_format($totalPlays)],
                ['Percentage Suspicious', $percentage . '%'],
                ['Average Amends', round($avgAmends, 1)],
                ['Max Amends (Record)', $maxAmends],
            ]
        );

        $this->newLine();

        if ($totalSuspicious === 0) {
            $this->info('No suspicious plays detected yet.');
            return 0;
        }

        // Top offenders
        $this->info('TOP OFFENDERS:');
        $topOffenders = Play::select('user_id')
            ->selectRaw('COUNT(*) as suspicious_count')
            ->selectRaw('SUM(amend_count) as total_amends')
            ->selectRaw('SUM(payout) as total_payout')
            ->where('suspicious', true)
            ->groupBy('user_id')
            ->orderByDesc('suspicious_count')
            ->with('user')
            ->limit(10)
            ->get();

        $this->table(
            ['Rank', 'Username', 'Suspicious Plays', 'Total Amends', 'Total Payout'],
            $topOffenders->map(function ($offender, $index) {
                return [
                    $index + 1,
                    $offender->user->github_username,
                    $offender->suspicious_count,
                    $offender->total_amends,
                    '+' . number_format($offender->total_payout),
                ];
            })->toArray()
        );

        $this->newLine();

        // Get suspicious plays
        $query = Play::with(['user', 'repository'])
            ->where('suspicious', true)
            ->orderBy('created_at', 'desc');

        // Filter by user if specified
        if ($this->option('user')) {
            $query->whereHas('user', function ($q) {
                $q->where('github_username', $this->option('user'));
            });
        }

        $plays = $query->limit($this->option('limit'))->get();

        $this->info('FLAGGED COMMITS (Last ' . $this->option('limit') . '):');
        $this->table(
            ['Date', 'User', 'Repo', 'Hash', 'Pattern', 'Payout', 'Amends'],
            $plays->map(function ($play) {
                return [
                    $play->created_at->format('Y-m-d H:i'),
                    $play->user->github_username,
                    $play->repository->displayFullName(),
                    $play->commit_hash,
                    $play->pattern_name,
                    '+' . number_format($play->payout),
                    $play->amend_count,
                ];
            })->toArray()
        );

        $this->newLine();
        $this->comment('To remove a suspicious play:');
        $this->line('  php artisan play:remove <hash> <username>');
        $this->newLine();
        $this->comment('To filter by user:');
        $this->line('  php artisan plays:suspicious --user=username');
        $this->newLine();

        return 0;
    }
}
