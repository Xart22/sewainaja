<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/customer', function () {
    return view('customer.index');
});


Route::get('dashboard', function () {
    return view('dashboard.index');
});
