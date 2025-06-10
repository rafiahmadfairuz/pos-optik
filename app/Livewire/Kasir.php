<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Resep;
use Livewire\Component;
use App\Models\OrderItems;
use Livewire\Attributes\On;
use App\Models\Orderan as TbOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Kasir extends Component
{
    public $customerData = [];
    public $cartData = [];
    public $orderData = [];
    public $resepData = [];
    public $total = [];

    #[On('customerSelected')]
    public function handleCustomerData($data)
    {
        $this->customerData = $data;
        logger('Customer Data Diterima:', $this->customerData);
    }

    #[On('cartUpdated')]
    public function handleCartData($cart)
    {
        $this->cartData = $cart;
        logger('Cart Data Diterima:', $this->cartData);
    }

    #[On('dataOrderOrder')]
    public function handleOrderData($data)
    {
        $this->orderData = $data;
        logger('Order Data Diterima:', $this->orderData);
    }

    #[On('dataOrderResep')]
    public function handleResepData($data)
    {
        $this->resepData = $data;
        logger('Resep Data Diterima:', $this->resepData);
    }

    #[On('totalUpdated')]
    public function handleTotal($total)
    {
        $this->total = $total;
        logger('Total Data Diterima:', $this->total);
    }

    #[On('initiateTransactionSave')]
    public function simpanTransaksi($dataFromTransactionDetail = [])
    {
        logger('simpanTransaksi jalan');

        if (!empty($dataFromTransactionDetail)) {
            $this->orderData = $dataFromTransactionDetail['order_data'] ?? $this->orderData;
            $this->resepData = $dataFromTransactionDetail['resep_data'] ?? $this->resepData;
        }

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

            logger('Data untuk Order: ', [
                'orderData' => $this->orderData,
                'resepData' => $this->resepData,
                'customerData' => $this->customerData,
                'cartData' => $this->cartData,
                'totalData' => $this->total,
            ]);

            $order = TbOrder::create([
                'order_date'        => $this->orderData['order_date'],
                'complete_date'     => $this->orderData['complete_date'],
                'total'             => $this->total,
                'payment_status'    => $this->orderData['payment_status'],
                'order_status'      => $this->orderData['order_status'],
                'payment_type'      => $this->orderData['payment_type'],
                'optometrist_name'  => $this->orderData['optometrist_id'],
                'customer_paying'   => $this->orderData['customer_paying'],
                'payment_method'    => $this->orderData['payment_method'],
                'asuransi_id'       => $this->orderData['asuransi_id'],
                'pelanggan_id'      => $this->customerData['id'],
            ]);

            logger('Order berhasil dibuat dengan ID: ' . $order->id);

            foreach ($this->cartData as $item) {
                OrderItems::create([
                    'order_id'      => $order->id,
                    'itemable_id'   => $item['id'],
                    'itemable_type' => $item['type'],
                    'quantity'      => $item['quantity'],
                    'price'         => $item['price'],
                    'subtotal'      => $item['price'] * $item['quantity'],
                ]);
            }

            logger('OrderItems berhasil ditambahkan');

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
                logger('Resep berhasil ditambahkan');
            } else {
                logger('Data resep kosong, tidak membuat entri Resep.');
            }

            DB::commit();

            $this->reset(['customerData', 'cartData', 'orderData', 'resepData', 'total']);

            $this->dispatch('transactionSavedAndReset');
            session()->flash('success', 'Transaksi berhasil disimpan.');
        } catch (ValidationException $e) {
            session()->flash('error', 'Validasi gagal: ' . $e->getMessage());
            logger()->error('Validasi simpanTransaksi gagal: ' . json_encode($e->errors()));
            $this->dispatch('notify', message: 'Validasi gagal: ' . $e->getMessage(), type: 'error');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
            logger()->error('Gagal simpanTransaksi: ' . $e->getMessage());
            $this->dispatch('notify', message: 'Gagal menyimpan transaksi: ' . $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        $users = User::where('cabang_id', session('cabang_id'))->get();
        return view('livewire.kasir', compact('users'));
    }
}
