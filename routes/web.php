<?php

use App\Http\Controllers\BadgeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/stats', function () {
    return view('stats');
})->name('stats');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/odds', function () {
    return view('odds');
})->name('odds');

Route::get('/winner/{uuid}', [App\Http\Controllers\WinnerController::class, 'show'])->name('winner.show');
Route::get('/winner/{uuid}/image.png', [App\Http\Controllers\WinnerController::class, 'image'])->name('winner.image');

// Fallback routes for when custom domain is not configured
// These work on the main domain (e.g., gitslotmachinecom-main-vilmm1.laravel.cloud/api/play)
// Once custom domain is set up, api.gitslotmachine.com will be used instead
Route::middleware(['api'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])->prefix('api')->group(base_path('routes/api.php'));

// Badge fallback route
Route::get('/badge/{owner}/{repo}.svg', [BadgeController::class, 'show']);
