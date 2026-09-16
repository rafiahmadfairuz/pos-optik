<?php

namespace App\Http\Controllers\Navigation;

use Carbon\Carbon;
use App\Models\Orderan;
use App\Models\ProdukCabang;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Relations\Relation;

class DashboardController extends Controller
{
    public function index()
    {
        $cabangId = session('cabang_id');

        // Optimasi Eager Loading MorphTo untuk menghindari N+1 Query
        $leastStockProducts = ProdukCabang::with('itemable')
            ->where('cabang_id', $cabangId)
            ->orderBy('stok', 'asc')
            ->limit(20)
            ->get()
            ->map(function ($item) {
                $model = $item->itemable;
                return (object) [
                    'id'          => $item->id,
                    'nama_produk' => $model->merk ?? $model->nama ?? 'Produk Tidak Dikenal',
                    'stok'        => $item->stok,
                    'kategori'    => $model ? class_basename($model) : 'Umum',
                ];
            });

        $pendingOrders = Orderan::with('user')
            ->where('cabang_id', $cabangId)
            ->where('order_status', 'pending')
            ->orderBy('order_date', 'asc')
            ->limit(10)
            ->get();

        // Chart Penjualan (Abaikan Transaksi Retur)
        $chartData = Orderan::selectRaw("DATE(order_date) as tanggal, SUM(total) as total_penjualan")
            ->where('cabang_id', $cabangId)
            ->where('order_status', 'complete')
            ->where('is_returned', 0) // FIX: Abaikan transaksi yang diretur
            ->whereBetween('order_date', [now()->subDays(14)->toDateString(), now()->toDateString()])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => Carbon::parse($item->tanggal)->translatedFormat('d M'),
                    'total' => (int) $item->total_penjualan
                ];
            });

        // Produk Terlaris (Abaikan Transaksi Retur)
        $produkTerlaris = DB::table('order_items')
            ->join('orderans', 'order_items.order_id', '=', 'orderans.id')
            ->where('orderans.order_status', 'complete')
            ->where('orderans.is_returned', 0) // FIX: Abaikan transaksi yang diretur
            ->where('orderans.cabang_id', $cabangId)
            ->select('order_items.itemable_id', 'order_items.itemable_type', DB::raw('SUM(order_items.quantity) as total_terjual'))
            ->groupBy('order_items.itemable_id', 'order_items.itemable_type')
            ->orderByDesc('total_terjual')
            ->limit(6)
            ->get();

        $produkLabels = [];
        $produkValues = [];

        foreach ($produkTerlaris as $item) {
            $modelClass = Relation::getMorphedModel($item->itemable_type) ?? $item->itemable_type;
            $model = class_exists($modelClass) ? $modelClass::find($item->itemable_id) : null;

            if ($model) {
                $nama = $model->merk ?? $model->nama ?? 'Produk Tidak Dikenal';
                $produkLabels[] = $nama;
                $produkValues[] = (int) $item->total_terjual;
            }
        }

        return view('Dashboard.dashboard', compact(
            'leastStockProducts',
            'pendingOrders',
            'chartData',
            'produkLabels',
            'produkValues'
        ));
    }
}
