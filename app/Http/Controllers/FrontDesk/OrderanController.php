<?php

namespace App\Http\Controllers\FrontDesk;

use App\Models\Staff;
use App\Models\Cabang;
use App\Models\Orderan;
use App\Models\Asuransi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class OrderanController extends Controller
{
    public function index()
    {
        $cabangId = session('cabang_id');

        $orderans = Orderan::where('cabang_id', $cabangId)
            ->with('user') // jika ingin ambil data customer
            ->orderByDesc('created_at')
            ->get();

        return view('Informasi.orderan', compact('orderans'));
    }

    public function orderanDetail($id)
    {
        $optometristList = Staff::where("cabang_id", session('cabang_id'))->get();
        $asuransiList = Asuransi::where("cabang_id", session('cabang_id'))->get();
        $order = Orderan::with([
            'items.itemable',
            'resep',
            'user'
        ])->findOrFail($id);

        return view('Informasi.detailOrderan', compact('order', 'optometristList', 'asuransiList'));
    }

    public function updateOrderan(Request $request, $id)
    {
        $order = Orderan::find($id);
        $isUserAdmin = (Auth::user()->role ?? '') == 'admin';
        $originalOrderStatus = $order->order_status;
        $requestedOrderStatus = $request->input('order_status');

        if ($originalOrderStatus == 'complete' && !$isUserAdmin) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengubah order yang sudah selesai.');
        }

        $customerPayingCleaned = (float) str_replace(['.', ','], '', $request->input('customer_paying'));
        $diskonCleaned = (float) str_replace(['.', ','], '', $request->input('diskon')); // ✅ Tambahan
        $request->merge([
            'customer_paying_cleaned' => $customerPayingCleaned,
            'diskon_cleaned' => $diskonCleaned, // ✅ Tambahan
        ]);

        $validatedData = $request->validate([
            'resep_right_sph_d' => 'nullable|numeric|between:-20,20|regex:/^\-?\d+(\.\d{1,2})?$/',
            'resep_right_cyl_d' => 'nullable|numeric|between:-6,6|regex:/^\-?\d+(\.\d{1,2})?$/',
            'resep_right_axis_d' => 'nullable|numeric|regex:/^\d{1,3}$/|between:0,180',
            'resep_right_va_d' => 'nullable|numeric',
            'resep_left_sph_d' => 'nullable|numeric|between:-20,20|regex:/^\-?\d+(\.\d{1,2})?$/',
            'resep_left_cyl_d' => 'nullable|numeric|between:-6,6|regex:/^\-?\d+(\.\d{1,2})?$/',
            'resep_left_axis_d' => 'nullable|numeric|regex:/^\d{1,3}$/|between:0,180',
            'resep_left_va_d' => 'nullable|numeric',
            'resep_add_right' => 'nullable|numeric|between:0.75,3.5|regex:/^\d+(\.\d{1,2})?$/',
            'resep_add_left' => 'nullable|numeric|between:0.75,3.5|regex:/^\d+(\.\d{1,2})?$/',
            'resep_pd_right' => 'nullable|numeric|between:25,40|regex:/^\d+(\.\d{1,2})?$/',
            'resep_pd_left' => 'nullable|numeric|between:25,40|regex:/^\d+(\.\d{1,2})?$/',
            'tanggal_pemeriksaan' => 'nullable',
            'resep_notes' => 'nullable|string',
            'order_date' => 'required|date',
            'complete_date' => 'required|date',
            'staff_id' => 'required|exists:staff,id',
            'payment_type' => ['required', Rule::in(['pelunasan', 'asuransi'])],
            'asuransi_id' => [
                'nullable',
                Rule::requiredIf($request->input('payment_type') == 'asuransi'),
                'exists:asuransis,id',
            ],
            'order_status' => ['required', Rule::in(['pending', 'complete'])],
            'payment_method' => ['required', Rule::in(['cash', 'card'])],
            'payment_status' => ['required', Rule::in(['DP', 'unpaid', 'paid'])],
            'customer_paying_cleaned' => 'required|numeric|min:0',
            'diskon_cleaned' => 'nullable|numeric|min:0', // ✅ Tambahan

        ]);

        DB::beginTransaction();
        try {
            $calculatedTotal = $order->items->sum(function ($item) {
                return $item->quantity * $item->price;
            });

            $asuransiNominal = 0;
            if ($validatedData['payment_type'] == 'asuransi' && isset($validatedData['asuransi_id'])) {
                $asuransi = Asuransi::find($validatedData['asuransi_id']);
                if ($asuransi) {
                    $asuransiNominal = $asuransi->nominal;
                }
            }

            $diskon = $validatedData['diskon_cleaned'] ?? 0; // ✅ Tambahan
            $perluDibayar = $calculatedTotal - $asuransiNominal - $diskon; // ✅ Update formula

            // ✅ Tambahan akumulasi pembayaran
            $customerPayingAkhir = $order->customer_paying + $validatedData['customer_paying_cleaned'];
            $kurangBayar = max($perluDibayar - $customerPayingAkhir, 0); // ✅ Perbaikan
            $kembalian = max($customerPayingAkhir - $perluDibayar, 0); // ✅ Perbaikan

            if ($requestedOrderStatus == 'complete' && $originalOrderStatus != 'complete') {
                foreach ($order->items as $item) {
                    $product = $item->itemable;

                    if (!$product) {
                        throw new \Exception("Produk terkait dengan item order (ID: {$item->id}) tidak ditemukan.");
                    }

                    if ($product->stok < $item->quantity) {
                        throw new \Exception("Stok {$product->merk} ({$item->quantity} diminta, {$product->stok} tersedia) tidak mencukupi untuk item order ID {$item->id}.");
                    }

                    $product->stok -= $item->quantity;
                    $product->save();
                }

                $order->complete_date = now();
                if ($order->payment_status == 'unpaid') {
                    $order->payment_status = 'paid';
                }
            }

            $order->update([
                'order_date' => $validatedData['order_date'],
                'complete_date' => $order->complete_date,
                'staff_id' => $validatedData['staff_id'],
                'payment_type' => $validatedData['payment_type'],
                'asuransi_id' => $validatedData['asuransi_id'],
                'order_status' => $validatedData['order_status'],
                'payment_method' => $validatedData['payment_method'],
                'payment_status' => $validatedData['payment_status'],
                'customer_paying' => $customerPayingAkhir, // ✅ Akumulasi
                'diskon' => $diskon, // ✅ Tambahan
                'kurang_bayar' => $kurangBayar, // ✅ Tambahan
                'total' => $calculatedTotal,
                'perlu_dibayar' => $perluDibayar,
                'kembalian' => $kembalian,
            ]);

            $resepData = [
                'right_sph_d' => $validatedData['resep_right_sph_d'],
                'right_cyl_d' => $validatedData['resep_right_cyl_d'],
                'right_axis_d' => $validatedData['resep_right_axis_d'],
                'right_va_d' => $validatedData['resep_right_va_d'],
                'left_sph_d' => $validatedData['resep_left_sph_d'],
                'left_cyl_d' => $validatedData['resep_left_cyl_d'],
                'left_axis_d' => $validatedData['resep_left_axis_d'],
                'left_va_d' => $validatedData['resep_left_va_d'],
                'add_right' => $validatedData['resep_add_right'],
                'add_left' => $validatedData['resep_add_left'],
                'pd_right' => $validatedData['resep_pd_right'],
                'pd_left' => $validatedData['resep_pd_left'],
                'tanggal_pemeriksaan' => $validatedData['tanggal_pemeriksaan'],
                'notes' => $validatedData['resep_notes'],

            ];

            if ($order->resep) {
                $order->resep->update($resepData);
            } else {
                $order->resep()->create($resepData);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Order berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui order: ' . $e->getMessage());
        }
    }


    public function cetakNota($id)
    {
        $order = Orderan::with('asuransi')->findOrFail($id);
        $cabang = Cabang::findOrFail(session('cabang_id'));

        $pdf = Pdf::loadView('nota', compact('order', 'cabang'))
            ->setPaper([0, 0, 226.77, 600], 'portrait');

        return $pdf->stream('nota-' . $order->id . '.pdf');
    }

    public function returOrderan($id)
    {
        $order = Orderan::with(['items.itemable'])->findOrFail($id);

        // 1) Validasi cabang aktif vs order
        $currentCabangId = session('cabang_id');
        if (!$currentCabangId) {
            return back()->with('error', 'Cabang aktif tidak ditemukan di sesi.');
        }

        if ((int)$order->cabang_id !== (int)$currentCabangId) {
            return back()->with('error', 'Order ini bukan milik cabang yang sedang aktif.');
        }

        // 2) Hanya boleh retur kalau pending
        if ($order->order_status !== 'complete') {
            return back()->with('warning', 'Hanya order status pending yang bisa diretur.');
        }

        // 3) Anti double-retur (jika kamu tambah kolom, lihat Bagian 4)
        if (Schema::hasColumn('orderans', 'is_returned') && $order->is_returned) {
            return back()->with('warning', 'Order ini sudah diretur sebelumnya.');
        }

        try {
            DB::transaction(function () use ($order, $currentCabangId) {
                foreach ($order->items as $item) {
                    $modelClass = $item->itemable;
                    $model = new $modelClass;

                    // pastikan tabel & kolom bisa diinspeksi
                    $table = $model->getTable();
                    $columns = Schema::getColumnListing($table);

                    // Field pembanding yang sama seperti di kode transfermu
                    $matchableFields = ['sku', 'merk', 'tipe', 'warna', 'desain', 'nama', 'jenis', 'sph', 'cyl', 'add'];

                    // Bangun query produk di CABANG AKTIF
                    $queryCabang = $modelClass::query();
                    foreach ($matchableFields as $field) {
                        if (in_array($field, $columns) && isset($item->itemable->$field)) {
                            $queryCabang->where($field, $item->itemable->$field);
                        }
                    }
                    $queryCabang->where('cabang_id', $currentCabangId);

                    $produkCabang = $queryCabang->first();

                    if (!$produkCabang) {
                        throw new \Exception("Produk {$modelClass} untuk item #{$item->id} tidak ditemukan di cabang aktif.");
                    }
                    if (!in_array('stok', $columns)) {
                        throw new \Exception("Kolom 'stok' tidak ada pada tabel {$table}.");
                    }

                    $qty = (int)$item->quantity;
                    $stokSebelum = (int)$produkCabang->stok;

                    // Karena retur order → stok cabang dikembalikan (ditambah)
                    $produkCabang->increment('stok', $qty);

                    Log::info("=== RETUR ORDER #{$order->id} - {$modelClass} item #{$item->id} ===");
                    Log::info("Cabang aktif: {$currentCabangId}");
                    Log::info("Stok sebelum: {$stokSebelum}");
                    Log::info("Stok ditambah: {$qty}");
                    Log::info("Stok sesudah: {$produkCabang->stok}");
                }

                // Tandai agar tidak bisa diretur dua kali (jika kolom tersedia)
                if (Schema::hasColumn('orderans', 'is_returned')) {
                    $order->update([
                        'is_returned' => true,
                        'returned_at' => now(),
                    ]);
                }

                // OPTIONAL: kalau retur = batal, kamu bisa set payment_status dsb.
                // $order->update(['payment_status' => 'unpaid']);
            });

            return back()->with('success', 'Barang dari order ini berhasil diretur ke stok cabang aktif.');
        } catch (\Exception $e) {
            return back()->with('error', 'Retur gagal: ' . $e->getMessage());
        }
    }
}
