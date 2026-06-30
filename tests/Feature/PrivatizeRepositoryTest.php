<?php

use App\Models\Play;
use App\Models\Repository;
use App\Models\User;

function makePlay(Repository $repo, int $payout = 25, string $hash = 'aaa1234'): Play
{
    return Play::create([
        'user_id' => $repo->user_id,
        'repository_id' => $repo->id,
        'commit_hash' => $hash,
        'pattern_type' => 'THREE_OF_KIND',
        'pattern_name' => 'THREE OF A KIND',
        'payout' => $payout,
        'repo_balance_after' => 100 - 10 + $payout,
        'played_at' => now(),
    ]);
}

it('anonymizes an owner to private/private and folds in its plays', function () {
    $repo = Repository::factory()->create(['owner' => 'example-org', 'name' => 'website']);
    makePlay($repo, 25, 'aaa1234');

    $this->artisan('repo:privatize', ['owner' => 'example-org'])->assertSuccessful();

    expect(Repository::where('owner', 'example-org')->exists())->toBeFalse();

    $private = Repository::where('owner', 'private')->where('name', 'private')->first();
    expect($private)->not->toBeNull()
        ->and($private->plays()->count())->toBe(1)
        ->and(Play::where('commit_hash', 'aaa1234')->first()->repository_id)->toBe($private->id);
});

it('folds into an existing private repo without violating the unique constraint', function () {
    $private = Repository::factory()->create(['owner' => 'private', 'name' => 'private']);
    makePlay($private, 10, 'bbb1111');

    $ted = Repository::factory()->create(['owner' => 'example-org', 'name' => 'talks']);
    makePlay($ted, 25, 'ccc2222');

    $this->artisan('repo:privatize', ['owner' => 'example-org'])->assertSuccessful();

    expect(Repository::where('owner', 'example-org')->exists())->toBeFalse()
        ->and(Repository::where('owner', 'private')->where('name', 'private')->count())->toBe(1)
        ->and($private->fresh()->plays()->count())->toBe(2);
});

it('leaves the user balance untouched (anonymization is not removal)', function () {
    $user = User::factory()->create(['total_balance' => 999, 'total_commits' => 3]);
    $repo = Repository::factory()->create(['owner' => 'example-org', 'name' => 'x', 'user_id' => $user->id]);
    makePlay($repo);

    $this->artisan('repo:privatize', ['owner' => 'example-org'])->assertSuccessful();

    expect($user->fresh()->total_balance)->toBe(999)
        ->and($user->fresh()->total_commits)->toBe(3);
});

it('dry-run writes nothing', function () {
    $repo = Repository::factory()->create(['owner' => 'example-org', 'name' => 'y']);
    makePlay($repo);

    $this->artisan('repo:privatize', ['owner' => 'example-org', '--dry-run' => true])->assertSuccessful();

    expect(Repository::where('owner', 'example-org')->exists())->toBeTrue()
        ->and(Repository::where('owner', 'private')->where('name', 'private')->exists())->toBeFalse();
});

it('does nothing for an unknown owner', function () {
    $this->artisan('repo:privatize', ['owner' => 'nope'])->assertSuccessful();
    expect(Repository::where('owner', 'private')->exists())->toBeFalse();
});
