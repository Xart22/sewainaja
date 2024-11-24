<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerSupportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HardwareController;
use Illuminate\Support\Facades\Route;





Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');


Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');



    // master data hardware
    Route::get('/master-data/hardware', [HardwareController::class, 'index'])->name('master-data.hardware.index');
    Route::get('/master-data/hardware/create', [HardwareController::class, 'create'])->name('master-data.hardware.create');
    Route::post('/master-data/hardware', [HardwareController::class, 'store'])->name('master-data.hardware.store');
    Route::get('/master-data/hardware/{id}', [HardwareController::class, 'show'])->name('master-data.hardware.show');
    Route::get('/master-data/hardware/{id}/edit', [HardwareController::class, 'edit'])->name('master-data.hardware.edit');
    Route::put('/master-data/hardware/{id}', [HardwareController::class, 'update'])->name('master-data.hardware.update');
    Route::post('/master-data/hardware-copy', [HardwareController::class, 'copyHardware'])->name('master-data.hardware.copy');
    Route::delete('/master-data/hardware/{id}', [HardwareController::class, 'destroy'])->name('master-data.hardware.destroy');
    Route::get('/master-data/hardware/{id}/qr-code', [HardwareController::class, 'qrCode'])->name('master-data.hardware.qr-code');

    // master data customer
    Route::get('/master-data/customer', [CustomerController::class, 'index'])->name('master-data.customer.index');
    Route::get('/master-data/customer/create', [CustomerController::class, 'create'])->name('master-data.customer.create');
    Route::post('/master-data/customer', [CustomerController::class, 'store'])->name('master-data.customer.store');
    Route::get('/master-data/customer/{id}', [CustomerController::class, 'show'])->name('master-data.customer.show');
    Route::get('/master-data/customer/{id}/edit', [CustomerController::class, 'edit'])->name('master-data.customer.edit');
    Route::put('/master-data/customer/{id}', [CustomerController::class, 'update'])->name('master-data.customer.update');
    Route::delete('/master-data/customer/{id}', [CustomerController::class, 'destroy'])->name('master-data.customer.destroy');

    Route::get('/customer-support/send/{id}', [CustomerSupportController::class, 'sendChatWeb'])->name('send-chat');
    Route::post('/customer-support/assign-teknisi', [CustomerSupportController::class, 'assignTechnician'])->name('assign-technician-web');



    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/tes', [CustomerSupportController::class, 'tes']);

Route::get('/customer-support/{hased}', [CustomerSupportController::class, 'customerOnline'])->name('customer-online');


Route::get('/404', function () {
    return view('not-found');
})->name('not-found');
Route::post('/customer-support', [CustomerSupportController::class, 'store'])->name('customer-support.store');
