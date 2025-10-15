<?php

use App\Http\Controllers\Api\PlayController;
use Illuminate\Support\Facades\Route;

Route::post('/play', [PlayController::class, 'store']);
