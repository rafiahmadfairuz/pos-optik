<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Resep;
use Livewire\Component;
use App\Models\OrderItems;
use App\Models\ProdukCabang;
use App\Models\Orderan as TbOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class Kasir extends Component
{
    public $customerData = [];
    public $cartData = [];
    public $orderData = [];
    public $resepData = [];
    public $resepOption = 'tanpa_resep';
    public $selectedResepId = null;
    public $total = 0;
    public $kembalian = 0;
    public $perluDibayar = 0;

    protected $listeners = [
        'initiateTransactionSave' => 'simpanTransaksi',
    ];

    public function simpanTransaksi($data = [])
    {
        $this->customerData = $data['customer_data'] ?? [];
        $this->cartData = $data['cart_data'] ?? [];
        $this->total = $data['total'] ?? 0;
        $this->kembalian = $data['kembalian'] ?? 0;
        $this->perluDibayar = $data['perluDibayar'] ?? 0;
        $this->orderData = $data['order_data'] ?? [];
        $this->resepData = $data['resep_data'] ?? [];
        $this->resepOption = $data['resep_option'] ?? 'tanpa_resep';
        $this->selectedResepId = $data['selected_resep_id'] ?? null;

        try {
            if (empty($this->customerData) || !isset($this->customerData['id'])) {
                throw ValidationException::withMessages(['customerData' => 'Data pelanggan belum dipilih.']);
            }

            $hasCart = !empty($this->cartData);
            $hasResepBaru = $this->resepOption === 'resep_baru';
            $hasResepLama = $this->resepOption === 'resep_lama';

            if (!$hasCart && !$hasResepBaru) {
                throw ValidationException::withMessages(['transaction' => 'Pilih minimal satu tindakan: Periksa/Resep Baru atau Pembelian Produk.']);
            }

            DB::beginTransaction();

            $resepIdToAttach = null;

            if ($hasResepBaru) {
                $resep = Resep::create([
                    'user_id' => $this->customerData['id'],
                    'staff_id' => $this->resepData['staff_id'] ?? null,
                    'od_sph' => $this->resepData['od_sph'] ?? null,
                    'od_cyl' => $this->resepData['od_cyl'] ?? null,
                    'od_axis' => $this->resepData['od_axis'] ?? null,
                    'od_add' => $this->resepData['od_add'] ?? null,
                    'od_prisma' => $this->resepData['od_prisma'] ?? null,
                    'od_base' => $this->resepData['od_base'] ?? null,
                    'od_va' => $this->resepData['od_va'] ?? null,
                    'os_sph' => $this->resepData['os_sph'] ?? null,
                    'os_cyl' => $this->resepData['os_cyl'] ?? null,
                    'os_axis' => $this->resepData['os_axis'] ?? null,
                    'os_add' => $this->resepData['os_add'] ?? null,
                    'os_prisma' => $this->resepData['os_prisma'] ?? null,
                    'os_base' => $this->resepData['os_base'] ?? null,
                    'os_va' => $this->resepData['os_va'] ?? null,
                    'pdr' => $this->resepData['pdr'] ?? null,
                    'pdl' => $this->resepData['pdl'] ?? null,
                    'pv' => $this->resepData['pv'] ?? null,
                    'frame_a' => $this->resepData['frame_a'] ?? null,
                    'frame_b' => $this->resepData['frame_b'] ?? null,
                    'frame_d' => $this->resepData['frame_d'] ?? null,
                    'frame_diag' => $this->resepData['frame_diag'] ?? null,
                    'frame' => $this->resepData['frame'] ?? null,
                    'tanggal_pemeriksaan' => $this->resepData['tanggal_pemeriksaan'] ?? date('Y-m-d'),
                    'notes' => $this->resepData['notes'] ?? null,
                ]);

                $resepIdToAttach = $resep->id;
            } elseif ($hasResepLama) {
                $resepIdToAttach = $this->selectedResepId;
            }

            if ($hasCart) {
                $totalLaba = 0;
                foreach ($this->cartData as $item) {
                    $itemLaba = $item['laba'] ?? 0;
                    $totalLaba += $itemLaba * $item['quantity'];
                }

                $order = TbOrder::create([
                    'user_id' => $this->customerData['id'],
                    'cabang_id' => session('cabang_id'),
                    'resep_id' => $resepIdToAttach,
                    'order_date' => $this->orderData['order_date'] ?? date('Y-m-d'),
                    'complete_date' => $this->orderData['complete_date'] ?? date('Y-m-d'),
                    'staff_id' => $this->orderData['optometrist_id'] ?? null,
                    'payment_type' => $this->orderData['payment_type'] ?? 'pelunasan',
                    'order_status' => $this->orderData['order_status'] ?? 'complete',
                    'payment_method' => $this->orderData['payment_method'] ?? 'cash',
                    'payment_status' => $this->orderData['payment_status'] ?? 'paid',
                   'customer_paying' => isset($this->orderData['customer_paying']) && $this->orderData['customer_paying'] !== ''
                        ? (float) $this->orderData['customer_paying']
                        : (float) $this->perluDibayar,
                    'asuransi_id' => $this->orderData['asuransi_id'] ?? null,
                    'diskon' => $this->orderData['diskon'] ?? 0,
                    'kurang_bayar' => $this->orderData['kurang_bayar'] ?? 0,
                    'total' => $this->total,
                    'perlu_dibayar' => $this->perluDibayar,
                    'kembalian' => $this->kembalian,
                    'laba_total' => $totalLaba,
                ]);

                $cabangAktif = session('cabang_id');
                $isOrderComplete = strtolower($order->order_status) === 'complete';

                foreach ($this->cartData as $item) {
                    OrderItems::create([
                        'order_id' => $order->id,
                        'itemable_id' => $item['id'],
                        'itemable_type' => $item['type'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['price'] * $item['quantity'],
                        'laba' => $item['laba'] ?? 0,
                    ]);

                    if ($isOrderComplete) {
                        $produkCabang = ProdukCabang::where('itemable_type', $item['type'])
                            ->where('itemable_id', $item['id'])
                            ->where('cabang_id', $cabangAktif)
                            ->lockForUpdate()
                            ->first();

                        if (!$produkCabang) {
                            throw new \Exception("Stok produk '{$item['name']}' tidak ditemukan pada cabang ini.");
                        }

                        if ($produkCabang->stok < $item['quantity']) {
                            throw new \Exception("Stok produk '{$item['name']}' tidak mencukupi. (Stok: {$produkCabang->stok}, Dibeli: {$item['quantity']})");
                        }

                        $produkCabang->decrement('stok', $item['quantity']);
                    }
                }
            }

            DB::commit();

            $this->reset(['customerData', 'cartData', 'orderData', 'resepData', 'total', 'resepOption', 'selectedResepId']);
            session()->flash('success', 'Transaksi berhasil disimpan.');

            return redirect()->route('dashboard');

        } catch (ValidationException $e) {
            DB::rollBack();
            session()->flash('error', 'Validasi gagal: ' . implode(', ', \Illuminate\Support\Arr::flatten($e->errors())));
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal simpanTransaksi: ' . $e->getMessage());
            session()->flash('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
            return redirect()->route('dashboard');
        }
    }

    public function render()
    {
        $users = User::where('cabang_id', session('cabang_id'))->get();
        return view('livewire.kasir', compact('users'));
    }
}
