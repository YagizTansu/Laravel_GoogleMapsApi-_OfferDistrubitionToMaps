<?php

use App\Http\Controllers\Admin\adminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Models\Ship;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TruckController;
use App\Http\Controllers\ShipController;

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

Route::get('/ajax', [ShipController::class, 'index'])->name('ajax');
Route::post('/ajax-post', [ShipController::class, 'ajaxPost'])->name('ajax-post');

Route::get('/ajax-get-exchange-rate', [ShipController::class, 'getExchangeRate'])->name('get-exchange-rate');
Route::post('/addCircle', [ShipController::class, 'addCircle'])->name('addCircle');
