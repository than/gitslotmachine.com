<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BalanceController;
use App\Http\Controllers\Api\PlayController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/play', [PlayController::class, 'store']);

// Auth routes
Route::post('/auth/token', [AuthController::class, 'createToken']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/user', [AuthController::class, 'user']);
    Route::delete('/auth/token', [AuthController::class, 'revokeToken']);
    Route::get('/balance', [BalanceController::class, 'index']);
});
