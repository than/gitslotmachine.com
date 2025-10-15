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

        // Extract owner/repo from URL
        preg_match('/github\.com\/([^\/]+)\/([^\/]+)/', $validated['repo_url'], $matches);
        $owner = $matches[1];
        $repoName = rtrim($matches[2], '.git'); // Remove .git if present

        // Verify pattern matches hash
        $detectedPattern = $this->detector->detect($validated['commit_hash']);
        if ($detectedPattern['type'] !== $validated['pattern_type']) {
            return response()->json([
                'message' => 'Pattern mismatch - verification failed',
                'detected' => $detectedPattern['type'],
                'claimed' => $validated['pattern_type'],
            ], 422);
        }

        // Find or create user
        $user = User::firstOrCreate(
            ['github_username' => $owner],
            [
                'name' => $owner,
                'email' => $owner.'@github.com',
                'password' => bcrypt(str()->random()),
                'total_balance' => 0,
                'total_commits' => 0,
                'biggest_win' => 0,
            ]
        );

        // Find or create repository
        $repository = Repository::firstOrCreate(
            ['owner' => $owner, 'name' => $repoName],
            [
                'user_id' => $user->id,
                'github_url' => $validated['repo_url'],
                'balance' => 100,
                'total_commits' => 0,
            ]
        );

        // Calculate new balance (cost 10 credits, add payout)
        $newBalance = $repository->balance - 10 + $validated['payout'];

        // Create play
        Play::create([
            'user_id' => $user->id,
            'repository_id' => $repository->id,
            'commit_hash' => strtolower($validated['commit_hash']),
            'pattern_type' => $validated['pattern_type'],
            'pattern_name' => $validated['pattern_name'],
            'payout' => $validated['payout'],
            'repo_balance_after' => $newBalance,
            'played_at' => now(),
        ]);

        // Update repository stats
        $repository->update([
            'balance' => $newBalance,
            'total_commits' => $repository->total_commits + 1,
            'last_commit_hash' => $validated['commit_hash'],
            'last_played_at' => now(),
        ]);

        // Update user stats
        $netWinnings = $validated['payout'] - 10;
        $user->update([
            'total_balance' => $user->total_balance + $netWinnings,
            'total_commits' => $user->total_commits + 1,
            'biggest_win' => max($user->biggest_win, $validated['payout']),
        ]);

        return response()->json([
            'message' => 'Play recorded successfully',
            'balance' => $newBalance,
        ], 201);
    }
}
