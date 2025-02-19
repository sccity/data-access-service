<?php

use Illuminate\Support\Facades\Route;
use Orion\Facades\Orion;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\FredController;

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

// Your Orion API routes will go here 

Orion::resource('users', UserController::class);

// Add this custom route before the resource route
Route::get('fred/cpi', [FredController::class, 'cpi'])
    ->middleware(['check.token', 'auth:api']);

Orion::resource('fred', FredController::class); 