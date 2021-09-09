<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\adminController;


Route::get('/', function () {
    return view('auth.login');
});

//offer Controller
Route::get('/addOffer', [OfferController::class, 'addOffer'])->name('addOffer');
Route::get('/offers', [OfferController::class, 'index'])->name('offers');
Route::get('offer-detail/{id}', [OfferController::class, 'detail'])->whereNumber('id')->name('detail');
Route::get('offer-edit/{id}', [OfferController::class, 'edit'])->whereNumber('id')->name('edit');
Route::post('/offerAddManully', [OfferController::class, 'offerAddManully'])->name('offerAddManully');

Route::group( ['middleware' => ['auth:sanctum', 'verified'],'prefix' =>'panel'], function () {
    Route::resource('offers',adminController::class);
});


//try
Route::get('try', function () {
    return view('try');
});


