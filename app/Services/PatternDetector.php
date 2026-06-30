<?php

namespace App\Services;

use InvalidArgumentException;

class PatternDetector
{
    private SecretDetector $secretDetector;

    public function __construct()
    {
        $this->secretDetector = new SecretDetector;
    }

    /** @var array<string, array{name: string, payout: int}>|null */
    private static ?array $payouts = null;

    /**
     * Payout table, loaded from the canonical ruleset (resources/data/patterns.json) so the
     * CLI, server, and odds page share a single source of truth. See PATTERN-DETECTION-SPEC.md.
     *
     * @return array<string, array{name: string, payout: int}>
     */
    private static function payouts(): array
    {
        if (self::$payouts === null) {
            $map = [];
            foreach (Ruleset::patterns() as $pattern) {
                $map[$pattern['type']] = ['name' => $pattern['name'], 'payout' => $pattern['payout']];
            }

            self::$payouts = $map;
        }

        return self::$payouts;
    }

    public function detect(string $hash): array
    {
        // Validate input
        if (strlen($hash) !== 7) {
            throw new InvalidArgumentException('Hash must be 7 characters');
        }

        if (! preg_match('/^[0-9a-fA-F]+$/', $hash)) {
            throw new InvalidArgumentException('Hash must contain only hex characters');
        }

        // Normalize to lowercase
        $hash = strtolower($hash);

        // Check for secret combos first
        $secret = $this->secretDetector->check($hash);
        if ($secret) {
            return [
                'type' => 'SECRET',
                'name' => $secret['name'],
                'payout' => $secret['payout'],
            ];
        }

        // Detect pattern - check in order of rarity/value
        $type = $this->detectPatternType($hash);

        $config = self::payouts()[$type];

        return [
            'type' => $type,
            'name' => $config['name'],
            'payout' => $config['payout'],
        ];
    }

    private function detectPatternType(string $hash): string
    {
        $distribution = $this->getDistribution($hash);

        // Check for the secret ultimate jackpot: 7777777
        if ($hash === '7777777') {
            return 'LUCKY_SEVENS';
        }

        // Check for all same first (highest value)
        if ($distribution[0] === 7) {
            return 'ALL_SAME';
        }

        // Check for 6 of a kind
        if ($distribution[0] === 6) {
            return 'SIX_OF_KIND';
        }

        // Check for straight 7 (very rare)
        if ($this->hasSequentialRun($hash, 7)) {
            return 'STRAIGHT_7';
        }

        // Check for fullest house (4-3)
        if ($distribution[0] === 4 && $distribution[1] === 3) {
            return 'FULLEST_HOUSE';
        }

        // Check for 5 of a kind
        if ($distribution[0] === 5) {
            return 'FIVE_OF_KIND';
        }

        // Check for straight 6
        if ($this->hasSequentialRun($hash, 6)) {
            return 'STRAIGHT_6';
        }

        // Check for 4 of a kind
        if ($distribution[0] === 4) {
            return 'FOUR_OF_KIND';
        }

        // Check for all letters
        if ($this->isAllLetters($hash)) {
            return 'ALL_LETTERS';
        }

        // Check for straight 5
        if ($this->hasSequentialRun($hash, 5)) {
            return 'STRAIGHT_5';
        }

        // Check for double triple (3-3-1)
        if ($distribution[0] === 3 && $distribution[1] === 3) {
            return 'THREE_OF_KIND_PLUS_THREE';
        }

        // Check for fuller house (3-2-2)
        if ($distribution[0] === 3 && $distribution[1] === 2 && $distribution[2] === 2) {
            return 'FULLER_HOUSE';
        }

        // Check for full house (3-2-1-1)
        if ($distribution[0] === 3 && $distribution[1] === 2) {
            return 'FULL_HOUSE';
        }

        // Check for 3 of a kind
        if ($distribution[0] === 3) {
            return 'THREE_OF_KIND';
        }

        // Check for three consecutive pairs
        if ($this->countConsecutivePairs($hash) === 3) {
            return 'THREE_PAIR';
        }

        // Check for two consecutive pairs
        if ($this->countConsecutivePairs($hash) === 2) {
            return 'TWO_PAIR';
        }

        // Check for all numbers
        if ($this->isAllNumbers($hash)) {
            return 'ALL_NUMBERS';
        }

        // Check for one consecutive pair
        if ($this->countConsecutivePairs($hash) === 1) {
            return 'ONE_PAIR';
        }

        // Everything else is no win
        return 'NO_WIN';
    }

    private function hasSequentialRun(string $hash, int $length): bool
    {
        $hexValues = '0123456789abcdef';

        for ($i = 0; $i <= strlen($hash) - $length; $i++) {
            $substring = substr($hash, $i, $length);

            // Check ascending
            $isAscending = true;
            for ($j = 0; $j < strlen($substring) - 1; $j++) {
                $currentIndex = strpos($hexValues, $substring[$j]);
                $nextIndex = strpos($hexValues, $substring[$j + 1]);
                if ($nextIndex !== $currentIndex + 1) {
                    $isAscending = false;
                    break;
                }
            }
            if ($isAscending) {
                return true;
            }

            // Check descending
            $isDescending = true;
            for ($j = 0; $j < strlen($substring) - 1; $j++) {
                $currentIndex = strpos($hexValues, $substring[$j]);
                $nextIndex = strpos($hexValues, $substring[$j + 1]);
                if ($nextIndex !== $currentIndex - 1) {
                    $isDescending = false;
                    break;
                }
            }
            if ($isDescending) {
                return true;
            }
        }

        return false;
    }

    private function isAllLetters(string $hash): bool
    {
        return preg_match('/^[a-f]+$/', $hash) === 1;
    }

    private function isAllNumbers(string $hash): bool
    {
        return preg_match('/^[0-9]+$/', $hash) === 1;
    }

    private function countConsecutivePairs(string $hash): int
    {
        // Count pairs of consecutive identical characters (e.g., "33" or "bb")
        $pairCount = 0;
        $i = 0;

        while ($i < strlen($hash) - 1) {
            if ($hash[$i] === $hash[$i + 1]) {
                $pairCount++;
                $i += 2; // Skip both characters of the pair
            } else {
                $i++;
            }
        }

        return $pairCount;
    }

    private function getDistribution(string $hash): array
    {
        $counts = [];
        for ($i = 0; $i < strlen($hash); $i++) {
            $char = $hash[$i];
            $counts[$char] = ($counts[$char] ?? 0) + 1;
        }

        $distribution = array_values($counts);
        rsort($distribution);

        // Ensure we always have at least 2 elements for safe access
        while (count($distribution) < 2) {
            $distribution[] = 0;
        }

        return $distribution;
    }
}
