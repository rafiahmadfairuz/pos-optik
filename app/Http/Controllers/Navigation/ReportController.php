<?php

namespace App\Http\Controllers\Navigation;

use Carbon\Carbon;
use App\Models\Frame;
use App\Models\LensaFinish;
use App\Models\LensaKhusus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function index()
    {
        $penjualanPerBulan = DB::table('orderans')
            ->selectRaw('MONTH(order_date) as bulan,
                 SUM(total) as total_penjualan,
                 COUNT(*) as jumlah_transaksi')
            ->whereYear('order_date', date('Y'))
            ->where('order_status', 'complete')
            ->groupBy(DB::raw('MONTH(order_date)'))
            ->orderBy('bulan')
            ->get();

        $frameTerlaris = DB::table('order_items')
            ->join('orderans', 'order_items.order_id', '=', 'orderans.id')
            ->join('frames', function ($join) {
                $join->on('order_items.itemable_id', '=', 'frames.id')
                    ->where('order_items.itemable_type', '=', 'frame');
            })
            ->select('frames.merk as name', DB::raw('SUM(order_items.quantity) as total_terjual'))
            ->where('orderans.order_status', 'complete')
            ->groupBy('frames.merk')
            ->orderByDesc('total_terjual')
            ->limit(6)
            ->get();




        $lensaFinish = DB::table('order_items')
            ->join('orderans', 'order_items.order_id', '=', 'orderans.id')
            ->join('lensa_finishes', function ($join) {
                $join->on('order_items.itemable_id', '=', 'lensa_finishes.id')
                    ->where('order_items.itemable_type', '=', 'lensa_finish');
            })
            ->select('lensa_finishes.merk as nama_lensa', DB::raw('SUM(order_items.quantity) as total_terjual'))
            ->where('orderans.order_status', 'complete')
            ->groupBy('lensa_finishes.merk');

        $lensaKhusus = DB::table('order_items')
            ->join('orderans', 'order_items.order_id', '=', 'orderans.id')
            ->join('lensa_khususes', function ($join) {
                $join->on('order_items.itemable_id', '=', 'lensa_khususes.id')
                    ->where('order_items.itemable_type', '=', 'lensa_khusus');
            })
            ->select('lensa_khususes.merk as nama_lensa', DB::raw('SUM(order_items.quantity) as total_terjual'))
            ->where('orderans.order_status', 'complete')
            ->groupBy('lensa_khususes.merk');

        // Gabungkan hasil dua query lalu wrap dalam subquery untuk bisa pakai orderBy + limit
        $lensaTerlaris = DB::table(DB::raw("({$lensaFinish->unionAll($lensaKhusus)->toSql()}) as lensa_terlaris"))
            ->mergeBindings($lensaFinish)
            ->select('nama_lensa', 'total_terjual')
            ->orderByDesc('total_terjual')
            ->limit(6)
            ->get();

        $penjualanHarian = DB::table('orderans')
            ->selectRaw('DATE(order_date) as tanggal, SUM(total) as total_penjualan')
            ->whereBetween('order_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->where('order_status', 'complete')
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
}
