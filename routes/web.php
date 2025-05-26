<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Dashboard.dashboard');
});

Route::get('/asuransi', function () {
    return view('Informasi.asuransi');
});
Route::get('/customer', function () {
    return view('Informasi.customer');
});
Route::get('/kasir', function () {
    return view('Informasi.kasir');
});
