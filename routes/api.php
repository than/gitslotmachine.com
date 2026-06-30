<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BalanceController;
use App\Http\Controllers\Api\PlayController;
use App\Services\Ruleset;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/play', [PlayController::class, 'store']);

// Ruleset version handshake — lets the CLI detect when its bundled payout table has
// drifted from the server's, without fetching the whole table on every commit.
Route::get('/ruleset-version', function () {
    return response()->json([
        'success' => true,
        'data' => [
            'version' => Ruleset::version(),
            'hash' => Ruleset::hash(),
        ],
    ]);
});

// Auth routes
Route::post('/auth/token', [AuthController::class, 'createToken']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/user', [AuthController::class, 'user']);
    Route::delete('/auth/token', [AuthController::class, 'revokeToken']);
    Route::get('/balance', [BalanceController::class, 'index']);
});
