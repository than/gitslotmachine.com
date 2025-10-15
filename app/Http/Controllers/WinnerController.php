<?php

namespace App\Http\Controllers;

use App\Models\Play;
use Illuminate\Http\Response;
use Spatie\Browsershot\Browsershot;

class WinnerController extends Controller
{
    public function show(string $uuid)
    {
        $play = Play::with(['user', 'repository'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        // Only show wins
        if ($play->payout <= 0) {
            abort(404);
        }

        return view('winner', compact('play'));
    }

    public function image(string $uuid)
    {
        $play = Play::with(['user', 'repository'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        // Only generate images for wins
        if ($play->payout <= 0) {
            abort(404);
        }

        $cacheKey = "winner-image-{$uuid}";
        $cachePath = storage_path("app/public/og-images/{$uuid}.png");

        // Return cached image if it exists
        if (file_exists($cachePath)) {
            return response()->file($cachePath, [
                'Content-Type' => 'image/png',
                'Cache-Control' => 'public, max-age=31536000', // 1 year
            ]);
        }

        // Ensure directory exists
        if (!file_exists(dirname($cachePath))) {
            mkdir(dirname($cachePath), 0755, true);
        }

        // Generate terminal output HTML
        $html = view('winner-terminal', compact('play'))->render();

        // Generate image using browsershot
        try {
            Browsershot::html($html)
                ->windowSize(1200, 630) // OG image size
                ->setScreenshotType('png')
                ->save($cachePath);

            return response()->file($cachePath, [
                'Content-Type' => 'image/png',
                'Cache-Control' => 'public, max-age=31536000',
            ]);
        } catch (\Exception $e) {
            // Fallback if browsershot fails
            abort(500, 'Could not generate image');
        }
    }
}
