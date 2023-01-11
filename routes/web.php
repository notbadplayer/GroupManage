<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\SubgroupController;
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
    Route::get('users/edit/{User}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/update/{User}', [UserController::class, 'update'])->name('users.update');

    //Groups:
    Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
    Route::get('groups/data', [GroupController::class, 'data'])->name('groups.data');
    Route::get('groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('groups/store', [GroupController::class, 'store'])->name('groups.store');
    Route::get('groups/edit/{Group}', [GroupController::class, 'edit'])->name('groups.edit');
    Route::put('groups/update/{Group}', [GroupController::class, 'update'])->name('groups.update');

    //members of group:
    Route::get('groups/members/{Group}', [GroupController::class, 'members'])->name('groups.members');

    //Subgroups:
    Route::get('groups/{Group}/createSubgroup', [SubgroupController::class, 'create'])->name('subgroups.create');
    Route::post('subgroups/store', [SubgroupController::class, 'store'])->name('subgroups.store');
    Route::get('subgroups/edit/{Subgroup}', [SubgroupController::class, 'edit'])->name('subgroups.edit');
    Route::put('subgroups/update/{Subgroup}', [SubgroupController::class, 'update'])->name('subgroups.update');


});


Auth::routes();


