<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerSupportController;
use App\Http\Controllers\CustomerSupportDataController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HardwareController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;





Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');

// All routes that require authentication
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/customer-support/send/{id}', [CustomerSupportController::class, 'sendChatWeb'])->name('send-chat');
    Route::post('/customer-support/assign-teknisi', [CustomerSupportController::class, 'assignteknisi'])->name('assign-teknisi-web');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});


//admin
Route::middleware([AdminMiddleware::class])->prefix('admin')->group(function () {
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
    Route::post('/master-data/hardware/import', [HardwareController::class, 'import'])->name('master-data.hardware.import');
    Route::post('/master-data/hardware/assign', [HardwareController::class, 'assign'])->name('master-data.hardware.assign');
    Route::get('/master-data/hardware/deassign/{id}', [HardwareController::class, 'destroyAssign'])->name('master-data.hardware.deassign');

    // master data customer
    Route::get('/master-data/customer', [CustomerController::class, 'index'])->name('master-data.customer.index');
    Route::get('/master-data/customer/create', [CustomerController::class, 'create'])->name('master-data.customer.create');
    Route::post('/master-data/customer', [CustomerController::class, 'store'])->name('master-data.customer.store');
    Route::get('/master-data/customer/{id}', [CustomerController::class, 'show'])->name('master-data.customer.show');
    Route::get('/master-data/customer/{id}/edit', [CustomerController::class, 'edit'])->name('master-data.customer.edit');
    Route::put('/master-data/customer/{id}', [CustomerController::class, 'update'])->name('master-data.customer.update');
    Route::delete('/master-data/customer/{id}', [CustomerController::class, 'destroy'])->name('master-data.customer.destroy');


    Route::get('/manage-user', [UserController::class, 'index'])->name('manage-user.index');
    Route::get('/manage-user/create', [UserController::class, 'create'])->name('manage-user.create');
    Route::post('/manage-user', [UserController::class, 'store'])->name('manage-user.store');
    Route::get('/manage-user/{id}', [UserController::class, 'show'])->name('manage-user.show');
    Route::get('/manage-user/{id}/edit', [UserController::class, 'edit'])->name('manage-user.edit');
    Route::put('/manage-user/{id}', [UserController::class, 'update'])->name('manage-user.update');
    Route::delete('/manage-user/{id}', [UserController::class, 'destroy'])->name('manage-user.destroy');
    Route::get('/data-permohonan/{start_date}/{end_date}', [CustomerSupportDataController::class, 'index'])->name('data-permohonan.index');
    Route::get('/data-permohonan-export/{start_date}/{end_date}', [CustomerSupportDataController::class, 'export'])->name('data-permohonan.export');
});

Route::get('/tracking', [CustomerSupportController::class, 'tracking'])->name('tracking');

Route::get('/tes', [CustomerSupportController::class, 'tes']);

Route::get('/customer-support/{hased}', [CustomerSupportController::class, 'customerOnline'])->name('customer-online');
Route::get('/customer-support', [CustomerSupportController::class, 'submission'])->name('customer-support.submission');
Route::get('/customer-support/close-ticket/{id}', [CustomerSupportController::class, 'closeTicketView'])->name('close-ticket');
Route::post('/customer-support/close-ticket', [CustomerSupportController::class, 'closeTicket'])->name('customer-support.close');
Route::get('/thank-you', [CustomerSupportController::class, 'closed'])->name('customer-support.closed');


Route::get('/404', function () {
    return view('not-found');
})->name('not-found');
Route::post('/customer-support', [CustomerSupportController::class, 'store'])->name('customer-support.store');
