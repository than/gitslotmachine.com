<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function createToken(Request $request): JsonResponse
    {
        $request->validate([
            'github_username' => 'required|string',
        ]);

        // Find or create user
        $user = User::firstOrCreate(
            ['github_username' => $request->github_username],
            [
                'name' => $request->github_username,
                'email' => $request->github_username.'@github.com',
                'password' => Hash::make(str()->random()),
                'total_balance' => 0,
                'total_commits' => 0,
                'biggest_win' => 0,
            ]
        );

        // Create token
        $token = $user->createToken('cli-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'token' => $token,
            ],
        ]);
    }

    public function user(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $request->user()->id,
                'github_username' => $request->user()->github_username,
                'name' => $request->user()->name,
            ],
        ]);
    }

    public function revokeToken(Request $request): JsonResponse
    {
        // Revoke current token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Token revoked successfully',
        ]);
    }
}
