<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\QuestionnaireController;
use App\Http\Controllers\SongController;
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
    Route::get('users/profile', [UserController::class, 'profile'])->name('users.profile');
    Route::put('users/profile/update/{User}', [UserController::class, 'profileUdate'])->name('users.profileUdate');
    Route::put('users/passwordUpdate', [UserController::class, 'passwordUdate'])->name('users.passwordUpdate');
    Route::post('users/destroy/{User}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('users/profile', [UserController::class, 'profile'])->name('users.profile');
    //Groups:
    Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
    Route::get('groups/data', [GroupController::class, 'data'])->name('groups.data');
    Route::get('groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('groups/store', [GroupController::class, 'store'])->name('groups.store');
    Route::get('groups/edit/{Group}', [GroupController::class, 'edit'])->name('groups.edit');
    Route::put('groups/update/{Group}', [GroupController::class, 'update'])->name('groups.update');
    Route::post('groups/destroy/{Group}', [GroupController::class, 'destroy'])->name('groups.destroy');

    //members of group:
    Route::get('groups/members/{Group?}', [GroupController::class, 'members'])->name('groups.members');
    Route::POST('groups/members/addMember', [GroupController::class, 'addMember'])->name('groups.addMember');

    //subgroups of group
    Route::get('groups/subgroups/{Group}', [GroupController::class, 'subgroups'])->name('groups.subgroups');

    //Subgroups:
    Route::get('groups/{Group}/createSubgroup', [SubgroupController::class, 'create'])->name('subgroups.create');
    Route::post('subgroups/store', [SubgroupController::class, 'store'])->name('subgroups.store');
    Route::get('subgroups/edit/{Subgroup}', [SubgroupController::class, 'edit'])->name('subgroups.edit');
    Route::put('subgroups/update/{Subgroup}', [SubgroupController::class, 'update'])->name('subgroups.update');
    Route::post('subgroups/destroy/{Subgroup}', [SubgroupController::class, 'destroy'])->name('subgroups.destroy');


    //Publications:
    Route::get('/publications', [PublicationController::class, 'index'])->name('publications.index');
    Route::get('publications/data/{active}', [PublicationController::class, 'data'])->name('publications.data');
    Route::get('publications/create', [PublicationController::class, 'create'])->name('publications.create');
    Route::post('publications/store', [PublicationController::class, 'store'])->name('publications.store');
    Route::get('publications/edit/{Publication}', [PublicationController::class, 'edit'])->name('publications.edit');
    Route::put('publications/update/{Publication}', [PublicationController::class, 'update'])->name('publications.update');
    Route::post('publications/archive/{Publication}', [PublicationController::class, 'archive'])->name('publications.archive');

    //Files
    Route::post('file-upload/{assignedTo}', [FileController::class, 'storeFile'])->name('file.upload');
    Route::get('file-download/{type}/{id}', [FileController::class, 'downloadFile'])->name('file.download');


    //Notes:
    Route::get('/notes', [NoteController::class, 'index'])->name('notes.index');
    Route::get('notes/data', [NoteController::class, 'data'])->name('notes.data');
    Route::get('notes/create', [NoteController::class, 'create'])->name('notes.create');
    Route::post('notes/store', [NoteController::class, 'store'])->name('notes.store');
    Route::get('notes/edit/{Note}', [NoteController::class, 'edit'])->name('notes.edit');
    Route::put('notes/update/{Note}', [NoteController::class, 'update'])->name('notes.update');
    Route::post('notes/destroy/{Note}', [NoteController::class, 'destroy'])->name('notes.destroy');

    //Player:
    Route::get('/songs', [SongController::class, 'index'])->name('songs.index');
    Route::get('songs/data', [SongController::class, 'data'])->name('songs.data');
    Route::get('songs/create', [SongController::class, 'create'])->name('songs.create');
    Route::post('songs/store', [SongController::class, 'store'])->name('songs.store');
    Route::get('songs/edit/{Song}', [SongController::class, 'edit'])->name('songs.edit');
    Route::put('songs/update/{Song}', [SongController::class, 'update'])->name('songs.update');

    Route::get('songs/play/{Song}', [SongController::class, 'play'])->name('songs.play');


    //Categories:
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/data', [CategoryController::class, 'data'])->name('categories.data');
    Route::post('categories/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::post('categories/update/{Category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::post('categories/destroy/{Category}', [CategoryController::class, 'destroy'])->name('categories.destroy');


    //Questionnaire
    Route::post('questionnaire/destroy/{Questionnaire?}', [QuestionnaireController::class, 'destroy'])->name('questionnaires.destroy');
    Route::post('questionnaire/vote/{Questionnaire?}', [QuestionnaireController::class, 'vote'])->name('questionnaires.vote');
    Route::get('questionnaire/results/{Questionnaire}', [QuestionnaireController::class, 'results'])->name('questionnaires.results');
    Route::post('questionnaire/resultsModal', [QuestionnaireController::class, 'resultsModal'])->name('questionnaires.resultsModal');
    Route::post('questionnaire/addOption', [QuestionnaireController::class, 'addOption'])->name('questionnaires.addOption');
    Route::post('questionnaire/deleteOption', [QuestionnaireController::class, 'deleteOption'])->name('questionnaires.deleteOption');
    Route::post('questionnaire/updateOption', [QuestionnaireController::class, 'updateOption'])->name('questionnaires.updateOption');


    //Events
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('events/data', [EventController::class, 'data'])->name('events.data');
    Route::get('events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('events/store', [EventController::class, 'store'])->name('events.store');
    Route::get('events/edit/{Event}', [EventController::class, 'edit'])->name('events.edit');
    Route::put('events/update/{Event}', [EventController::class, 'update'])->name('events.update');
    Route::post('events/destroy/{Event}', [EventController::class, 'destroy'])->name('events.destroy');


});


Auth::routes();


