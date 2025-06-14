<?php

namespace App\Http\Controllers\Navigation;

use Carbon\Carbon;
use App\Models\Frame;
use App\Models\LensaFinish;
use App\Models\LensaKhusus;
use Illuminate\Http\Request;
use App\Exports\OrderansExport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
  public function index(Request $request)
{
    $dateFrom = $request->input('date_from', Carbon::now()->startOfYear()->toDateString());
    $dateTo = $request->input('date_to', Carbon::now()->endOfYear()->toDateString());

    $queryOrderans = DB::table('orderans')
        ->where('order_status', 'complete')
        ->where('cabang_id', session('cabang_id'))
        ->whereBetween('order_date', [$dateFrom, $dateTo]);

    $penjualanPerBulan = (clone $queryOrderans)
        ->selectRaw('MONTH(order_date) as bulan, SUM(total) as total_penjualan, COUNT(*) as jumlah_transaksi')
        ->groupBy(DB::raw('MONTH(order_date)'))
        ->orderBy('bulan')
        ->get();

    // Frame terlaris
    $frameTerlaris = DB::table('order_items')
        ->join('orderans', 'order_items.order_id', '=', 'orderans.id')
        ->join('frames', function ($join) {
            $join->on('order_items.itemable_id', '=', 'frames.id')
                ->where('order_items.itemable_type', '=', 'frame');
        })
        ->select('frames.merk as name', DB::raw('SUM(order_items.quantity) as total_terjual'))
        ->where('orderans.order_status', 'complete')
        ->where('orderans.cabang_id', session('cabang_id'))
        ->whereBetween('orderans.order_date', [$dateFrom, $dateTo])
        ->groupBy('frames.merk')
        ->orderByDesc('total_terjual')
        ->limit(6)
        ->get();

    // Lensa Terlaris (gabungan Finish & Khusus)
    $lensaFinish = DB::table('order_items')
        ->join('orderans', 'order_items.order_id', '=', 'orderans.id')
        ->join('lensa_finishes', function ($join) {
            $join->on('order_items.itemable_id', '=', 'lensa_finishes.id')
                ->where('order_items.itemable_type', '=', 'lensa_finish');
        })
        ->select('lensa_finishes.merk as nama_lensa', DB::raw('SUM(order_items.quantity) as total_terjual'))
        ->where('orderans.order_status', 'complete')
        ->where('orderans.cabang_id', session('cabang_id'))
        ->whereBetween('orderans.order_date', [$dateFrom, $dateTo])
        ->groupBy('lensa_finishes.merk');

    $lensaKhusus = DB::table('order_items')
        ->join('orderans', 'order_items.order_id', '=', 'orderans.id')
        ->join('lensa_khususes', function ($join) {
            $join->on('order_items.itemable_id', '=', 'lensa_khususes.id')
                ->where('order_items.itemable_type', '=', 'lensa_khusus');
        })
        ->select('lensa_khususes.merk as nama_lensa', DB::raw('SUM(order_items.quantity) as total_terjual'))
        ->where('orderans.order_status', 'complete')
        ->where('orderans.cabang_id', session('cabang_id'))
        ->whereBetween('orderans.order_date', [$dateFrom, $dateTo])
        ->groupBy('lensa_khususes.merk');

    $lensaTerlaris = DB::table(DB::raw("({$lensaFinish->unionAll($lensaKhusus)->toSql()}) as lensa_terlaris"))
        ->mergeBindings($lensaFinish)
        ->select('nama_lensa', 'total_terjual')
        ->orderByDesc('total_terjual')
        ->limit(6)
        ->get();

    $penjualanHarian = (clone $queryOrderans)
        ->selectRaw('DATE(order_date) as tanggal, SUM(total) as total_penjualan')
        ->groupBy('tanggal')
        ->orderBy('tanggal')
        ->get();

    return view("Dashboard.report", [
        'penjualanPerBulan' => $penjualanPerBulan,
        'frameTerlaris' => $frameTerlaris,
        'lensaTerlaris' => $lensaTerlaris,
        'penjualanHarian' => $penjualanHarian,
    ]);
}


   public function exportExcel(Request $request)
{
    $filters = [
        'date_from' => $request->input('date_from'),
        'date_to' => $request->input('date_to'),
    ];

    return Excel::download(new OrderansExport($filters), 'Laporan Penjualan.xlsx');
}

}
