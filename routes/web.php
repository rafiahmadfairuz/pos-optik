<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Company\AdminController;
use App\Http\Controllers\Company\StaffController;
use App\Http\Controllers\FrontDesk\AsuransiController;
use App\Http\Controllers\FrontDesk\CustomerController;
use App\Http\Controllers\Inventory\AccessoriesController;
use App\Http\Controllers\Inventory\FrameController;
use App\Http\Controllers\Inventory\LensaFinishController;
use App\Http\Controllers\Inventory\LensaKhususController;
use App\Http\Controllers\Inventory\SoftlensController;
use App\Http\Controllers\Navigation\DashboardController;
use App\Http\Controllers\Navigation\ReportController;
use App\Livewire\Kasir;

Route::get('/login', [AuthController::class, "showLogin"])->name('login');
Route::post('/login', [AuthController::class, "processLogin"])->name("login.process");

Route::middleware(['auth'])->group(function () {
    Route::middleware(['cabang'])->group(function () {
        Route::get('/', [DashboardController::class, "index"])->name("dashboard");
        Route::get('/report', [ReportController::class, "index"])->name("report");
        Route::resource("/customer", CustomerController::class)->except(['create', 'show', 'edit']);
        Route::resource("/asuransi", AsuransiController::class)->except(['create', 'show', 'edit']);
        Route::resource("/frame", FrameController::class)->except(['create', 'show', 'edit']);
        Route::resource("/lensaFinish", LensaFinishController::class)->except(['create', 'show', 'edit']);
        Route::resource("/lensaKhusus", LensaKhususController::class)->except(['create', 'show', 'edit']);
        Route::resource("/softlens", SoftlensController::class)->except(['create', 'show', 'edit']);
        Route::resource('/accessories', AccessoriesController::class)->except(['create', 'show', 'edit']);
        Route::get("/kasir", Kasir::class);


        Route::get('/customer/detail', function () {
            return view('Informasi.detailUser');
        })->name('customer.detail');

        Route::get('/orderan', function () {
            return view('Informasi.orderan');
        });


        Route::get('/orderan/detail', function () {
            return view('Informasi.detailOrderan');
        })->name('transaction.detail');
    });

    Route::resource('/staff', StaffController::class)->except(['create', 'show', 'edit'])->middleware("admin");
    Route::post('/logout', [AuthController::class, "logout"])->name('logout');
    Route::get("/pilihCabang", [AdminController::class, "pilihCabang"])->name("pilihCabang");
    Route::get('/pilihCabang/{id}', [AdminController::class, 'setCabang'])->name('setCabang');

    Route::get('/ditolak', function () {
        return view("ditolak");
    })->name('ditolak');
});


Route::fallback(function () {
    return response()->view('404', [], 404);
});
