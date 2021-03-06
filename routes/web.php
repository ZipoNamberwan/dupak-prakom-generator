<?php

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

Route::get('/IIB12', [App\Http\Controllers\IIB12Controller::class, 'index']);
Route::post('/IIB12', [App\Http\Controllers\IIB12Controller::class, 'store']);
Route::get('/IIB12/create', [App\Http\Controllers\IIB12Controller::class, 'create']);
Route::delete('/IIB12/{id}', [App\Http\Controllers\IIB12Controller::class, 'destroy']);
Route::get('/IIB12/{id}', [App\Http\Controllers\IIB12Controller::class, 'show']);
Route::put('/IIB12/{id}', [App\Http\Controllers\IIB12Controller::class, 'update']);
Route::get('/IIB12/{id}/edit', [App\Http\Controllers\IIB12Controller::class, 'edit']);
