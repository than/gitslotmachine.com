<?php

namespace App\Http\Controllers;

use App\Models\Repository;
use Illuminate\Http\Response;

class BadgeController extends Controller
{
    public function show(string $owner, string $repo): Response
    {
        // Remove .svg extension if present
        $repo = str_replace('.svg', '', $repo);

        // Find repository
        $repository = Repository::where('owner', $owner)
            ->where('name', $repo)
            ->with(['plays' => fn ($q) => $q->latest('played_at')->limit(1)])
            ->first();

        if (! $repository || $repository->plays->isEmpty()) {
            return $this->generateDefaultBadge();
        }

        $lastPlay = $repository->plays->first();

        return $this->generateBadge(
            $lastPlay->commit_hash,
            $lastPlay->pattern_name,
            $lastPlay->payout,
            $repository->balance
        );
    }

    private function generateBadge(
        string $hash,
        string $pattern,
        int $payout,
        int $balance
    ): Response {
        $payoutText = $payout > 0 ? "+{$payout}" : "{$payout}";
        $payoutColor = $payout > 0 ? '#10b981' : '#ef4444';

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="400" height="30" role="img">
    <title>Git Slot Machine</title>
    <linearGradient id="s" x2="0" y2="100%">
        <stop offset="0" stop-color="#1f2937" stop-opacity=".1"/>
        <stop offset="1" stop-opacity=".1"/>
    </linearGradient>
    <clipPath id="r">
        <rect width="400" height="30" rx="3" fill="#fff"/>
    </clipPath>
    <g clip-path="url(#r)">
        <rect width="400" height="30" fill="#1f2937"/>
        <rect x="0" width="400" height="30" fill="url(#s)"/>
        <g fill="#fff" text-anchor="start" font-family="Verdana,Geneva,DejaVu Sans,sans-serif" font-size="11">
            <!-- Emoji -->
            <text x="5" y="20" font-size="16">🎰</text>

            <!-- Hash -->
            <text x="30" y="20" fill="#9ca3af" font-family="monospace">{$hash}</text>

            <!-- Bullet -->
            <text x="100" y="20" fill="#6b7280">•</text>

            <!-- Pattern and payout -->
            <text x="115" y="20" fill="{$payoutColor}" font-weight="bold">{$pattern} {$payoutText}</text>

            <!-- Bullet -->
            <text x="270" y="20" fill="#6b7280">•</text>

            <!-- Balance -->
            <text x="285" y="20" fill="#fff">Balance: </text>
            <text x="340" y="20" fill="#10b981" font-weight="bold">{$balance}</text>
        </g>
    </g>
</svg>
SVG;

        return response($svg, 200)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Cache-Control', 'max-age=300'); // 5 min cache
    }

    private function generateDefaultBadge(): Response
    {
        $svg = <<<'SVG'
<svg xmlns="http://www.w3.org/2000/svg" width="350" height="30" role="img">
    <title>Git Slot Machine</title>
    <linearGradient id="s" x2="0" y2="100%">
        <stop offset="0" stop-color="#1f2937" stop-opacity=".1"/>
        <stop offset="1" stop-opacity=".1"/>
    </linearGradient>
    <clipPath id="r">
        <rect width="350" height="30" rx="3" fill="#fff"/>
    </clipPath>
    <g clip-path="url(#r)">
        <rect width="350" height="30" fill="#1f2937"/>
        <rect x="0" width="350" height="30" fill="url(#s)"/>
        <g fill="#fff" text-anchor="start" font-family="Verdana,Geneva,DejaVu Sans,sans-serif" font-size="11">
            <text x="5" y="20" font-size="16">🎰</text>
            <text x="30" y="20" fill="#9ca3af">No plays yet • Install git-slot-machine</text>
        </g>
    </g>
</svg>
SVG;

        return response($svg, 200)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Cache-Control', 'max-age=300');
    }
}
