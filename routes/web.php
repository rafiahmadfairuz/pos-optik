<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
use Illuminate\Container\Attributes\Auth;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AccessoriesController;
use App\Http\Controllers\AsuransiController;
use App\Http\Controllers\FrameController;
use App\Http\Controllers\LensaFinishController;
use App\Http\Controllers\LensaKhususController;
use App\Http\Controllers\SoftlensController;

Route::controller(AuthController::class)->group(function () {
    Route::get("/login", "showLogin")->name("login");
    Route::post("/login", "processLogin")->name("login.process");
});

Route::get("/staff", [StaffController::class, "showStaff"])->name("staff");
Route::post("/staff", [StaffController::class, "createStaff"])->name("create.staff");
Route::put("/staff/{id}", [StaffController::class, "editStaff"])->name("edit.staff");
Route::delete("/staff/{id}", [StaffController::class, "deleteStaff"])->name("delete.staff");

Route::get("/accesories", [AccessoriesController::class, "showAccessories"])->name("accessories");
Route::post("/accesories", [AccessoriesController::class, "createAccessories"])->name("create.accessories");
Route::put("/accesories/{id}", [AccessoriesController::class, "editAccessories"])->name("edit.accessories");
Route::delete("/accesories/{id}", [AccessoriesController::class, "deleteAccessories"])->name("delete.accessories");

Route::get("/softlens", [SoftlensController::class, "showSoftlens"])->name("softlens");
Route::post("/softlens", [SoftlensController::class, "createSoftlens"])->name("create.softlens");
Route::put("/softlens/{id}", [SoftlensController::class, "editSoftlens"])->name("edit.softlens");
Route::delete("/softlens/{id}", [SoftlensController::class, "deleteSoftlens"])->name("delete.softlens");

Route::get("/frame", [FrameController::class, "showFrame"])->name("frame");
Route::post("/frame", [FrameController::class, "createFrame"])->name("create.frame");
Route::put("/frame/{id}", [FrameController::class, "editFrame"])->name("edit.frame");
Route::delete("/frame/{id}", [FrameController::class, "deleteFrame"])->name("delete.frame");

Route::get("/lensaFinish", [LensaFinishController::class, "showLensaFinish"])->name("lensaFinish");
Route::post("/lensaFinish", [LensaFinishController::class, "createLensaFinish"])->name("create.lensaFinish");
Route::put("/lensaFinish/{id}", [LensaFinishController::class, "editLensaFinish"])->name("edit.lensaFinish");
Route::delete("/lensaFinish/{id}", [LensaFinishController::class, "deleteLensaFinish"])->name("delete.lensaFinish");

Route::get("/lensaKhusus", [LensaKhususController::class, "showLensaKhusus"])->name("lensaKhusus");
Route::post("/lensaKhusus", [LensaKhususController::class, "createLensaKhusus"])->name("create.lensaKhusus");
Route::put("/lensaKhusus/{id}", [LensaKhususController::class, "editLensaKhusus"])->name("edit.lensaKhusus");
Route::delete("/lensaKhusus/{id}", [LensaKhususController::class, "deleteLensaKhusus"])->name("delete.lensaKhusus");

Route::get("/asuransi", [AsuransiController::class, "showAsuransi"])->name("asuransi");
Route::post("/asuransi", [AsuransiController::class, "createAsuransi"])->name("create.asuransi");
Route::put("/asuransi/{id}", [AsuransiController::class, "editAsuransi"])->name("edit.asuransi");
Route::delete("/asuransi/{id}", [AsuransiController::class, "deleteAsuransi"])->name("delete.asuransi");



Route::get('/', function () {
    return view('Dashboard.dashboard');
});

Route::get('/customer', function () {
    return view('Informasi.customer');
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

