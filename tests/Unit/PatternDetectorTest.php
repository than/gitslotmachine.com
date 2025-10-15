<?php

use App\Services\PatternDetector;

beforeEach(function () {
    $this->detector = new PatternDetector;
});

// Validation Tests
it('throws exception for hash too short', function () {
    $this->detector->detect('abc');
})->throws(InvalidArgumentException::class, 'Hash must be 7 characters');

it('throws exception for hash too long', function () {
    $this->detector->detect('abcd1234');
})->throws(InvalidArgumentException::class, 'Hash must be 7 characters');

it('throws exception for non-hex characters', function () {
    $this->detector->detect('gggg123');
})->throws(InvalidArgumentException::class, 'Hash must contain only hex characters');

it('throws exception for invalid characters', function () {
    $this->detector->detect('xyz1234');
})->throws(InvalidArgumentException::class, 'Hash must contain only hex characters');

// ALL_SAME (JACKPOT)
it('detects ALL_SAME pattern', function () {
    $result = $this->detector->detect('aaaaaaa');

    expect($result)->toBe([
        'type' => 'ALL_SAME',
        'name' => 'JACKPOT',
        'payout' => 10000,
    ]);
});

it('detects ALL_SAME with zeros', function () {
    $result = $this->detector->detect('0000000');

    expect($result['type'])->toBe('ALL_SAME');
    expect($result['payout'])->toBe(10000);
});

// STRAIGHT_7 (7 in a row)
it('detects STRAIGHT_7 ascending', function () {
    $result = $this->detector->detect('0123456');

    expect($result)->toBe([
        'type' => 'STRAIGHT_7',
        'name' => 'LUCKY SEVEN',
        'payout' => 2500,
    ]);
});

it('detects STRAIGHT_7 descending', function () {
    $result = $this->detector->detect('fedcba9');

    expect($result['type'])->toBe('STRAIGHT_7');
    expect($result['payout'])->toBe(2500);
});

// SIX_OF_KIND
it('detects SIX_OF_KIND', function () {
    $result = $this->detector->detect('aaaaaa1');

    expect($result)->toBe([
        'type' => 'SIX_OF_KIND',
        'name' => 'HEXTET',
        'payout' => 5000,
    ]);
});

// STRAIGHT_6 (6 in a row)
it('detects STRAIGHT_6', function () {
    $result = $this->detector->detect('012345a');

    expect($result)->toBe([
        'type' => 'STRAIGHT_6',
        'name' => 'BIG STRAIGHT',
        'payout' => 500,
    ]);
});

// FULLEST_HOUSE (4+3)
it('detects FULLEST_HOUSE', function () {
    $result = $this->detector->detect('aaaabbb');

    expect($result)->toBe([
        'type' => 'FULLEST_HOUSE',
        'name' => 'FULLEST HOUSE',
        'payout' => 2000,
    ]);
});

// STRAIGHT_5 (5 in a row)
it('detects STRAIGHT_5', function () {
    $result = $this->detector->detect('01234ab');

    expect($result)->toBe([
        'type' => 'STRAIGHT_5',
        'name' => 'STRAIGHT',
        'payout' => 200,
    ]);
});

// FIVE_OF_KIND
it('detects FIVE_OF_KIND', function () {
    $result = $this->detector->detect('aaaaa12');

    expect($result)->toBe([
        'type' => 'FIVE_OF_KIND',
        'name' => 'FIVE OF A KIND',
        'payout' => 1000,
    ]);
});

// FOUR_OF_KIND
it('detects FOUR_OF_KIND', function () {
    $result = $this->detector->detect('aaaa123');

    expect($result)->toBe([
        'type' => 'FOUR_OF_KIND',
        'name' => 'FOUR OF A KIND',
        'payout' => 400,
    ]);
});

// THREE_OF_KIND_PLUS_THREE (DOUBLE_TRIPLE)
it('detects THREE_OF_KIND_PLUS_THREE', function () {
    $result = $this->detector->detect('aaabbb1');

    expect($result)->toBe([
        'type' => 'THREE_OF_KIND_PLUS_THREE',
        'name' => 'DOUBLE TRIPLE',
        'payout' => 150,
    ]);
});

// ALL_LETTERS (no straights)
it('detects ALL_LETTERS', function () {
    $result = $this->detector->detect('abacfed');

    expect($result)->toBe([
        'type' => 'ALL_LETTERS',
        'name' => 'ALL LETTERS',
        'payout' => 300,
    ]);
});

// FULL_HOUSE (3-2 pattern)
it('detects FULL_HOUSE', function () {
    $result = $this->detector->detect('aaabbc1');

    expect($result)->toBe([
        'type' => 'FULL_HOUSE',
        'name' => 'FULL HOUSE',
        'payout' => 100,
    ]);
});

// THREE_PAIR
it('detects THREE_PAIR', function () {
    $result = $this->detector->detect('aabbcc1');

    expect($result)->toBe([
        'type' => 'THREE_PAIR',
        'name' => 'THREE PAIR',
        'payout' => 150,
    ]);
});

it('detects THREE_PAIR with scattered pairs', function () {
    // 11aa22b has three consecutive pairs scattered throughout
    $result = $this->detector->detect('11aa22b');

    expect($result['type'])->toBe('THREE_PAIR');
    expect($result['payout'])->toBe(150);
});

// THREE_OF_KIND
it('detects THREE_OF_KIND', function () {
    $result = $this->detector->detect('aaa1234');

    expect($result)->toBe([
        'type' => 'THREE_OF_KIND',
        'name' => 'THREE OF A KIND',
        'payout' => 50,
    ]);
});

// TWO_PAIR
it('detects TWO_PAIR', function () {
    $result = $this->detector->detect('aabb123');

    expect($result)->toBe([
        'type' => 'TWO_PAIR',
        'name' => 'TWO PAIR',
        'payout' => 50,
    ]);
});

it('does not detect TWO_PAIR for non-consecutive pairs', function () {
    // d0cca60 has two 0s and two cs but they're not consecutive pairs
    $result = $this->detector->detect('d0cca60');

    expect($result['type'])->toBe('NO_WIN');
    expect($result['payout'])->toBe(0);
});

// ALL_NUMBERS
it('detects ALL_NUMBERS when no straight', function () {
    $result = $this->detector->detect('1230984');

    expect($result)->toBe([
        'type' => 'ALL_NUMBERS',
        'name' => 'ALL NUMBERS',
        'payout' => 10,
    ]);
});

it('detects STRAIGHT_7 not ALL_NUMBERS for sequential digits', function () {
    $result = $this->detector->detect('1234567');

    expect($result['type'])->toBe('STRAIGHT_7');
    expect($result['payout'])->toBe(2500);
});

// NO_WIN (no pattern, all unique or single pair)
it('detects NO_WIN for single pair no straight', function () {
    $result = $this->detector->detect('aa13579');

    expect($result)->toBe([
        'type' => 'NO_WIN',
        'name' => 'NO WIN',
        'payout' => 0,
    ]);
});

// NO_WIN
it('detects NO_WIN', function () {
    $result = $this->detector->detect('abcd123');

    expect($result)->toBe([
        'type' => 'NO_WIN',
        'name' => 'NO WIN',
        'payout' => 0,
    ]);
});

// Case normalization
it('normalizes uppercase to lowercase', function () {
    $result = $this->detector->detect('AAAAAAA');

    expect($result['type'])->toBe('ALL_SAME');
});
