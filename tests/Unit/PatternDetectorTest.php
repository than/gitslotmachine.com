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
        'payout' => 100000,
    ]);
});

it('detects ALL_SAME with zeros', function () {
    $result = $this->detector->detect('0000000');

    expect($result['type'])->toBe('ALL_SAME');
    expect($result['payout'])->toBe(100000);
});

// STRAIGHT_7 (7 in a row)
it('detects STRAIGHT_7 ascending', function () {
    $result = $this->detector->detect('0123456');

    expect($result)->toBe([
        'type' => 'STRAIGHT_7',
        'name' => 'LUCKY SEVEN',
        'payout' => 50000,
    ]);
});

it('detects STRAIGHT_7 descending', function () {
    $result = $this->detector->detect('fedcba9');

    expect($result['type'])->toBe('STRAIGHT_7');
    expect($result['payout'])->toBe(50000);
});

// SIX_OF_KIND
it('detects SIX_OF_KIND', function () {
    $result = $this->detector->detect('aaaaaa1');

    expect($result)->toBe([
        'type' => 'SIX_OF_KIND',
        'name' => 'HEXTET',
        'payout' => 10000,
    ]);
});

// STRAIGHT_6 (6 in a row)
it('detects STRAIGHT_6', function () {
    $result = $this->detector->detect('012345a');

    expect($result)->toBe([
        'type' => 'STRAIGHT_6',
        'name' => 'BIG STRAIGHT',
        'payout' => 25000,
    ]);
});

// FULLEST_HOUSE (4+3)
it('detects FULLEST_HOUSE', function () {
    $result = $this->detector->detect('aaaabbb');

    expect($result)->toBe([
        'type' => 'FULLEST_HOUSE',
        'name' => 'FULLEST HOUSE',
        'payout' => 5000,
    ]);
});

// STRAIGHT_5 (5 in a row)
it('detects STRAIGHT_5', function () {
    $result = $this->detector->detect('01234ab');

    expect($result)->toBe([
        'type' => 'STRAIGHT_5',
        'name' => 'STRAIGHT',
        'payout' => 2500,
    ]);
});

// FIVE_OF_KIND
it('detects FIVE_OF_KIND', function () {
    $result = $this->detector->detect('aaaaa12');

    expect($result)->toBe([
        'type' => 'FIVE_OF_KIND',
        'name' => 'FIVE OF A KIND',
        'payout' => 2000,
    ]);
});

// FOUR_OF_KIND
it('detects FOUR_OF_KIND', function () {
    $result = $this->detector->detect('aaaa123');

    expect($result)->toBe([
        'type' => 'FOUR_OF_KIND',
        'name' => 'FOUR OF A KIND',
        'payout' => 200,
    ]);
});

// THREE_OF_KIND_PLUS_THREE (DOUBLE_TRIPLE)
it('detects THREE_OF_KIND_PLUS_THREE', function () {
    $result = $this->detector->detect('aaabbb1');

    expect($result)->toBe([
        'type' => 'THREE_OF_KIND_PLUS_THREE',
        'name' => 'DOUBLE TRIPLE',
        'payout' => 1000,
    ]);
});

// ALL_LETTERS (no straights)
it('detects ALL_LETTERS', function () {
    $result = $this->detector->detect('abacfed');

    expect($result)->toBe([
        'type' => 'ALL_LETTERS',
        'name' => 'ALPHABET SOUP',
        'payout' => 250,
    ]);
});

// FULL_HOUSE (3-2 pattern)
it('detects FULL_HOUSE', function () {
    $result = $this->detector->detect('aaabbc1');

    expect($result)->toBe([
        'type' => 'FULL_HOUSE',
        'name' => 'FULL HOUSE',
        'payout' => 50,
    ]);
});

// THREE_PAIR
it('detects THREE_PAIR', function () {
    $result = $this->detector->detect('aabbcc1');

    expect($result)->toBe([
        'type' => 'THREE_PAIR',
        'name' => 'THREE PAIR',
        'payout' => 500,
    ]);
});

it('detects THREE_PAIR with scattered pairs', function () {
    // 11aa22b has three consecutive pairs scattered throughout
    $result = $this->detector->detect('11aa22b');

    expect($result['type'])->toBe('THREE_PAIR');
    expect($result['payout'])->toBe(500);
});

// THREE_OF_KIND
it('detects THREE_OF_KIND', function () {
    $result = $this->detector->detect('aaa1234');

    expect($result)->toBe([
        'type' => 'THREE_OF_KIND',
        'name' => 'THREE OF A KIND',
        'payout' => 25,
    ]);
});

// TWO_PAIR
it('detects TWO_PAIR', function () {
    $result = $this->detector->detect('aabb123');

    expect($result)->toBe([
        'type' => 'TWO_PAIR',
        'name' => 'TWO PAIR',
        'payout' => 25,
    ]);
});

it('does not detect TWO_PAIR for non-consecutive pairs', function () {
    // 0c0c123 has two 0s and two cs, but none are *consecutive* pairs,
    // so it should not win (consecutive 'cc'/'00' would make it ONE_PAIR).
    $result = $this->detector->detect('0c0c123');

    expect($result['type'])->toBe('NO_WIN');
    expect($result['payout'])->toBe(0);
});

// ALL_NUMBERS
it('detects ALL_NUMBERS when no straight', function () {
    $result = $this->detector->detect('1230984');

    expect($result)->toBe([
        'type' => 'ALL_NUMBERS',
        'name' => 'ALL NUMBERS',
        'payout' => 50,
    ]);
});

it('detects STRAIGHT_7 not ALL_NUMBERS for sequential digits', function () {
    $result = $this->detector->detect('1234567');

    expect($result['type'])->toBe('STRAIGHT_7');
    expect($result['payout'])->toBe(50000);
});

// ONE_PAIR (a single consecutive pair now pays out, no longer NO_WIN)
it('detects ONE_PAIR for single pair no straight', function () {
    $result = $this->detector->detect('aa13579');

    expect($result)->toBe([
        'type' => 'ONE_PAIR',
        'name' => 'ONE PAIR',
        'payout' => 10,
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
