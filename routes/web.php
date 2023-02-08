<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\PublicationController;
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
    Route::get('groups/members/{Group?}', [GroupController::class, 'members'])->name('groups.members');
    Route::POST('groups/members/addMember', [GroupController::class, 'addMember'])->name('groups.addMember');


    //Subgroups:
    Route::get('groups/{Group}/createSubgroup', [SubgroupController::class, 'create'])->name('subgroups.create');
    Route::post('subgroups/store', [SubgroupController::class, 'store'])->name('subgroups.store');
    Route::get('subgroups/edit/{Subgroup}', [SubgroupController::class, 'edit'])->name('subgroups.edit');
    Route::put('subgroups/update/{Subgroup}', [SubgroupController::class, 'update'])->name('subgroups.update');


    //Publications:
    Route::get('/publications', [PublicationController::class, 'index'])->name('publications.index');
    Route::get('publications/data', [PublicationController::class, 'data'])->name('publications.data');
    Route::get('publications/create', [PublicationController::class, 'create'])->name('publications.create');
    Route::post('publications/store', [PublicationController::class, 'store'])->name('publications.store');
    Route::get('publications/edit/{Publication}', [PublicationController::class, 'edit'])->name('publications.edit');
    Route::put('publications/update/{Publication}', [PublicationController::class, 'update'])->name('publications.update');


    //Files
    Route::post('file-upload/{location}', [FileUploadController::class, 'storeFile'])->name('file.upload');


    //Notes:
    Route::get('/notes', [NoteController::class, 'index'])->name('notes.index');
    Route::get('notes/data', [NoteController::class, 'data'])->name('notes.data');
    Route::get('notes/create', [NoteController::class, 'create'])->name('notes.create');
    Route::post('notes/store', [NoteController::class, 'store'])->name('notes.store');
    Route::get('notes/edit/{Note}', [NoteController::class, 'edit'])->name('notes.edit');
    Route::put('notes/update/{Note}', [NoteController::class, 'update'])->name('notes.update');


    //Categories:
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/data', [CategoryController::class, 'data'])->name('categories.data');
    Route::post('categories/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::post('categories/update/{Category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::post('categories/destroy/{Category}', [CategoryController::class, 'destroy'])->name('categories.destroy');


});


Auth::routes();


