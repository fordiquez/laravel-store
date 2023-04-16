<?php

use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\LocationController;
use Illuminate\Support\Facades\Route;

Route::prefix('locations')->controller(LocationController::class)->group(function () {
    Route::get('countries', 'countries')->name('locations.countries');
    Route::get('{country}/states', 'states')->name('locations.states');
    Route::get('{state}/cities', 'cities')->name('locations.cities');
});

Route::get('categories', [CategoryController::class, 'index'])->name('categories');
