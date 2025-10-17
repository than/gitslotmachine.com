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

Route::get('/changelog', function () {
    return view('changelog');
})->name('changelog');

Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

Route::get('/sitemap.xml', function () {
    $baseUrl = 'https://gitslotmachine.com';
    $currentDate = now()->toIso8601String();

    $urls = [
        ['loc' => $baseUrl.'/', 'priority' => '1.0', 'changefreq' => 'daily'],
        ['loc' => $baseUrl.'/about', 'priority' => '0.8', 'changefreq' => 'weekly'],
        ['loc' => $baseUrl.'/stats', 'priority' => '0.8', 'changefreq' => 'daily'],
        ['loc' => $baseUrl.'/odds', 'priority' => '0.6', 'changefreq' => 'weekly'],
        ['loc' => $baseUrl.'/changelog', 'priority' => '0.5', 'changefreq' => 'weekly'],
        ['loc' => $baseUrl.'/privacy', 'priority' => '0.3', 'changefreq' => 'monthly'],
    ];

    $xml = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;

    foreach ($urls as $url) {
        $xml .= '  <url>'.PHP_EOL;
        $xml .= '    <loc>'.htmlspecialchars($url['loc']).'</loc>'.PHP_EOL;
        $xml .= '    <lastmod>'.$currentDate.'</lastmod>'.PHP_EOL;
        $xml .= '    <changefreq>'.$url['changefreq'].'</changefreq>'.PHP_EOL;
        $xml .= '    <priority>'.$url['priority'].'</priority>'.PHP_EOL;
        $xml .= '  </url>'.PHP_EOL;
    }

    $xml .= '</urlset>';

    return response($xml, 200)->header('Content-Type', 'application/xml');
})->name('sitemap');

Route::get('/winner/{uuid}', [App\Http\Controllers\WinnerController::class, 'show'])->name('winner.show');
Route::get('/winner/{uuid}/image.png', [App\Http\Controllers\WinnerController::class, 'image'])->name('winner.image');

// Fallback routes for when custom domain is not configured
// These work on the main domain (e.g., gitslotmachinecom-main-vilmm1.laravel.cloud/api/play)
// Once custom domain is set up, api.gitslotmachine.com will be used instead
Route::middleware(['api'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])->prefix('api')->group(base_path('routes/api.php'));

// Badge fallback route
Route::get('/badge/{owner}/{repo}.svg', [BadgeController::class, 'show']);
