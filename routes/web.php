<?php

use App\Http\Controllers\Main\IndexController;
use App\Http\Controllers\Main\ProfileController;
use Illuminate\Support\Facades\Route;

Route::controller(IndexController::class)->group(function () {
    Route::get('', 'dashboard')->name('index.dashboard');
    Route::get('content/{category}', 'content')->name('index.content');
    Route::get('good/{good}', 'good')->name('index.good');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
