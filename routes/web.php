<?php

use App\Http\Controllers\Main\IndexController;
use App\Http\Controllers\Main\ProfileController;
use Illuminate\Support\Facades\Route;

Route::controller(IndexController::class)->group(function () {
    Route::get('', 'dashboard')->name('index.dashboard');
    Route::get('category/{category}', 'category')->name('index.category');
    Route::get('goods/search', 'search')->name('index.search');
    Route::get('goods/{category}', 'goods')->name('index.goods');
    Route::get('good/{good}', 'good')->name('index.good');
    Route::get('good/{good}/properties', 'goodProperties')->name('index.good.properties');
    Route::get('good/{good}/reviews', 'goodReviews')->name('index.good.reviews');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
