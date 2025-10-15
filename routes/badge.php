<?php

use Illuminate\Support\Facades\Route;

// Badge routes will be accessible via badge.gitslotmachine.test
// For example: badge.gitslotmachine.test/thantibbetts/test-repo

Route::get('/{owner}/{repo}', function (string $owner, string $repo) {
    return response('Badge generation coming soon!', 200)
        ->header('Content-Type', 'text/plain');
});
