<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\TokenController;
use App\Http\Controllers\Admin\UserController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('admin.tokens.index');
    } else {
        return redirect()->route('login');
    }
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/tokens', [TokenController::class, 'index'])->name('admin.tokens.index');
    Route::get('/tokens/create', [TokenController::class, 'createForm'])->name('admin.tokens.create');
    Route::post('/tokens', [TokenController::class, 'store'])->name('admin.tokens.store');
    Route::delete('/tokens/{token}', [TokenController::class, 'destroy'])->name('admin.tokens.destroy');
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});

require __DIR__.'/auth.php';
