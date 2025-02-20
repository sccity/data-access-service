<?php

use Illuminate\Support\Facades\Route;
use Orion\Facades\Orion;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CpiController;
use App\Http\Middleware\CheckToken;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

Route::middleware('api')->group(function () {
    // Your Orion API routes will go here 
    Route::middleware(CheckToken::class)->group(function () {
        Orion::resource('users', UserController::class);
        Orion::resource('cpi', CpiController::class);
    });

    // Add this temporary route for debugging
    Route::get('/debug', function() {
        return response()->json(['message' => 'API is working']);
    });
}); 