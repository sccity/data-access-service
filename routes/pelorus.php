<?php

use Illuminate\Support\Facades\Route;
use Orion\Facades\Orion;
use App\Http\Middleware\CheckToken;

use App\Http\Controllers\Api\Pelorus\EmployeeController;

Route::prefix('pelorus')->group(function () {
    Route::middleware('api')->group(function () {
        Route::middleware(CheckToken::class)->group(function () {
            Orion::resource('employee', EmployeeController::class);
        });
    });
});
