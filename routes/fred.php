<?php

use App\Http\Controllers\Api\Fred\Cpi;
use App\Http\Middleware\CheckToken;
use Illuminate\Support\Facades\Route;
use Orion\Facades\Orion;

Route::prefix('fred')->group(function () {
    Route::middleware('api')->group(function () {
        Route::middleware(CheckToken::class)->group(function () {
            Orion::resource('cpi', Cpi::class);
        });
    });
});
