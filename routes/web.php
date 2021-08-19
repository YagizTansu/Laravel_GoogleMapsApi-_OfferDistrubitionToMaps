<?php

use App\Http\Controllers\Admin\adminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Models\Ship;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TruckController;
use App\Http\Controllers\ShipController;
use App\Http\Controllers\CacheController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\OfferController;
use App\Models\Currency;

Route::get('/', function () {
    return view('auth.login');
});

Route::group( ['middleware' => ['auth:sanctum', 'verified'],'prefix' =>'admin'], function () {
    Route::get('ships/{id}',[adminController::class,'destroy'])->whereNumber('id')->name('ships.destroy');
    Route::resource('ships',adminController::class);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/',[ShipController::class, 'show']);
Route::middleware(['auth:sanctum', 'verified'])->get('/ships',[ShipController::class, 'show'])->name('ships');

Route::get('/trucks', [TruckController::class, 'show'])->name('trucks');
Route::get('ship-detail/{id}', [ShipController::class, 'detail'])->whereNumber('id')->name('detail');

Route::post('/ship-add',[ShipController::class, 'add'])->name('ship-add');

Route::get('/distribution', [ShipController::class, 'index'])->name('distribution');
//Route::post('/ajax-post', [ShipController::class, 'ajaxPost'])->name('ajax-post');

Route::get('/ajax-get-exchange-rate', [ShipController::class, 'getExchangeRate'])->name('get-exchange-rate');
Route::post('/addCircle', [ShipController::class, 'addCircle'])->name('addCircle');

Route::get('/getCacheFile', [CacheController::class, 'getCacheFile'])->name('getCacheFile');

Route::get('/getCountries', [CountryController::class, 'getCountries'])->name('getCountries');
Route::get('/getCities', [CountryController::class, 'getCities'])->name('getCities');
Route::get('/getOffers', [OfferController::class, 'getOffers'])->name('getOffers');
Route::get('/getCurrencySymbol', [CurrencyController::class, 'getCurrencySymbol'])->name('getCurrencySymbol');
Route::get('/getCurrencySellingValue', [CurrencyController::class, 'getCurrencySellingValue'])->name('getCurrencySellingValue');
