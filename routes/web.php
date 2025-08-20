<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Company\AdminController;
use App\Http\Controllers\Company\StaffController;
use App\Http\Controllers\FrontDesk\AsuransiController;
use App\Http\Controllers\FrontDesk\CustomerController;
use App\Http\Controllers\FrontDesk\KasirController;
use App\Http\Controllers\FrontDesk\OrderanController;
use App\Http\Controllers\GudangUtamaController;
use App\Http\Controllers\Inventory\AccessoriesController;
use App\Http\Controllers\Inventory\FrameController;
use App\Http\Controllers\Inventory\LensaFinishController;
use App\Http\Controllers\Inventory\LensaKhususController;
use App\Http\Controllers\Inventory\SoftlensController;
use App\Http\Controllers\Navigation\DashboardController;
use App\Http\Controllers\Navigation\ReportController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\SupplierController;
use App\Livewire\Kasir;
use App\Models\Orderan;

Route::get('/login', [AuthController::class, "showLogin"])->name('login');
Route::post('/login', [AuthController::class, "processLogin"])->name("login.process");

Route::middleware(['auth'])->group(function () {
    Route::middleware(['cabang'])->group(function () {
        Route::get('/', [DashboardController::class, "index"])->name("dashboard");
        Route::get('/report', [ReportController::class, "index"])->name("report");
        Route::resource("/customer", CustomerController::class)->except(['create', 'edit']);
        Route::resource("/asuransi", AsuransiController::class)->except(['create', 'show', 'edit']);

        Route::get("/kasir", [KasirController::class, "index"]);
        Route::get('/orderan', [OrderanController::class, "index"])->name("orderan.index");
        Route::get('/orderan/{id}', [OrderanController::class, "orderanDetail"])->name('orderan.detail');
        Route::put('/orderan/{id}', [OrderanController::class, "updateOrderan"])->name('orderan.update');
    });

    Route::resource("/frame", FrameController::class)->except(['create', 'show', 'edit']);
    Route::resource("/lensaFinish", LensaFinishController::class)->except(['create', 'show', 'edit']);
    Route::resource("/lensaKhusus", LensaKhususController::class)->except(['create', 'show', 'edit']);
    Route::resource("/softlens", SoftlensController::class)->except(['create', 'show', 'edit']);
    Route::resource('/accessories', AccessoriesController::class)->except(['create', 'show', 'edit']);
    Route::resource("/supplier", SupplierController::class)->except(['create', 'edit']);
    Route::get('/pembelian', [PembelianController::class, 'index'])->name('list.pembelian');
    Route::get('/pembelian/{id}', [PembelianController::class, 'detail'])->name('detail.pembelian');
    Route::patch('/pembelian/{id}/retur', [\App\Http\Controllers\PembelianController::class, 'retur'])->name('pembelian.retur');

    Route::get('/transferBarang', [GudangUtamaController::class, 'transferBarangKeCabang'])->name('transfer.barang');
    Route::get('/listTransferBarang', [GudangUtamaController::class, 'listTransferBarangKeCabang'])->name('list.transfer.barang');
    Route::get('/listTransferBarang/{id}', [GudangUtamaController::class, 'detailListTransferBarangKeCabang'])->name('detail.transfer.barang');
    Route::patch('/listTransferBarang/{id}/retur', [GudangUtamaController::class, 'retur'])->name('transfer.retur');
    Route::patch('/listTransferBarang/{id}/transferKeCabangLain', [GudangUtamaController::class, 'transferKeCabangLain'])->name('transfer.ke.cabang');
    Route::get('/beliBarang', [GudangUtamaController::class, 'beliBarang'])->name('beli.barang');

    Route::resource('/staff', StaffController::class)->except(['create', 'show', 'edit'])->middleware("admin");
    Route::post('/logout', [AuthController::class, "logout"])->name('logout');

    Route::get("/pilihCabang", [AdminController::class, "pilihCabang"])->name("pilihCabang");
    Route::get('/pilihCabang/{id}', [AdminController::class, 'setCabang'])->name('setCabang');

    Route::get('/report', [ReportController::class, 'index'])->name('report.index');
    Route::get('/report/export', [ReportController::class, 'exportExcel'])->name('report.export');

    Route::get('/cetak-nota/{id}', [OrderanController::class, 'cetakNota'])->name('cetak.nota');

    Route::get('/orderan/{id}/retur', [OrderanController::class, 'returOrderan'])->name('retur.orderan');


    Route::get('/ditolak', function () {
        return view("ditolak");
    })->name('ditolak');
});


Route::fallback(function () {
    return response()->view('404', [], 404);
});
