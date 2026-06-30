<?php

namespace App\Services;

/**
 * Single accessor for the canonical ruleset (resources/data/patterns.json) — the source of
 * truth shared with the CLI. Decoded once and cached in-process.
 *
 * Uses a framework-agnostic path (not resource_path()) so it works in plain unit tests that
 * boot no application, which is how PatternDetector is tested.
 */
class Ruleset
{
    /** @var array<string, mixed>|null */
    private static ?array $data = null;

    /**
     * @return array<string, mixed>
     */
    public static function all(): array
    {
        if (self::$data === null) {
            $path = dirname(__DIR__, 2).'/resources/data/patterns.json';
            $decoded = is_file($path) ? json_decode((string) file_get_contents($path), true) : null;

            if (! is_array($decoded) || ! isset($decoded['patterns'])) {
                throw new \RuntimeException("Canonical ruleset missing or invalid: {$path}");
            }

            self::$data = $decoded;
        }

        return self::$data;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function patterns(): array
    {
        return self::all()['patterns'];
    }

    public static function version(): int
    {
        return self::all()['rulesetVersion'];
    }

    public static function hash(): string
    {
        return self::all()['rulesetHash'];
    }

    public static function rtp(): float
    {
        return self::all()['rtp'];
    }

    public static function winRate(): float
    {
        return self::all()['winRate'];
    }

    public static function cost(): int
    {
        return self::all()['cost'];
    }

    public static function startingBalance(): int
    {
        return self::all()['startingBalance'];
    }
}
