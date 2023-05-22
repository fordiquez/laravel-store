<?php

use App\Http\Controllers\API\IndexController;
use Illuminate\Support\Facades\Route;

Route::controller(IndexController::class)->group(function () {
    Route::get('countries', 'countries')->name('api.locations.countries');
    Route::get('{country}/states', 'states')->name('api.locations.states');
    Route::get('{state}/cities', 'cities')->name('api.locations.cities');
    Route::get('categories', 'categories')->name('api.categories');
    Route::post('verify-promo-code/key', 'verifyPromoCode')->name('api.verify-promo-code');
});
