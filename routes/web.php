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

Route::group( ['middleware' => ['auth:sanctum', 'verified','isAdmin'],'prefix' =>'admin'], function () {
    Route::get('ships/{id}',[adminController::class,'destroy'])->whereNumber('id')->name('ships.destroy');
    Route::resource('ships',adminController::class);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/',[ShipController::class, 'show'])->name('ships');
Route::middleware(['auth:sanctum', 'verified'])->get('/ships',[ShipController::class, 'show'])->name('ships');
Route::get('/trucks', [TruckController::class, 'show'])->name('trucks');
