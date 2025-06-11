<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Resep;
use Livewire\Component;
use App\Models\OrderItems;
use App\Models\Orderan as TbOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Relations\Relation;

class Kasir extends Component
{
    public $customerData = [];
    public $cartData = [];
    public $orderData = [];
    public $resepData = [];
    public $total = 0;

    protected $listeners = [
        'initiateTransactionSave' => 'simpanTransaksi',
    ];

    public function simpanTransaksi($data = [])
    {
        logger('simpanTransaksi jalan');

        $this->customerData = $data['customer_data'];
        $this->cartData = $data['cart_data'];
        $this->total = $data['total'];
        $this->orderData = $data['order_data'];
        $this->resepData = $data['resep_data'];

        logger('Data untuk Order: ', [
            'orderData' => $this->orderData,
            'resepData' => $this->resepData,
            'customerData' => $this->customerData,
            'cartData' => $this->cartData,
            'totalData' => $this->total,
        ]);

        try {
            if (empty($this->customerData) || !isset($this->customerData['id'])) {
                throw ValidationException::withMessages(['customerData' => 'Data pelanggan belum lengkap.']);
            }
            if (empty($this->cartData)) {
                throw ValidationException::withMessages(['cartData' => 'Keranjang belanja kosong.']);
            }
            if (empty($this->orderData) || !isset($this->orderData['order_date'])) {
                throw ValidationException::withMessages(['orderData' => 'Data order belum lengkap.']);
            }
            if (!is_numeric($this->total) || $this->total <= 0) {
                throw ValidationException::withMessages(['total' => 'Total transaksi tidak valid atau kosong.']);
            }

            DB::beginTransaction();


            $order = TbOrder::create([
                'user_id'      => $this->customerData['id'],
                'cabang_id'         => session("cabang_id"),
                'order_date'        => $this->orderData['order_date'],
                'complete_date'     => $this->orderData['complete_date'],
                'staff_id'          =>  $this->orderData['optometrist_id'],
                'payment_type'      => $this->orderData['payment_type'],
                'order_status'      => $this->orderData['order_status'],
                'payment_method'    => $this->orderData['payment_method'],
                'payment_status'    => $this->orderData['payment_status'],
                'customer_paying'   => $this->orderData['customer_paying'],
                'asuransi_id'       => $this->orderData['asuransi_id'],
                'total'             => $this->total,
            ]);


            foreach ($this->cartData as $item) {
                OrderItems::create([
                    'order_id'      => $order->id,
                    'itemable_id'   => $item['id'],
                    'itemable_type' => $item['type'],
                    'quantity'      => $item['quantity'],
                    'price'         => $item['price'],
                    'subtotal'      => $item['price'] * $item['quantity'],
                ]);

                $morphMap = Relation::morphMap();
                $modelClass = $morphMap[$item['type']] ?? null;

                if ($modelClass) {
                    $product = $modelClass::find($item['id']);

                    if ($product) {
                        $product->stok -= $item['quantity'];
                        $product->save();
                        Log::info('Stok dikurangi', [
                            'itemable_type' => $item['type'],
                            'itemable_id'   => $item['id'],
                            'nama_model'    => $modelClass,
                            'stok_sisa'     => $product->stok,
                            'jumlah_dikurang' => $item['quantity'],
                        ]);
                    } else {
                        Log::warning('Produk tidak ditemukan saat mau kurangi stok', [
                            'itemable_type' => $item['type'],
                            'itemable_id'   => $item['id'],
                        ]);
                    }
                } else {
                    Log::error('Model class tidak ditemukan di morphMap', [
                        'type' => $item['type']
                    ]);
                }
            }


            if (!empty($this->resepData) && array_filter($this->resepData)) {
                Resep::create([
                    'orderan_id'    => $order->id,
                    'right_sph_d'   => $this->resepData['right_sph_d'] ?? null,
                    'right_cyl_d'   => $this->resepData['right_cyl_d'] ?? null,
                    'right_axis_d'  => $this->resepData['right_axis_d'] ?? null,
                    'right_va_d'    => $this->resepData['right_va_d'] ?? null,
                    'left_sph_d'    => $this->resepData['left_sph_d'] ?? null,
                    'left_cyl_d'    => $this->resepData['left_cyl_d'] ?? null,
                    'left_axis_d'   => $this->resepData['left_axis_d'] ?? null,
                    'left_va_d'     => $this->resepData['left_va_d'] ?? null,
                    'add_right'     => $this->resepData['add_right'] ?? null,
                    'add_left'      => $this->resepData['add_left'] ?? null,
                    'pd_right'      => $this->resepData['pd_right'] ?? null,
                    'pd_left'       => $this->resepData['pd_left'] ?? null,
                    'notes'         => $this->resepData['notes'] ?? null,
                ]);
            }

            DB::commit();
            $this->reset(['customerData', 'cartData', 'orderData', 'resepData', 'total']);
            session()->flash('success', 'Transaksi berhasil disimpan.');
            logger('success Transaksi berhasil disimpan.');
        } catch (ValidationException $e) {
            logger()->error('Validasi simpanTransaksi gagal: ', $e->errors());
            session()->flash('error', 'Validasi gagal: ' . $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
            logger()->error('Gagal simpanTransaksi: ' . $e->getMessage());
        }
    }




    public function render()
    {
        $users = User::where('cabang_id', session('cabang_id'))->get();
        return view('livewire.kasir', compact('users'));
    }
}
