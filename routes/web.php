<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Company\StaffController;
use App\Http\Controllers\FrontDesk\AsuransiController;
use App\Http\Controllers\FrontDesk\CustomerController;
use App\Http\Controllers\Inventory\AccessoriesController;
use App\Http\Controllers\Inventory\LensaFinishController;
use App\Http\Controllers\Inventory\LensaKhususController;
use App\Http\Controllers\Inventory\SoftlensController;

Route::controller(AuthController::class)->group(function () {
    Route::get("/login", "showLogin")->name("login");
    Route::post("/login", "processLogin")->name("login.process");
});

Route::resource("/customer", CustomerController::class)->except(['create', 'show', 'edit']);
Route::resource("/asuransi", AsuransiController::class)->except(['create', 'show', 'edit']);

Route::resource("/frame", LensaFinishController::class)->except(['create', 'show', 'edit']);
Route::resource("/lensaFinish", LensaFinishController::class)->except(['create', 'show', 'edit']);
Route::resource("/lensaKhusus", LensaKhususController::class)->except(['create', 'show', 'edit']);
Route::resource("/softlens", SoftlensController::class)->except(['create', 'show', 'edit']);
Route::resource('/accessories', AccessoriesController::class)->except(['create', 'show', 'edit']);

Route::resource('/staff', StaffController::class)->except(['create', 'show', 'edit']);


Route::get('/', function () {
    return view('Dashboard.dashboard');
});

Route::get('/customer/detail', function () {
    return view('Informasi.detailUser');
})->name('customer.detail');

Route::get('/kasir', function () {
    return view('Informasi.kasir');
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
})->name('transaction.detail');
Route::get('/login', function () {
    return view('Auth.login');
});
