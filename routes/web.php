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
Route::get('/IIB12/data', [App\Http\Controllers\IIB12Controller::class, 'getData']);
Route::get('/IIB12/create', [App\Http\Controllers\IIB12Controller::class, 'create']);
Route::get('/IIB12/show-generate-by-periode', [App\Http\Controllers\IIB12Controller::class, 'showGenerateByPeriode']);
Route::get('/IIB12/show-generate-approval-by-periode', [App\Http\Controllers\IIB12Controller::class, 'showGenerateApprovalByPeriode']);
Route::get('/IIB12/generate-by-periode/{periode?}', [App\Http\Controllers\IIB12Controller::class, 'generateByPeriode']);
Route::get('/IIB12/generate-approval-by-periode/{periode?}', [App\Http\Controllers\IIB12Controller::class, 'generateApprovalByPeriode']);
Route::delete('/IIB12/{id}', [App\Http\Controllers\IIB12Controller::class, 'destroy']);
Route::get('/IIB12/{id}', [App\Http\Controllers\IIB12Controller::class, 'show']);
Route::put('/IIB12/{id}', [App\Http\Controllers\IIB12Controller::class, 'update']);
Route::get('/IIB12/{id}/edit', [App\Http\Controllers\IIB12Controller::class, 'edit']);
Route::get('/IIB12/{id}/generate', [App\Http\Controllers\IIB12Controller::class, 'generate']);
Route::get('/IIB12/{id}/generate-approval', [App\Http\Controllers\IIB12Controller::class, 'generateApproval']);

Route::get('/IIB9', [App\Http\Controllers\IIB9Controller::class, 'index']);
Route::post('/IIB9', [App\Http\Controllers\IIB9Controller::class, 'store']);
Route::get('/IIB9/data', [App\Http\Controllers\IIB9Controller::class, 'getData']);
Route::get('/IIB9/create', [App\Http\Controllers\IIB9Controller::class, 'create']);
Route::get('/IIB9/show-generate-by-periode', [App\Http\Controllers\IIB9Controller::class, 'showGenerateByPeriode']);
Route::get('/IIB9/show-generate-approval-by-periode', [App\Http\Controllers\IIB9Controller::class, 'showGenerateApprovalByPeriode']);
Route::get('/IIB9/generate-by-periode/{periode?}', [App\Http\Controllers\IIB9Controller::class, 'generateByPeriode']);
Route::get('/IIB9/generate-approval-by-periode/{periode?}', [App\Http\Controllers\IIB9Controller::class, 'generateApprovalByPeriode']);
Route::delete('/IIB9/{id}', [App\Http\Controllers\IIB9Controller::class, 'destroy']);
Route::get('/IIB9/{id}', [App\Http\Controllers\IIB9Controller::class, 'show']);
Route::put('/IIB9/{id}', [App\Http\Controllers\IIB9Controller::class, 'update']);
Route::get('/IIB9/{id}/edit', [App\Http\Controllers\IIB9Controller::class, 'edit']);
Route::get('/IIB9/{id}/generate', [App\Http\Controllers\IIB9Controller::class, 'generate']);
Route::get('/IIB9/{id}/generate-approval', [App\Http\Controllers\IIB9Controller::class, 'generateApproval']);

Route::get('/IIB8', [App\Http\Controllers\IIB8Controller::class, 'index']);
Route::post('/IIB8', [App\Http\Controllers\IIB8Controller::class, 'store']);
Route::get('/IIB8/data', [App\Http\Controllers\IIB8Controller::class, 'getData']);
Route::get('/IIB8/create', [App\Http\Controllers\IIB8Controller::class, 'create']);
Route::get('/IIB8/show-generate-by-periode', [App\Http\Controllers\IIB8Controller::class, 'showGenerateByPeriode']);
Route::get('/IIB8/show-generate-approval-by-periode', [App\Http\Controllers\IIB8Controller::class, 'showGenerateApprovalByPeriode']);
Route::get('/IIB8/generate-by-periode/{periode?}', [App\Http\Controllers\IIB8Controller::class, 'generateByPeriode']);
Route::get('/IIB8/generate-approval-by-periode/{periode?}', [App\Http\Controllers\IIB8Controller::class, 'generateApprovalByPeriode']);
Route::delete('/IIB8/{id}', [App\Http\Controllers\IIB8Controller::class, 'destroy']);
Route::get('/IIB8/{id}', [App\Http\Controllers\IIB8Controller::class, 'show']);
Route::put('/IIB8/{id}', [App\Http\Controllers\IIB8Controller::class, 'update']);
Route::get('/IIB8/{id}/edit', [App\Http\Controllers\IIB8Controller::class, 'edit']);
Route::get('/IIB8/{id}/generate', [App\Http\Controllers\IIB8Controller::class, 'generate']);
Route::get('/IIB8/{id}/generate-approval', [App\Http\Controllers\IIB8Controller::class, 'generateApproval']);

Route::get('/IC39', [App\Http\Controllers\IC39Controller::class, 'index']);
Route::post('/IC39', [App\Http\Controllers\IC39Controller::class, 'store']);
Route::get('/IC39/data', [App\Http\Controllers\IC39Controller::class, 'getData']);
Route::get('/IC39/create', [App\Http\Controllers\IC39Controller::class, 'create']);
Route::get('/IC39/show-generate-by-periode', [App\Http\Controllers\IC39Controller::class, 'showGenerateByPeriode']);
Route::get('/IC39/generate-by-periode/{periode?}', [App\Http\Controllers\IC39Controller::class, 'generateByPeriode']);
Route::delete('/IC39/{id}', [App\Http\Controllers\IC39Controller::class, 'destroy']);
Route::get('/IC39/{id}', [App\Http\Controllers\IC39Controller::class, 'show']);
Route::put('/IC39/{id}', [App\Http\Controllers\IC39Controller::class, 'update']);
Route::get('/IC39/{id}/edit', [App\Http\Controllers\IC39Controller::class, 'edit']);
Route::get('/IC39/{id}/generate', [App\Http\Controllers\IC39Controller::class, 'generate']);

Route::get('/IIIC8', [App\Http\Controllers\IIIC8Controller::class, 'index']);
Route::post('/IIIC8', [App\Http\Controllers\IIIC8Controller::class, 'store']);
Route::get('/IIIC8/data', [App\Http\Controllers\IIIC8Controller::class, 'getData']);
Route::get('/IIIC8/create', [App\Http\Controllers\IIIC8Controller::class, 'create']);
Route::get('/IIIC8/show-generate-by-periode', [App\Http\Controllers\IIIC8Controller::class, 'showGenerateByPeriode']);
Route::get('/IIIC8/generate-by-periode/{periode?}', [App\Http\Controllers\IIIC8Controller::class, 'generateByPeriode']);
Route::delete('/IIIC8/{id}', [App\Http\Controllers\IIIC8Controller::class, 'destroy']);
Route::get('/IIIC8/{id}', [App\Http\Controllers\IIIC8Controller::class, 'show']);
Route::put('/IIIC8/{id}', [App\Http\Controllers\IIIC8Controller::class, 'update']);
Route::get('/IIIC8/{id}/edit', [App\Http\Controllers\IIIC8Controller::class, 'edit']);
Route::get('/IIIC8/{id}/generate', [App\Http\Controllers\IIIC8Controller::class, 'generate']);
