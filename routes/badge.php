<?php

use App\Http\Controllers\BadgeController;
use Illuminate\Support\Facades\Route;

// Badge routes will be accessible via badge.gitslotmachine.test
// For example: badge.gitslotmachine.test/thantibbetts/test-repo.svg

Route::get('/{owner}/{repo}.svg', [BadgeController::class, 'show']);
