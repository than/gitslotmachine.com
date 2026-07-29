<?php

it('exposes a full x.y.z version and a major.minor display label', function () {
    $version = config('app.version');

    expect($version)->toMatch('/^\d+\.\d+\.\d+/')
        ->and(config('app.version_display'))->toBe(implode('.', array_slice(explode('.', $version), 0, 2)));
});

// version_display must stay a prefix of version, or the terminal chrome and the update
// banner (meta[name=app-version]) start disagreeing about what's deployed.
//
// This re-requires config/app.php rather than calling config()->set: version_display is
// derived once at config-load from a local in that file, so a runtime set can never
// re-derive it and the test would assert nothing.
it('derives the display label from the env value', function (string $full, string $display) {
    // Both superglobals: phpdotenv's default adapters read $_SERVER before $_ENV, so
    // setting only $_ENV would be silently overridden anywhere APP_VERSION is actually
    // set — a real OS env var, or .env once the commented line is uncommented.
    $original = ['env' => $_ENV['APP_VERSION'] ?? null, 'server' => $_SERVER['APP_VERSION'] ?? null];
    $_ENV['APP_VERSION'] = $_SERVER['APP_VERSION'] = $full;

    try {
        $config = require config_path('app.php');
    } finally {
        foreach (['env' => '_ENV', 'server' => '_SERVER'] as $key => $global) {
            if ($original[$key] === null) {
                unset($GLOBALS[$global]['APP_VERSION']);
            } else {
                $GLOBALS[$global]['APP_VERSION'] = $original[$key];
            }
        }
    }

    expect($config['version_display'])->toBe($display)
        ->and($config['version'])->toBe($full)
        ->toStartWith($display);
})->with([
    'plain' => ['3.1.0', '3.1'],
    'two-digit minor' => ['3.10.2', '3.10'],
    'prerelease suffix' => ['4.0.0-rc1', '4.0'],
]);

it('renders the display label in the terminal chrome', function () {
    $this->get('/')->assertSee('git-slot-machine v'.config('app.version_display'));
});
