<?php

use App\Http\Controllers\Main\CartController;
use App\Http\Controllers\Main\GoodController;
use App\Http\Controllers\Main\IndexController;
use App\Http\Controllers\Main\ProfileController;
use Illuminate\Support\Facades\Route;

Route::controller(IndexController::class)->group(function () {
    Route::get('', 'dashboard')->name('index.dashboard');
    Route::get('category/{category}', 'category')->name('index.category');
});

Route::prefix('goods')->controller(GoodController::class)->group(function () {
    Route::get('search', 'search')->name('goods.search');
    Route::get('{category}', 'goods')->name('goods.index');
    Route::get('{good}/general', 'index')->name('goods.good.general');
    Route::get('{good}/properties', 'properties')->name('goods.good.properties');
    Route::get('{good}/reviews', 'reviews')->name('goods.good.reviews');
});

Route::prefix('cart')->controller(CartController::class)->group(function () {
    Route::post('store/{good}', 'store')->name('cart.store');
    Route::patch('update/{good}', 'update')->name('cart.update');
    Route::delete('delete/{good}', 'delete')->name('cart.delete');
    Route::delete('bulk-delete', 'bulkDelete')->name('cart.bulk-delete');
});

Route::middleware('auth')->group(function () {
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
