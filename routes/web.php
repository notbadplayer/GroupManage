<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
Route::middleware(['auth', 'verified'])->group(function(){
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    //Users:
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/data', [UserController::class, 'data'])->name('users.data');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users/store', [UserController::class, 'store'])->name('users.store');

});


Auth::routes();


