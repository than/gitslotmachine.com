<?php

use App\Models\Play;
use App\Models\Repository;
use App\Models\User;

use function Pest\Laravel\get;

uses()->group('badge');
uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('generates badge for existing repo', function () {
    $user = User::factory()->create(['github_username' => 'thantibbetts']);
    $repo = Repository::factory()->create([
        'user_id' => $user->id,
        'owner' => 'thantibbetts',
        'name' => 'test-repo',
        'balance' => 150,
    ]);

    Play::create([
        'user_id' => $user->id,
        'repository_id' => $repo->id,
        'commit_hash' => 'aabbcc1',
        'pattern_type' => 'THREE_PAIR',
        'pattern_name' => 'THREE PAIR',
        'payout' => 150,
        'repo_balance_after' => 150,
        'played_at' => now(),
    ]);

    $response = get('http://badge.gitslotmachine.test/thantibbetts/test-repo.svg');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'image/svg+xml');

    $content = $response->getContent();
    expect($content)->toContain('aabbcc1');
    expect($content)->toContain('THREE PAIR');
    expect($content)->toContain('150');
});

it('returns default badge for nonexistent repo', function () {
    $response = get('http://badge.gitslotmachine.test/nonexistent/repo.svg');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'image/svg+xml');

    $content = $response->getContent();
    expect($content)->toContain('No plays yet');
});
