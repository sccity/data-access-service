<?php

use App\Http\Controllers\Api\Finance\Budget;
use App\Http\Controllers\Api\Finance\Employee;
use App\Http\Controllers\Api\Finance\ExpenseDetail;
use App\Http\Controllers\Api\Finance\Pastdue;
use App\Http\Controllers\Api\Finance\RevenueDetail;
use App\Http\Middleware\CheckToken;
use Illuminate\Support\Facades\Route;
use Orion\Facades\Orion;

Route::prefix('finance')->group(function () {
    Route::middleware('api')->group(function () {
        Route::middleware(CheckToken::class)->group(function () {
            Orion::resource('employee', Employee::class);
            Orion::resource('pastdue', Pastdue::class);
            Orion::resource('budget', Budget::class);
            Orion::resource('expense', ExpenseDetail::class);
            Orion::resource('revenue', RevenueDetail::class);
        });
    });
});
