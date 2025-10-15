<?php

use App\Models\Play;
use App\Models\Repository;
use App\Models\User;

use function Pest\Laravel\postJson;

uses()->group('api');

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

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

    // Verify play created
    expect(Play::where('commit_hash', 'aabbcc1')
        ->where('pattern_type', 'THREE_PAIR')
        ->where('payout', 150)
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

it('rejects mismatched pattern', function () {
    $response = postJson('/api/play', [
        'repo_url' => 'https://github.com/thantibbetts/test-repo',
        'repo_owner' => 'thantibbetts',
        'repo_name' => 'test-repo',
        'github_username' => 'thantibbetts',
        'commit_hash' => 'aabbcc1', // Actually THREE_PAIR
        'pattern_type' => 'NO_WIN', // Claiming NO_WIN
        'pattern_name' => 'NO WIN',
        'payout' => 0,
    ]);

    $response->assertStatus(422);
    $response->assertJsonFragment(['message' => 'Pattern mismatch - verification failed']);
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

    // Verify balance updated (100 - 10 cost + 300 payout = 390)
    $repo->refresh();
    expect($repo->balance)->toBe(390);
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

it('requires pattern_type field', function () {
    $response = postJson('/api/play', [
        'repo_url' => 'https://github.com/thantibbetts/test-repo',
        'repo_owner' => 'thantibbetts',
        'repo_name' => 'test-repo',
        'github_username' => 'thantibbetts',
        'commit_hash' => 'aabbcc1',
        'pattern_name' => 'THREE PAIR',
        'payout' => 150,
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['pattern_type']);
});

it('requires pattern_name field', function () {
    $response = postJson('/api/play', [
        'repo_url' => 'https://github.com/thantibbetts/test-repo',
        'repo_owner' => 'thantibbetts',
        'repo_name' => 'test-repo',
        'github_username' => 'thantibbetts',
        'commit_hash' => 'aabbcc1',
        'pattern_type' => 'THREE_PAIR',
        'payout' => 150,
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['pattern_name']);
});

it('requires payout field', function () {
    $response = postJson('/api/play', [
        'repo_url' => 'https://github.com/thantibbetts/test-repo',
        'repo_owner' => 'thantibbetts',
        'repo_name' => 'test-repo',
        'github_username' => 'thantibbetts',
        'commit_hash' => 'aabbcc1',
        'pattern_type' => 'THREE_PAIR',
        'pattern_name' => 'THREE PAIR',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['payout']);
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
    expect($user->total_balance)->toBe(290); // 300 payout - 10 cost
    expect($user->biggest_win)->toBe(300);
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
