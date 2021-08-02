<?php

use App\Http\Controllers\Admin\adminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Models\Ship;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/', function () {
    $ships = DB::table('ships')->where('user_id','=', Auth::id())->get();
    return view('ships',compact('ships'));
})->name('ships');

Route::middleware(['auth:sanctum', 'verified'])->get('/ships', function () {
    $ships = DB::table('ships')->where('user_id','=', Auth::id())->get();
    return view('ships',compact('ships'));
})->name('ships');

Route::group( ['middleware' => ['auth:sanctum', 'verified','isAdmin'],'prefix' =>'admin'], function () {
    Route::get('ships/{id}',[adminController::class,'destroy'])->whereNumber('id')->name('ships.destroy');
    Route::resource('ships',adminController::class);
});
