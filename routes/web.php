<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\adminController;


Route::get('/', function () {
    return view('auth.login');
});

//currency Controller
Route::get('/getExchangeRate', [CurrencyController::class, 'getExchangeRate'])->name('getExchangeRate');


//offer Controller
Route::get('/addOffer', [OfferController::class, 'addOffer'])->name('addOffer');

Route::get('/offers', [OfferController::class, 'index'])->name('offers');

Route::get('offer-detail/{id}', [OfferController::class, 'detail'])->whereNumber('id')->name('detail');

Route::get('offer-edit/{id}', [OfferController::class, 'edit'])->whereNumber('id')->name('edit');

Route::group( ['middleware' => ['auth:sanctum', 'verified'],'prefix' =>'panel'], function () {
    Route::resource('offers',adminController::class);
});


//try
Route::get('try', function () {
    return view('try');
});

//try
Route::get('/control', function () {
    return view('control');
});


Route::post('/offerAddManully', [OfferController::class, 'offerAddManully'])->name('offerAddManully');
