<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('flags users with profanity in username as pending', function () {
    $user = User::factory()->create([
        'github_username' => 'damn_user123',
        'moderation_status' => 'pending',
    ]);

    expect($user->moderation_status)->toBe('pending')
        ->and($user->isPending())->toBeTrue()
        ->and($user->isApproved())->toBeFalse();
});

it('allows clean usernames as approved', function () {
    $user = User::factory()->create([
        'github_username' => 'clean_user123',
        'moderation_status' => 'approved',
    ]);

    expect($user->moderation_status)->toBe('approved')
        ->and($user->isApproved())->toBeTrue()
        ->and($user->isPending())->toBeFalse();
});

it('displays asterisks for non-approved users', function () {
    $user = User::factory()->create([
        'github_username' => 'test_user',
        'moderation_status' => 'pending',
    ]);

    expect($user->display_name)->toBe('*******');
});

it('displays github username for approved users', function () {
    $user = User::factory()->create([
        'github_username' => 'approved_user',
        'moderation_status' => 'approved',
    ]);

    expect($user->display_name)->toBe('approved_user');
});

it('can approve a pending user', function () {
    $user = User::factory()->create([
        'github_username' => 'test_user',
        'moderation_status' => 'pending',
    ]);

    expect($user->isPending())->toBeTrue();

    $user->approve();

    expect($user->fresh()->isApproved())->toBeTrue()
        ->and($user->fresh()->moderated_at)->not->toBeNull();
});

it('can reject a pending user', function () {
    $user = User::factory()->create([
        'github_username' => 'test_user',
        'moderation_status' => 'pending',
    ]);

    expect($user->isPending())->toBeTrue();

    $user->reject();

    expect($user->fresh()->isRejected())->toBeTrue()
        ->and($user->fresh()->moderated_at)->not->toBeNull();
});

it('automatically flags new users with profanity in username', function () {
    $user = User::factory()->create([
        'github_username' => 'damn-user',
    ]);

    expect($user->moderation_status)->toBe('pending');
});

it('automatically approves new users with clean usernames', function () {
    $user = User::factory()->create([
        'github_username' => 'clean_user',
    ]);

    expect($user->moderation_status)->toBe('approved');
});
