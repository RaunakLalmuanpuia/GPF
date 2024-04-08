<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

use App\Http\Controllers\GpfController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/get_entry_info', [GpfController::class , 'get_entry_info']);
Route::get('/search_gpf', [GpfController::class , 'search_gpf']);
// Route::get('/create_gpf', [GpfController::class , 'create_gpf']);
Route::post('/save_gpf', [GPFController::class, 'save_gpf']);

Route::get('/signatory', [GpfController::class , 'signatory']);

Route::get('/show_gpf/{id}', [GpfController::class , 'show_gpf']);

Route::get('/edit_gpf/{id}', [GpfController::class , 'edit_gpf']);
Route::get('/delete_individual/{id}', [GpfController::class , 'delete_individual']);

Route::post('/update_gpf/{id}', [GpfController::class , 'update_gpf']);

Route::get('/delete_gpf/{id}', [GpfController::class , 'delete_gpf']);


Route::post('/save_signatory', [GPFController::class, 'save_signatory']);

Route::get('/delete_signatory/{id}', [GpfController::class , 'delete_signatory']);

Route::post('/save_approval_template/{id}', [GPFController::class, 'save_approval_template']);

Route::post('/check_existence', [GPFController::class, 'check_existence']);


Route::get('/text_templates/{id}', [GpfController::class , 'text_templates']);
