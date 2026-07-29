<?php

it('exposes a full x.y.z version and a major.minor display label', function () {
    $version = config('app.version');

    expect($version)->toMatch('/^\d+\.\d+\.\d+/')
        ->and(config('app.version_display'))->toBe(implode('.', array_slice(explode('.', $version), 0, 2)));
});

// version_display must stay a prefix of version, or the terminal chrome and the
// update banner (meta[name=app-version]) start disagreeing about what's deployed.
it('keeps the display label a prefix of the full version', function (string $full, string $display) {
    config(['app.version' => $full]);

    expect(implode('.', array_slice(explode('.', $full), 0, 2)))->toBe($display)
        ->and($full)->toStartWith($display);
})->with([
    'plain' => ['3.1.0', '3.1'],
    'two-digit minor' => ['3.10.2', '3.10'],
    'prerelease suffix' => ['4.0.0-rc1', '4.0'],
]);

it('renders the display label in the terminal chrome', function () {
    $this->get('/')->assertSee('git-slot-machine v'.config('app.version_display'));
});
