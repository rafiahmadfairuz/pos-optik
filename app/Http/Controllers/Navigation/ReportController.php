<?php

namespace App\Http\Controllers\Navigation;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Exports\OrderansExport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Relations\Relation;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $dateFrom = $request->input('date_from', Carbon::now()->startOfYear()->toDateString());
        $dateTo = $request->input('date_to', Carbon::now()->endOfYear()->toDateString());
        $cabangId = session('cabang_id');

        // Query Utama Orderan (Hanya Complete dan BELUM DIRETUR)
        $queryOrderans = DB::table('orderans')
            ->where('order_status', 'complete')
            ->where('is_returned', 0) // FIX: Wajib abaikan retur
            ->where('cabang_id', $cabangId)
            ->whereBetween('order_date', [$dateFrom, $dateTo]);

        $penjualanPerBulan = (clone $queryOrderans)
            ->selectRaw('MONTH(order_date) as bulan, SUM(total) as total_penjualan, COUNT(*) as jumlah_transaksi')
            ->groupBy(DB::raw('MONTH(order_date)'))
            ->orderBy('bulan')
            ->get();

        // Ambil Morph Class String Dinamis
        $frameClass = Relation::getMorphedModel('frame') ?? \App\Models\Frame::class;
        $lensaFinishClass = Relation::getMorphedModel('lensa_finish') ?? \App\Models\LensaFinish::class;
        $lensaKhususClass = Relation::getMorphedModel('lensa_khusus') ?? \App\Models\LensaKhusus::class;

        // Frame terlaris
        $frameTerlaris = DB::table('order_items')
            ->join('orderans', 'order_items.order_id', '=', 'orderans.id')
            ->join('frames', 'order_items.itemable_id', '=', 'frames.id')
            ->whereIn('order_items.itemable_type', ['frame', $frameClass])
            ->where('orderans.order_status', 'complete')
            ->where('orderans.is_returned', 0) // FIX: Abaikan retur
            ->where('orderans.cabang_id', $cabangId)
            ->whereBetween('orderans.order_date', [$dateFrom, $dateTo])
            ->select('frames.merk as name', DB::raw('SUM(order_items.quantity) as total_terjual'))
            ->groupBy('frames.merk')
            ->orderByDesc('total_terjual')
            ->limit(6)
            ->get();

        // Lensa Terlaris (gabungan Finish & Khusus)
        $lensaFinish = DB::table('order_items')
            ->join('orderans', 'order_items.order_id', '=', 'orderans.id')
            ->join('lensa_finishes', 'order_items.itemable_id', '=', 'lensa_finishes.id')
            ->whereIn('order_items.itemable_type', ['lensa_finish', $lensaFinishClass])
            ->where('orderans.order_status', 'complete')
            ->where('orderans.is_returned', 0) // FIX: Abaikan retur
            ->where('orderans.cabang_id', $cabangId)
            ->whereBetween('orderans.order_date', [$dateFrom, $dateTo])
            ->select('lensa_finishes.merk as nama_lensa', DB::raw('SUM(order_items.quantity) as total_terjual'))
            ->groupBy('lensa_finishes.merk');

        $lensaKhusus = DB::table('order_items')
            ->join('orderans', 'order_items.order_id', '=', 'orderans.id')
            ->join('lensa_khususes', 'order_items.itemable_id', '=', 'lensa_khususes.id')
            ->whereIn('order_items.itemable_type', ['lensa_khusus', $lensaKhususClass])
            ->where('orderans.order_status', 'complete')
            ->where('orderans.is_returned', 0) // FIX: Abaikan retur
            ->where('orderans.cabang_id', $cabangId)
            ->whereBetween('orderans.order_date', [$dateFrom, $dateTo])
            ->select('lensa_khususes.merk as nama_lensa', DB::raw('SUM(order_items.quantity) as total_terjual'))
            ->groupBy('lensa_khususes.merk');

        // Combined Query Lensa dengan SUM Grouping
        $lensaTerlaris = DB::table(DB::raw("({$lensaFinish->unionAll($lensaKhusus)->toSql()}) as combined_lensa"))
            ->mergeBindings($lensaFinish)
            ->select('nama_lensa', DB::raw('SUM(total_terjual) as total_terjual'))
            ->groupBy('nama_lensa')
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
            'frameTerlaris'     => $frameTerlaris,
            'lensaTerlaris'     => $lensaTerlaris,
            'penjualanHarian'   => $penjualanHarian,
        ]);
    }

    public function exportExcel(Request $request)
    {
        $filters = [
            'date_from' => $request->input('date_from'),
            'date_to'   => $request->input('date_to'),
        ];

        return Excel::download(new OrderansExport($filters), 'Laporan Penjualan.xlsx');
    }
}
