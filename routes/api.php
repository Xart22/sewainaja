<?php

use App\Http\Controllers\API\CustomerSupportController;
use App\Http\Controllers\HardwareController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::get('/customer-support', [CustomerSupportController::class, 'getData'])->name('customer-support-data');


Route::get('/hardware-data', [HardwareController::class, 'getData'])->name('hardware-data');
