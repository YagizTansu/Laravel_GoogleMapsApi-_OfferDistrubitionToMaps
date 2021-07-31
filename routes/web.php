<?php

use App\Http\Controllers\Admin\adminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Models\Ship;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/ships', function () {
    $ships = Ship::get();
    return view('ships',compact('ships'));
})->name('ships');

Route::group( ['middleware' => ['auth:sanctum', 'verified'],'prefix' =>'admin'], function () {
    Route::resource('ships',adminController::class);
});
