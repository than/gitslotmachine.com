<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // Get total stats across all repos
        $totalBalance = $user->repositories()->sum('balance');
        $totalCommits = $user->total_commits;
        $totalWinnings = $user->total_balance;
        $biggestWin = $user->biggest_win;

        return response()->json([
            'success' => true,
            'data' => [
                'balance' => $totalBalance,
                'total_commits' => $totalCommits,
                'total_winnings' => $totalWinnings,
                'biggest_win' => $biggestWin,
                'biggest_win_pattern' => $user->biggest_win_pattern,
                'biggest_win_hash' => $user->biggest_win_hash,
            ],
        ]);
    }
}
