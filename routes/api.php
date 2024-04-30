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
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndividualController;
use App\Http\Controllers\ReportController;

// Route::middleware('auth:sanctum')->get('/user/{$id}', function (Request $request) {
//     return dd($request->user());
// });

// Authentication Endpoints
Route::post('send_otp', [AuthController::class, 'send_otp']);
Route::post('verify_otp_and_search', [AuthController::class, 'verifyOtpAndSearch']);
Route::post('register', [AuthController::class, 'register']);


Route::post('/login', [AuthController::class, 'login']);

// Route::post('/login', [AuthController::class, 'check']);

Route::middleware('auth:sanctum')->get('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user']);
Route::middleware('auth:sanctum')->post('/update', [AuthController::class, 'update']);


Route::middleware(['auth:sanctum'])->group(function () {
    
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
    
    
    Route::get('/approval_templates/{id}', [GpfController::class , 'approval_templates']);


    Route::get('/individuals', [IndividualController::class , 'index']);
    Route::get('/search_individuals', [IndividualController::class , 'search_individual']);

    Route::get('/department', [GpfController::class , 'department']);

    Route::post('/filter-reports', [ReportController::class, 'getReport']);
    Route::post('/download-reports', [ReportController::class, 'downloadReport']);
});

