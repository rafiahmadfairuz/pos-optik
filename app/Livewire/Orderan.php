<?php

namespace App\Livewire;

use App\Models\Resep;
use Livewire\Component;
use App\Models\OrderItems;
use Livewire\Attributes\On;
use App\Models\Orderan as TbOrder;
use Illuminate\Support\Facades\DB;

class Orderan extends Component
{
    public $customerData = [];
    public $cartData = [];
    public $orderData = [];
    public $resepData = [];
    public $total = 0;

    protected $listeners = [
        'customerDataSent' => 'handleCustomerData',
        'cartUpdated' => 'handleCartData',
        'dataOrderOrder' => 'handleOrderData',
        'dataOrderResep' => 'handleResepData',
        'totalUpdated' => 'handleTotal',
    ];

    public function handleCustomerData($data)
    {
        $this->customerData = $data;
    }

    public function handleCartData($cart)
    {
        $this->cartData = $cart;
    }

    #[On('dataOrderOrder')]
    public function handleOrderData($data)
    {
        $this->orderData = $data;
    }


    #[On('dataOrderResep')]
    public function handleResepData($data)
    {
        $this->resepData = $data;
    }

    public function handleTotal($total)
    {
        $this->total = $total;
    }

    public function simpanTransaksi()
    {
        logger('simpanTransaksi jalan');

        DB::beginTransaction();

        try {
            logger('orderData:', $this->orderData);
            logger('resepData:', $this->resepData);
            logger('customerData:', $this->customerData);
            logger('cartData:', $this->cartData);

            $order = TbOrder::create([
                'order_date'       => $this->orderData['order_date'],
                'complete_date'    => $this->orderData['complete_date'],
                'total'            => $this->total,
                'payment_status'   => $this->orderData['payment_status'],
                'order_status'     => $this->orderData['order_status'],
                'payment_type'     => $this->orderData['payment_type'],
                'optometrist_name' => $this->orderData['optometrist_id'],
                'customer_paying'  => $this->orderData['customer_paying'],
                'payment_method'   => $this->orderData['payment_method'],
                'asuransi_id'      => $this->orderData['asuransi_id'],
                'pelanggan_id'     => $this->customerData['id'],
            ]);

            logger('Order berhasil dibuat');

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

            if (!empty($this->resepData)) {
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

            logger('Resep berhasil ditambahkan');

            DB::commit();

            $this->reset(['customerData', 'cartData', 'orderData', 'resepData', 'total']);

            return back()->with('success', 'Transaksi berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Gagal simpanTransaksi: ' . $e->getMessage());
            return back()->with('error', 'Gagal menyimpan transaksi. ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.orderan');
    }
}
