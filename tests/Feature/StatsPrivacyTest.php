<?php

use App\Livewire\Stats\GlobalStats;
use App\Models\Play;
use App\Models\Repository;
use Illuminate\Support\Facades\Cache;
use Livewire\Livewire;

function statsPlay(Repository $repo, string $type, string $name, int $payout): void
{
    Play::create([
        'user_id' => $repo->user_id,
        'repository_id' => $repo->id,
        'commit_hash' => substr(md5($repo->id.$type.$payout.uniqid('', true)), 0, 7),
        'pattern_type' => $type,
        'pattern_name' => $name,
        'payout' => $payout,
        'repo_balance_after' => 100 - 10 + $payout,
        'played_at' => now(),
    ]);
}

// A repo with a legendary win + enough net-positive plays to qualify for "luckiest".
function leaderboardRepo(string $owner, string $name): Repository
{
    $repo = Repository::factory()->create(['owner' => $owner, 'name' => $name]);
    statsPlay($repo, 'ALL_SAME', 'JACKPOT', 250000);
    for ($i = 0; $i < 5; $i++) {
        statsPlay($repo, 'THREE_OF_KIND', 'THREE OF A KIND', 25);
    }

    return $repo;
}

it('excludes private repos from legendary wins and luckiest repos', function () {
    Cache::flush();

    leaderboardRepo('acme', 'web');         // public
    leaderboardRepo('private', 'private');  // anonymized — must be hidden

    $page = Livewire::test(GlobalStats::class);

    $legendaryOwners = collect($page->viewData('legendary_wins'))->pluck('repo_owner');
    $luckiestOwners = collect($page->viewData('luckiest_repos'))->pluck('owner');

    expect($legendaryOwners)->toContain('acme')->not->toContain('private');
    expect($luckiestOwners)->toContain('acme')->not->toContain('private');
});

it('excludes private repos from unluckiest repos', function () {
    Cache::flush();

    $public = Repository::factory()->create(['owner' => 'badluck', 'name' => 'repo']);
    $private = Repository::factory()->create(['owner' => 'private', 'name' => 'private']);
    foreach ([$public, $private] as $repo) {
        for ($i = 0; $i < 6; $i++) {
            statsPlay($repo, 'NO_WIN', 'NO WIN', 0); // all losses → net negative
        }
    }

    $unluckiestOwners = collect(Livewire::test(GlobalStats::class)->viewData('unluckiest_repos'))->pluck('owner');

    expect($unluckiestOwners)->toContain('badluck')->not->toContain('private');
});
