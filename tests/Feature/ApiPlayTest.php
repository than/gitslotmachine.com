<?php

use App\Models\Play;
use App\Models\Repository;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\postJson;

uses()->group('api');

uses(RefreshDatabase::class);

it('can submit a play', function () {
    $response = postJson('/api/play', [
        'repo_url' => 'https://github.com/thantibbetts/test-repo',
        'repo_owner' => 'thantibbetts',
        'repo_name' => 'test-repo',
        'github_username' => 'thantibbetts',
        'commit_hash' => 'aabbcc1',
        'pattern_type' => 'THREE_PAIR',
        'pattern_name' => 'THREE PAIR',
        'payout' => 150,
    ]);

    $response->assertStatus(201);
    $response->assertJson(['message' => 'Play recorded successfully']);

    // Verify user created
    expect(User::where('github_username', 'thantibbetts')->exists())->toBeTrue();

    // Verify repository created
    expect(Repository::where('owner', 'thantibbetts')
        ->where('name', 'test-repo')
        ->exists())->toBeTrue();

    // Verify play created with the server-computed payout (THREE_PAIR = 500),
    // not the client-supplied value.
    expect(Play::where('commit_hash', 'aabbcc1')
        ->where('pattern_type', 'THREE_PAIR')
        ->where('payout', 500)
        ->exists())->toBeTrue();
});

it('rejects invalid hash length', function () {
    $response = postJson('/api/play', [
        'repo_url' => 'https://github.com/thantibbetts/test-repo',
        'repo_owner' => 'thantibbetts',
        'repo_name' => 'test-repo',
        'github_username' => 'thantibbetts',
        'commit_hash' => 'abc',
        'pattern_type' => 'NO_WIN',
        'pattern_name' => 'NO WIN',
        'payout' => 0,
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['commit_hash']);
});

it('rejects non-hex characters in hash', function () {
    $response = postJson('/api/play', [
        'repo_url' => 'https://github.com/thantibbetts/test-repo',
        'repo_owner' => 'thantibbetts',
        'repo_name' => 'test-repo',
        'github_username' => 'thantibbetts',
        'commit_hash' => 'gggg123',
        'pattern_type' => 'NO_WIN',
        'pattern_name' => 'NO WIN',
        'payout' => 0,
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['commit_hash']);
});

it('ignores a lying client and records the server-computed pattern', function () {
    // The API does not trust client-supplied pattern/payout; it recomputes them
    // from the commit hash. A client claiming NO_WIN for a THREE_PAIR hash is
    // silently overridden, not rejected.
    $response = postJson('/api/play', [
        'repo_url' => 'https://github.com/thantibbetts/test-repo',
        'repo_owner' => 'thantibbetts',
        'repo_name' => 'test-repo',
        'github_username' => 'thantibbetts',
        'commit_hash' => 'aabbcc1', // Actually THREE_PAIR
        'pattern_type' => 'NO_WIN',  // Lie: claiming NO_WIN
        'pattern_name' => 'NO WIN',
        'payout' => 0,
    ]);

    $response->assertStatus(201);

    // The stored play reflects the true server-detected pattern, not the lie.
    expect(Play::where('commit_hash', 'aabbcc1')
        ->where('pattern_type', 'THREE_PAIR')
        ->where('payout', 500)
        ->exists())->toBeTrue();
});

it('updates existing user and repo', function () {
    // Create existing user and repo
    $user = User::factory()->create(['github_username' => 'thantibbetts']);
    $repo = Repository::factory()->create([
        'user_id' => $user->id,
        'owner' => 'thantibbetts',
        'name' => 'test-repo',
        'balance' => 100,
        'total_commits' => 0,
    ]);

    $response = postJson('/api/play', [
        'repo_url' => 'https://github.com/thantibbetts/test-repo',
        'repo_owner' => 'thantibbetts',
        'repo_name' => 'test-repo',
        'github_username' => 'thantibbetts',
        'commit_hash' => 'abacfed',
        'pattern_type' => 'ALL_LETTERS',
        'pattern_name' => 'ALL LETTERS',
        'payout' => 300,
    ]);

    $response->assertStatus(201);

    // Balance uses the server-computed payout (ALL_LETTERS = 250): 100 - 10 + 250 = 340
    $repo->refresh();
    expect($repo->balance)->toBe(340);
    expect($repo->total_commits)->toBe(1);
});

it('calculates correct balance with no win', function () {
    $user = User::factory()->create(['github_username' => 'thantibbetts']);
    $repo = Repository::factory()->create([
        'user_id' => $user->id,
        'owner' => 'thantibbetts',
        'name' => 'test-repo',
        'balance' => 100,
    ]);

    $response = postJson('/api/play', [
        'repo_url' => 'https://github.com/thantibbetts/test-repo',
        'repo_owner' => 'thantibbetts',
        'repo_name' => 'test-repo',
        'github_username' => 'thantibbetts',
        'commit_hash' => 'abcd123',
        'pattern_type' => 'NO_WIN',
        'pattern_name' => 'NO WIN',
        'payout' => 0,
    ]);

    $response->assertStatus(201);

    // Verify balance decreased by cost (100 - 10 = 90)
    $repo->refresh();
    expect($repo->balance)->toBe(90);
});

it('requires repo_url field', function () {
    $response = postJson('/api/play', [
        'repo_owner' => 'thantibbetts',
        'repo_name' => 'test-repo',
        'github_username' => 'thantibbetts',
        'commit_hash' => 'aabbcc1',
        'pattern_type' => 'THREE_PAIR',
        'pattern_name' => 'THREE PAIR',
        'payout' => 150,
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['repo_url']);
});

it('requires commit_hash field', function () {
    $response = postJson('/api/play', [
        'repo_url' => 'https://github.com/thantibbetts/test-repo',
        'repo_owner' => 'thantibbetts',
        'repo_name' => 'test-repo',
        'github_username' => 'thantibbetts',
        'pattern_type' => 'THREE_PAIR',
        'pattern_name' => 'THREE PAIR',
        'payout' => 150,
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['commit_hash']);
});

it('does not require client pattern_type (server computes it)', function () {
    $response = postJson('/api/play', [
        'repo_url' => 'https://github.com/thantibbetts/test-repo',
        'repo_owner' => 'thantibbetts',
        'repo_name' => 'test-repo',
        'github_username' => 'thantibbetts',
        'commit_hash' => 'aabbcc1',
        'pattern_name' => 'THREE PAIR',
        'payout' => 150,
    ]);

    $response->assertStatus(201);
    expect(Play::where('commit_hash', 'aabbcc1')
        ->where('pattern_type', 'THREE_PAIR')
        ->exists())->toBeTrue();
});

it('does not require client pattern_name (server computes it)', function () {
    $response = postJson('/api/play', [
        'repo_url' => 'https://github.com/thantibbetts/test-repo',
        'repo_owner' => 'thantibbetts',
        'repo_name' => 'test-repo',
        'github_username' => 'thantibbetts',
        'commit_hash' => 'aabbcc1',
        'pattern_type' => 'THREE_PAIR',
        'payout' => 150,
    ]);

    $response->assertStatus(201);
    expect(Play::where('commit_hash', 'aabbcc1')
        ->where('pattern_name', 'THREE PAIR')
        ->exists())->toBeTrue();
});

it('does not require client payout (server computes it)', function () {
    $response = postJson('/api/play', [
        'repo_url' => 'https://github.com/thantibbetts/test-repo',
        'repo_owner' => 'thantibbetts',
        'repo_name' => 'test-repo',
        'github_username' => 'thantibbetts',
        'commit_hash' => 'aabbcc1',
        'pattern_type' => 'THREE_PAIR',
        'pattern_name' => 'THREE PAIR',
    ]);

    $response->assertStatus(201);
    expect(Play::where('commit_hash', 'aabbcc1')
        ->where('payout', 500)
        ->exists())->toBeTrue();
});

it('rejects non-github URLs', function () {
    $response = postJson('/api/play', [
        'repo_url' => 'https://gitlab.com/thantibbetts/test-repo',
        'repo_owner' => 'thantibbetts',
        'repo_name' => 'test-repo',
        'github_username' => 'thantibbetts',
        'commit_hash' => 'aabbcc1',
        'pattern_type' => 'THREE_PAIR',
        'pattern_name' => 'THREE PAIR',
        'payout' => 150,
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['repo_url']);
});

it('updates user stats correctly', function () {
    $user = User::factory()->create([
        'github_username' => 'thantibbetts',
        'total_balance' => 0,
        'total_commits' => 0,
        'biggest_win' => 0,
    ]);

    Repository::factory()->create([
        'user_id' => $user->id,
        'owner' => 'thantibbetts',
        'name' => 'test-repo',
        'balance' => 100,
    ]);

    postJson('/api/play', [
        'repo_url' => 'https://github.com/thantibbetts/test-repo',
        'repo_owner' => 'thantibbetts',
        'repo_name' => 'test-repo',
        'github_username' => 'thantibbetts',
        'commit_hash' => 'abacfed',
        'pattern_type' => 'ALL_LETTERS',
        'pattern_name' => 'ALL LETTERS',
        'payout' => 300,
    ]);

    $user->refresh();
    expect($user->total_commits)->toBe(1);
    expect($user->total_balance)->toBe(240); // server-computed ALL_LETTERS (250) - 10 cost
    expect($user->biggest_win)->toBe(250);
});

it('increments current streak on win', function () {
    $user = User::factory()->create([
        'github_username' => 'thantibbetts',
        'current_streak' => 0,
    ]);

    Repository::factory()->create([
        'user_id' => $user->id,
        'owner' => 'thantibbetts',
        'name' => 'test-repo',
        'balance' => 100,
    ]);

    postJson('/api/play', [
        'repo_url' => 'https://github.com/thantibbetts/test-repo',
        'repo_owner' => 'thantibbetts',
        'repo_name' => 'test-repo',
        'github_username' => 'thantibbetts',
        'commit_hash' => 'aabbcc1',
        'pattern_type' => 'THREE_PAIR',
        'pattern_name' => 'THREE PAIR',
        'payout' => 150,
    ]);

    $user->refresh();
    expect($user->current_streak)->toBe(1);
});

it('resets current streak on loss', function () {
    $user = User::factory()->create([
        'github_username' => 'thantibbetts',
        'current_streak' => 3,
    ]);

    Repository::factory()->create([
        'user_id' => $user->id,
        'owner' => 'thantibbetts',
        'name' => 'test-repo',
        'balance' => 100,
    ]);

    postJson('/api/play', [
        'repo_url' => 'https://github.com/thantibbetts/test-repo',
        'repo_owner' => 'thantibbetts',
        'repo_name' => 'test-repo',
        'github_username' => 'thantibbetts',
        'commit_hash' => 'abcd123',
        'pattern_type' => 'NO_WIN',
        'pattern_name' => 'NO WIN',
        'payout' => 0,
    ]);

    $user->refresh();
    expect($user->current_streak)->toBe(0);
});

it('updates longest streak when current streak exceeds it', function () {
    $user = User::factory()->create([
        'github_username' => 'thantibbetts',
        'current_streak' => 4,
        'longest_streak' => 3,
    ]);

    Repository::factory()->create([
        'user_id' => $user->id,
        'owner' => 'thantibbetts',
        'name' => 'test-repo',
        'balance' => 100,
    ]);

    postJson('/api/play', [
        'repo_url' => 'https://github.com/thantibbetts/test-repo',
        'repo_owner' => 'thantibbetts',
        'repo_name' => 'test-repo',
        'github_username' => 'thantibbetts',
        'commit_hash' => 'aabbcc1',
        'pattern_type' => 'THREE_PAIR',
        'pattern_name' => 'THREE PAIR',
        'payout' => 150,
    ]);

    $user->refresh();
    expect($user->current_streak)->toBe(5);
    expect($user->longest_streak)->toBe(5);
});

it('records when longest streak ended', function () {
    $user = User::factory()->create([
        'github_username' => 'thantibbetts',
        'current_streak' => 5,
        'longest_streak' => 5,
        'longest_streak_ended_at' => null,
    ]);

    Repository::factory()->create([
        'user_id' => $user->id,
        'owner' => 'thantibbetts',
        'name' => 'test-repo',
        'balance' => 100,
    ]);

    postJson('/api/play', [
        'repo_url' => 'https://github.com/thantibbetts/test-repo',
        'repo_owner' => 'thantibbetts',
        'repo_name' => 'test-repo',
        'github_username' => 'thantibbetts',
        'commit_hash' => 'abcd123',
        'pattern_type' => 'NO_WIN',
        'pattern_name' => 'NO WIN',
        'payout' => 0,
    ]);

    $user->refresh();
    expect($user->current_streak)->toBe(0);
    expect($user->longest_streak)->toBe(5);
    expect($user->longest_streak_ended_at)->not->toBeNull();
});
