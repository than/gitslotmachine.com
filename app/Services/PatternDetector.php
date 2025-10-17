<?php

namespace App\Services;

use InvalidArgumentException;

class PatternDetector
{
    private const PAYOUTS = [
        'LUCKY_SEVENS' => ['name' => 'LUCKY SEVENS', 'payout' => 1000000],
        'ALL_SAME' => ['name' => 'JACKPOT', 'payout' => 100000],
        'STRAIGHT_7' => ['name' => 'LUCKY SEVEN', 'payout' => 50000],
        'STRAIGHT_6' => ['name' => 'BIG STRAIGHT', 'payout' => 25000],
        'SIX_OF_KIND' => ['name' => 'HEXTET', 'payout' => 10000],
        'FULLEST_HOUSE' => ['name' => 'FULLEST HOUSE', 'payout' => 5000],
        'STRAIGHT_5' => ['name' => 'STRAIGHT', 'payout' => 2500],
        'FIVE_OF_KIND' => ['name' => 'FIVE OF A KIND', 'payout' => 2000],
        'THREE_OF_KIND_PLUS_THREE' => ['name' => 'DOUBLE TRIPLE', 'payout' => 1000],
        'THREE_PAIR' => ['name' => 'THREE PAIR', 'payout' => 500],
        'FULL_HOUSE' => ['name' => 'FULL HOUSE', 'payout' => 300],
        'ALL_LETTERS' => ['name' => 'ALPHABET SOUP', 'payout' => 250],
        'FOUR_OF_KIND' => ['name' => 'FOUR OF A KIND', 'payout' => 200],
        'THREE_OF_KIND' => ['name' => 'THREE OF A KIND', 'payout' => 100],
        'TWO_PAIR' => ['name' => 'TWO PAIR', 'payout' => 25],
        'ALL_NUMBERS' => ['name' => 'ALL NUMBERS', 'payout' => 10],
        'NO_WIN' => ['name' => 'NO WIN', 'payout' => 0],
    ];

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

        // Detect pattern - check in order of rarity/value
        $type = $this->detectPatternType($hash);

        $config = self::PAYOUTS[$type];

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

        // Check for full house (3-2-2 or 3-2-1-1)
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

        // Check for all numbers (break-even)
        if ($this->isAllNumbers($hash)) {
            return 'ALL_NUMBERS';
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
