<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\CurrencyController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group( ['middleware' => ['CheckUserApiToken']], function () {
    Route::get('/createApiToken', [UserController::class, 'createApiToken']);

    // important apis
    Route::get('/offerAjax', [OfferController::class, 'offerAjax'])->name('offerAjax');

});
//use
Route::get('/getCurrency', [CurrencyController::class, 'getCurrency']);
Route::get('/getCities', [CountryController::class, 'getCities'])->name('getCities');
Route::get('/getCountries', [CountryController::class, 'getCountries'])->name('getCountries');


