<?php

use Illuminate\Support\Facades\Route;
use Orion\Facades\Orion;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\CheckToken;

Route::middleware('api')->group(function () {
    Route::middleware('api')->group(function () {
        Route::middleware(CheckToken::class)->group(function () {
            Orion::resource('users', UserController::class);
        });
    });

    Route::get('/debug', function() {
        return response()->json(['message' => 'API is working']);
    });
});

include __DIR__.'/fred.php';
include __DIR__.'/finance.php';
