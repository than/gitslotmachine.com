<?php

use App\Services\PatternDetector;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('loads the odds page successfully', function () {
    $this->get('/odds')->assertOk();
});

// EXAMPLE VALIDATION: every example hash in the canonical ruleset must detect as its own type,
// guarding the example hashes the odds page displays.
it('detects each canonical example as its own pattern type', function () {
    $detector = new PatternDetector;

    $patterns = json_decode(
        (string) file_get_contents(resource_path('data/patterns.json')),
        true,
    )['patterns'];

    foreach ($patterns as $pattern) {
        expect($detector->detect($pattern['example'])['type'])->toBe($pattern['type']);
    }
});
