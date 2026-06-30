<?php

use App\Services\PatternDetector;

beforeEach(function () {
    $this->detector = new PatternDetector;
});

/**
 * @return array<int, array{hash: string, type: string, name: string, payout: int}>
 */
function goldenVectors(): array
{
    $path = dirname(__DIR__).'/Fixtures/golden-vectors.json';

    return json_decode((string) file_get_contents($path), true);
}

/**
 * @return array{rulesetVersion: int, totalHashes: int, patterns: array<int, array<string, mixed>>}
 */
function canonicalPatterns(): array
{
    $path = dirname(__DIR__, 2).'/resources/data/patterns.json';

    return json_decode((string) file_get_contents($path), true);
}

// GOLDEN CONTRACT: every vector in the shared fixture must round-trip through the detector.
it('matches every golden vector', function () {
    foreach (goldenVectors() as $vector) {
        expect($this->detector->detect($vector['hash']))->toBe([
            'type' => $vector['type'],
            'name' => $vector['name'],
            'payout' => $vector['payout'],
        ]);
    }
});

// CANONICAL CONTRACT: the detector's name + payout must equal the canonical ruleset for each example.
it('matches the canonical ruleset for each pattern example', function () {
    foreach (canonicalPatterns()['patterns'] as $pattern) {
        $result = $this->detector->detect($pattern['example']);

        expect($result['name'])->toBe($pattern['name'])
            ->and($result['payout'])->toBe($pattern['payout']);
    }
});

it('uses ruleset version 3', function () {
    expect(canonicalPatterns()['rulesetVersion'])->toBe(3);
});

// RTP BAND: sum(payout * net) / totalHashes, scaled by the $10 cost, must land in the target band.
it('keeps the RTP within the target band', function () {
    $data = canonicalPatterns();

    $expectedReturn = 0;
    foreach ($data['patterns'] as $pattern) {
        $expectedReturn += $pattern['payout'] * $pattern['net'];
    }

    $rtp = $expectedReturn / $data['totalHashes'] / 10;

    expect($rtp)->toBeGreaterThanOrEqual(1.05)
        ->and($rtp)->toBeLessThanOrEqual(1.10);
});

// TRICKY RULES: hashes that exercise the priority ordering and look-alike traps.
it('resolves tricky hashes to the correct type', function (string $hash, string $type) {
    expect($this->detector->detect($hash)['type'])->toBe($type);
})->with([
    'interleaved repeats are no win' => ['a1a2345', 'NO_WIN'],
    'mirrored prefix is no win' => ['ab1ab2c', 'NO_WIN'],
    'wrap-around letters are a six straight' => ['abcdefa', 'STRAIGHT_6'],
    'two triples of letters are alphabet soup' => ['aaabbcc', 'ALL_LETTERS'],
    'sequential digits are a seven straight' => ['1234567', 'STRAIGHT_7'],
    'all sevens are lucky sevens' => ['7777777', 'LUCKY_SEVENS'],
]);
