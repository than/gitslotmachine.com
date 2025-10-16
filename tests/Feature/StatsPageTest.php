<?php

use App\Models\Play;
use App\Models\Repository;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('loads the stats page successfully', function () {
    get('/stats')
        ->assertOk()
        ->assertSee('THE NUMBERS');
});

it('displays theory vs reality section', function () {
    // Create test data
    $user = User::factory()->create();
    $repo = Repository::factory()->create();

    Play::create([
        'user_id' => $user->id,
        'repository_id' => $repo->id,
        'commit_hash' => 'aaa1234',
        'pattern_type' => 'THREE_OF_KIND',
        'pattern_name' => 'THREE OF A KIND',
        'payout' => 50,
        'repo_balance_after' => 100,
        'played_at' => now(),
    ]);

    get('/stats')
        ->assertOk()
        ->assertSee('THEORY VS REALITY')
        ->assertSee('EXPECTED')
        ->assertSee('ACTUAL');
});

it('displays legendary wins when they exist', function () {
    $user = User::factory()->create(['github_username' => 'legendary_user']);
    $repo = Repository::factory()->create();

    // Create a legendary win (Five of a Kind)
    Play::create([
        'user_id' => $user->id,
        'repository_id' => $repo->id,
        'commit_hash' => 'aaaaa12',
        'pattern_type' => 'FIVE_OF_KIND',
        'pattern_name' => 'FIVE OF A KIND',
        'payout' => 1000,
        'repo_balance_after' => 1500,
        'played_at' => now(),
    ]);

    get('/stats')
        ->assertOk()
        ->assertSee('LEGENDARY WINS')
        ->assertSee('FIVE OF A KIND')
        ->assertSee('legendary_user');
});

it('does not show legendary wins section when none exist', function () {
    // Create only common patterns
    $user = User::factory()->create();
    $repo = Repository::factory()->create();

    Play::create([
        'user_id' => $user->id,
        'repository_id' => $repo->id,
        'commit_hash' => 'aa1bb2c',
        'pattern_type' => 'TWO_PAIR',
        'pattern_name' => 'TWO PAIR',
        'payout' => 50,
        'repo_balance_after' => 90,
        'played_at' => now(),
    ]);

    $response = get('/stats')->assertOk();

    // Section should not appear
    expect($response->getContent())->not->toContain('LEGENDARY WINS');
});

it('displays luckiest repositories section', function () {
    $user = User::factory()->create();

    // Create a lucky repo with high net profit
    $luckyRepo = Repository::factory()->create([
        'owner' => 'lucky-owner',
        'name' => 'lucky-repo',
        'balance' => 500,
    ]);

    // 10 plays with high payouts
    for ($i = 0; $i < 10; $i++) {
        Play::create([
            'user_id' => $user->id,
            'repository_id' => $luckyRepo->id,
            'commit_hash' => 'abc123'.$i,
            'pattern_type' => 'FOUR_OF_KIND',
            'pattern_name' => 'FOUR OF A KIND',
            'payout' => 100,
            'repo_balance_after' => 100 + ($i * 90),
            'played_at' => now()->subDays($i),
        ]);
    }

    // Create an unlucky repo with low payouts
    $unluckyRepo = Repository::factory()->create([
        'owner' => 'unlucky-owner',
        'name' => 'unlucky-repo',
        'balance' => 50,
    ]);

    for ($i = 0; $i < 10; $i++) {
        Play::create([
            'user_id' => $user->id,
            'repository_id' => $unluckyRepo->id,
            'commit_hash' => 'def456'.$i,
            'pattern_type' => 'NO_WIN',
            'pattern_name' => 'NO WIN',
            'payout' => 0,
            'repo_balance_after' => 100 - (($i + 1) * 10),
            'played_at' => now()->subDays($i),
        ]);
    }

    get('/stats')
        ->assertOk()
        ->assertSee('LUCKIEST REPOS')
        ->assertSee('lucky-owner/lucky-repo')
        ->assertSee('+900'); // 10 plays * (100 payout - 10 wager)
});
