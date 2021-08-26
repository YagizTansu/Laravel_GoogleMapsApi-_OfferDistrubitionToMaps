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
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckUserApiToken;
use App\Models\Currency;

Route::get('/', function () {
    return view('auth.login');
});

/*Route::group( ['middleware' => ['auth:sanctum', 'verified'],'prefix' =>'admin'], function () {
    Route::get('ships/{id}',[adminController::class,'destroy'])->whereNumber('id')->name('ships.destroy');
    Route::resource('ships',adminController::class);
});*/

//Route::middleware(['auth:sanctum', 'verified'])->get('/',[ShipController::class, 'show']);
//Route::middleware(['auth:sanctum', 'verified'])->get('/ships',[ShipController::class, 'show'])->name('ships');


//Route::post('/ship-add',[ShipController::class, 'add'])->name('ship-add');
//Route::post('/addCircle', [ShipController::class, 'addCircle'])->name('addCircle');
//Route::get('/getCacheFile', [CacheController::class, 'getCacheFile'])->name('getCacheFile');
//Route::post('/ajax-post', [ShipController::class, 'ajaxPost'])->name('ajax-post');


//Country controller
Route::get('/getCities', [CountryController::class, 'getCities'])->name('getCities');

//currency Controller
Route::get('/getCurrencySymbol', [CurrencyController::class, 'getCurrencySymbol'])->name('getCurrencySymbol');
Route::get('/getCurrencySellingValue', [CurrencyController::class, 'getCurrencySellingValue'])->name('getCurrencySellingValue');
Route::get('/getExchangeRate', [CurrencyController::class, 'getExchangeRate'])->name('getExchangeRate');

//offer Controller
//Route::get('/getOffers', [OfferController::class, 'getOffers'])->name('getOffers');
Route::get('/offers', [OfferController::class, 'index'])->name('offers');
Route::get('/offers-list', [OfferController::class, 'getOffersList'])->name('offers-list');
Route::get('/addOffer', [OfferController::class, 'addOffer'])->name('addOffer');
Route::get('offer-detail/{id}', [OfferController::class, 'detail'])->whereNumber('id')->name('detail');

Route::get('offer-edit/{id}', [OfferController::class, 'edit'])->whereNumber('id')->name('edit');
Route::get('offer-update/{id}', [AdminController::class, 'update'])->whereNumber('id')->name('offer-update');


//try
Route::get('try', function () {
    return view('try');
});
