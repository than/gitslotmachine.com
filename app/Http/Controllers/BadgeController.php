<?php

namespace App\Http\Controllers;

use App\Models\Repository;
use App\Services\PatternDetector;
use Illuminate\Http\Response;

class BadgeController extends Controller
{
    public function __construct(
        private PatternDetector $detector
    ) {}
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
        $netPayout = $payout - 10;
        $payoutText = $netPayout > 0 ? "+{$netPayout}" : "{$netPayout}";
        $rightColor = $netPayout > 0 ? '#4c1' : '#e05d44'; // Green for win, red for loss

        // Build the right side text: "PATTERN +40 • hash"
        $rightText = "{$pattern} {$payoutText} • {$hash}";

        // Calculate widths (approximate)
        $leftWidth = 95; // "GitSlots" section
        $rightWidth = strlen($rightText) * 6.5 + 20; // Approximate character width
        $totalWidth = $leftWidth + $rightWidth;

        // Pre-calculate SVG text positions (can't do complex math in heredoc)
        $rightTextX = $leftWidth * 10 + ($rightWidth * 10 / 2);
        $rightTextLength = ($rightWidth - 10) * 10;

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="{$totalWidth}" height="20" role="img" aria-label="GitSlots: {$rightText}">
    <title>GitSlots: {$rightText}</title>
    <linearGradient id="s" x2="0" y2="100%">
        <stop offset="0" stop-color="#bbb" stop-opacity=".1"/>
        <stop offset="1" stop-opacity=".1"/>
    </linearGradient>
    <clipPath id="r">
        <rect width="{$totalWidth}" height="20" rx="3" fill="#fff"/>
    </clipPath>
    <g clip-path="url(#r)">
        <rect width="{$leftWidth}" height="20" fill="#555"/>
        <rect x="{$leftWidth}" width="{$rightWidth}" height="20" fill="{$rightColor}"/>
        <rect width="{$totalWidth}" height="20" fill="url(#s)"/>
    </g>
    <g fill="#fff" text-anchor="middle" font-family="Verdana,Geneva,DejaVu Sans,sans-serif" text-rendering="geometricPrecision" font-size="110">
        <text aria-hidden="true" x="475" y="150" fill="#010101" fill-opacity=".3" transform="scale(.1)" textLength="850">GitSlots</text>
        <text x="475" y="140" transform="scale(.1)" fill="#fff" textLength="850">GitSlots</text>
        <text aria-hidden="true" x="{$rightTextX}" y="150" fill="#010101" fill-opacity=".3" transform="scale(.1)" textLength="{$rightTextLength}">{$rightText}</text>
        <text x="{$rightTextX}" y="140" transform="scale(.1)" fill="#fff" textLength="{$rightTextLength}">{$rightText}</text>
    </g>
</svg>
SVG;

        return response($svg, 200)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Cache-Control', 'max-age=300'); // 5 min cache
    }

    private function generateDefaultBadge(): Response
    {
        $leftWidth = 95;
        $rightText = 'no plays yet';
        $rightWidth = strlen($rightText) * 6.5 + 20;
        $totalWidth = $leftWidth + $rightWidth;

        // Pre-calculate SVG text positions (can't do complex math in heredoc)
        $rightTextX = $leftWidth * 10 + ($rightWidth * 10 / 2);
        $rightTextLength = ($rightWidth - 10) * 10;

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="{$totalWidth}" height="20" role="img" aria-label="GitSlots: {$rightText}">
    <title>GitSlots: {$rightText}</title>
    <linearGradient id="s" x2="0" y2="100%">
        <stop offset="0" stop-color="#bbb" stop-opacity=".1"/>
        <stop offset="1" stop-opacity=".1"/>
    </linearGradient>
    <clipPath id="r">
        <rect width="{$totalWidth}" height="20" rx="3" fill="#fff"/>
    </clipPath>
    <g clip-path="url(#r)">
        <rect width="{$leftWidth}" height="20" fill="#555"/>
        <rect x="{$leftWidth}" width="{$rightWidth}" height="20" fill="#9f9f9f"/>
        <rect width="{$totalWidth}" height="20" fill="url(#s)"/>
    </g>
    <g fill="#fff" text-anchor="middle" font-family="Verdana,Geneva,DejaVu Sans,sans-serif" text-rendering="geometricPrecision" font-size="110">
        <text aria-hidden="true" x="475" y="150" fill="#010101" fill-opacity=".3" transform="scale(.1)" textLength="850">GitSlots</text>
        <text x="475" y="140" transform="scale(.1)" fill="#fff" textLength="850">GitSlots</text>
        <text aria-hidden="true" x="{$rightTextX}" y="150" fill="#010101" fill-opacity=".3" transform="scale(.1)" textLength="{$rightTextLength}">{$rightText}</text>
        <text x="{$rightTextX}" y="140" transform="scale(.1)" fill="#fff" textLength="{$rightTextLength}">{$rightText}</text>
    </g>
</svg>
SVG;

        return response($svg, 200)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Cache-Control', 'max-age=300');
    }
}
