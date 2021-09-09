<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\CurrencyController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group( ['middleware' => ['CheckUserApiToken']], function () {
    Route::get('/createApiToken', [UserController::class, 'createApiToken']);

    // get offfers
    Route::get('/offerAjax', [OfferController::class, 'offerAjax'])->name('offerAjax');

});
//use
Route::get('/getCurrency', [CurrencyController::class, 'getCurrency']);
Route::get('/getCities', [CountryController::class, 'getCities'])->name('getCities');
Route::get('/getCountries', [CountryController::class, 'getCountries'])->name('getCountries');

