<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlayRequest;
use App\Models\Play;
use App\Models\Repository;
use App\Models\User;
use App\Services\PatternDetector;
use Illuminate\Http\JsonResponse;

class PlayController extends Controller
{
    public function __construct(
        private PatternDetector $detector
    ) {}

    public function store(StorePlayRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Server-side pattern detection (don't trust client)
        $detectedPattern = $this->detector->detect($validated['commit_hash']);
        $payout = $detectedPattern['payout'];
        $wager = 10; // Standard wager

        // Find or create user
        $user = User::firstOrCreate(
            ['github_username' => $validated['github_username']],
            [
                'name' => $validated['github_username'],
                'email' => $validated['github_username'].'@github.com',
                'password' => bcrypt(str()->random()),
                'total_balance' => 0,
                'total_commits' => 0,
                'biggest_win' => 0,
            ]
        );

        // Find or create repository
        $repository = Repository::firstOrCreate(
            ['owner' => $validated['repo_owner'], 'name' => $validated['repo_name']],
            [
                'user_id' => $user->id,
                'github_url' => $validated['repo_url'],
                'balance' => 100,
                'total_commits' => 0,
            ]
        );

        // Calculate balances
        $balanceBefore = $repository->balance;
        $balanceAfter = $balanceBefore - $wager + $payout;

        // Create play
        Play::create([
            'user_id' => $user->id,
            'repository_id' => $repository->id,
            'commit_hash' => strtolower($validated['commit_hash']),
            'pattern_type' => $detectedPattern['type'],
            'pattern_name' => $detectedPattern['name'],
            'payout' => $payout,
            'repo_balance_after' => $balanceAfter,
            'played_at' => now(),
        ]);

        // Update repository stats
        $repository->update([
            'balance' => $balanceAfter,
            'total_commits' => $repository->total_commits + 1,
            'last_commit_hash' => $validated['commit_hash'],
            'last_played_at' => now(),
        ]);

        // Update user stats
        $netWinnings = $payout - $wager;
        $user->update([
            'total_balance' => $user->total_balance + $netWinnings,
            'total_commits' => $user->total_commits + 1,
            'biggest_win' => max($user->biggest_win, $payout),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Play recorded successfully',
            'data' => [
                'balance' => $balanceAfter,
                'payout' => $payout,
                'pattern_name' => $detectedPattern['name'],
            ],
        ], 201);
    }
}
