<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Sets
Route::get('/sets', [\App\Http\Controllers\SetsController::class, 'sets'])->name('sets');
Route::post('/set/add', [\App\Http\Controllers\SetsController::class, 'add'])->name('set.add');
Route::post('/set/{set_id}/delete', [\App\Http\Controllers\SetsController::class, 'delete'])->name('set.delete');

// Translations
Route::get('/translations/{set_id}', [\App\Http\Controllers\TranslationsController::class, 'translations'])->name('translations');
Route::post('/translation/{set_id}/add', [\App\Http\Controllers\TranslationsController::class, 'add'])->name('translation.add');
Route::post('/translation/{set_id}/upload', [\App\Http\Controllers\TranslationsController::class, 'upload'])->name('translation.upload');
Route::post('/translation/{set_id}/delete', [\App\Http\Controllers\TranslationsController::class, 'delete'])->name('translation.delete');
Route::post('/translation/{set_id}/update', [\App\Http\Controllers\TranslationsController::class, 'update'])->name('translation.update');

