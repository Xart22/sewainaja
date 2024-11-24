<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CustomerSupportController;
use App\Http\Controllers\HardwareController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/update-profile', [AuthController::class, 'updateProfile']);



    Route::post('/customer-support/assign-technician', [CustomerSupportController::class, 'assignTechnician'])->name('assign-technician');
    Route::get('/teknisi/get-customer-support', [CustomerSupportController::class, 'getDataTeknisi'])->name('get-customer-support-teknisi');
    Route::post('/teknisi/update-status-teknisi/{id}', [CustomerSupportController::class, 'updateStatusTeknisi'])->name('update-status-teknisi');
});



Route::get('/customer-support', [CustomerSupportController::class, 'getData'])->name('customer-support-data');

Route::get('/hardware-data', [HardwareController::class, 'getData'])->name('hardware-data');

Route::get('/get-teknisi', [CustomerSupportController::class, 'getTeknisi'])->name('get-teknisi');
