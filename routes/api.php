<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CustomerSupportController;
use App\Http\Controllers\CustomerController;
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
    Route::post('/update-password', [AuthController::class, 'updatePassword']);

    Route::get('/customer-support/get-customer-support', [CustomerSupportController::class, 'getDataCso'])->name('get-customer-support');
    Route::get('/customer-support/send-chat/{id}', [CustomerSupportController::class, 'sendChatApi'])->name('send-chat-api');
    Route::post('/customer-support/assign-teknisi', [CustomerSupportController::class, 'assignteknisi'])->name('assign-teknisi');

    Route::get('/teknisi/get-customer-support', [CustomerSupportController::class, 'getDataTeknisi'])->name('get-customer-support-teknisi');
    Route::get('/teknisi/get-customer-support/{start}/{end}', [CustomerSupportController::class, 'getDataTeknisiByDate'])->name('get-customer-support-teknisi-by-date');
    Route::post('/teknisi/update-status-teknisi/{id}', [CustomerSupportController::class, 'updateStatusTeknisi'])->name('update-status-teknisi');
});
Route::get('tes', function () {
    // show time zone
    return date_default_timezone_get();
});

Route::get('/tracking/{id}', [CustomerSupportController::class, 'tracking'])->name('api.tracking');

Route::get('/customer-support', [CustomerSupportController::class, 'getData'])->name('customer-support-data');

Route::get('/hardware-data', [HardwareController::class, 'getData'])->name('hardware-data');

Route::get('/customer-data', [CustomerController::class, 'getData'])->name('customer-data');

Route::get('/get-teknisi', [CustomerSupportController::class, 'getTeknisi'])->name('get-teknisi');
