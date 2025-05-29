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
Route::get('/customer/detail', function () {
    return view('Informasi.detailUser');
});
Route::get('/kasir', function () {
    return view('Informasi.kasir');
});
Route::get('/frame', function () {
    return view('Inventory.frame');
});
Route::get('/lensaFinish', function () {
    return view('Inventory.lensaFinish');
});
Route::get('/lensaKhusus', function () {
    return view('Inventory.lensaKhusus');
});
Route::get('/softlens', function () {
    return view('Inventory.softlens');
});
Route::get('/accesories', function () {
    return view('Inventory.accesories');
});
Route::get('/orderan', function () {
    return view('Informasi.orderan');
});
Route::get('/admin', function () {
    return view('admin');
});
Route::get('/report', function () {
    return view('Dashboard.report');
});
Route::get('/orderan/detail', function () {
    return view('Informasi.detailOrderan');
});
Route::get('/login', function () {
    return view('Auth.login');
});
Route::get('/register', function () {
    return view('Auth.register');
});
