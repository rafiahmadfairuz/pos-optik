<?php

namespace App\Livewire;

use App\Models\Staff;
use App\Models\Asuransi;
use Livewire\Component;
use Illuminate\Validation\Rule;

class TransactionDetail extends Component
{
    public $right_sph_d, $right_cyl_d, $right_axis_d, $right_va_d;
    public $left_sph_d, $left_cyl_d, $left_axis_d, $left_va_d;
    public $add_right, $add_left;
    public $pd_right, $pd_left, $notes;

    public $order_status, $order_date, $complete_date;
    public $payment_type, $optometrist_id, $customer_paying;
    public $payment_method, $payment_status, $asuransi;

    public $asuransiList = [];
    public $optometristList = [];

    public $customerData = [];
    public $cartData = [];
    public $total = 0;
    public $kembalian = 0;
    public $perluDibayar = 0;

    public $forcePendingStatus = false;


    protected $listeners = [
        'customerDataSent' => 'handleCustomerData',
        'cartUpdated' => 'handleCartData',
        'totalUpdated' => 'handleTotal',
    ];



    public function updatedAsuransi($value)
    {
        if ($this->payment_type === 'asuransi' && $value) {
            $asuransi = \App\Models\Asuransi::find($this->asuransi);
            $harga = $asuransi?->nominal ?? 0;
            $this->dispatch('asuransiDipilih', $harga);
        }
    }

    public function updatedCustomerPaying($value)
    {
        logger($value);
        $this->dispatch('customerPayingUpdated', $value);
    }



    public function rules()
    {
        return [
            'order_status' => ['required', Rule::in(['pending', 'complete'])],
            'order_date' => 'required|date',
            'complete_date' => 'nullable|date|after_or_equal:order_date',
            'payment_type' => ['required', Rule::in(['DP', 'pelunasan', 'asuransi'])],
            'optometrist_id' => 'required|exists:staff,id',
            'customer_paying' => 'required|numeric|min:0',
            'payment_method' => ['required', Rule::in(['cash', 'card'])],
            'payment_status' => ['required', Rule::in(['paid', 'unpaid'])],
            'asuransi' => 'nullable|exists:asuransis,id',

            'right_sph_d' => 'nullable|string|max:10',
            'right_cyl_d' => 'nullable|string|max:10',
            'right_axis_d' => 'nullable|string|max:10',
            'right_va_d' => 'nullable|string|max:10',
            'left_sph_d' => 'nullable|string|max:10',
            'left_cyl_d' => 'nullable|string|max:10',
            'left_axis_d' => 'nullable|string|max:10',
            'left_va_d' => 'nullable|string|max:10',
            'add_right' => 'nullable|string|max:10',
            'add_left' => 'nullable|string|max:10',
            'pd_right' => 'nullable|string|max:10',
            'pd_left' => 'nullable|string|max:10',
            'notes' => 'nullable|string|max:2000',
        ];
    }




    public function handleCustomerData($customer)
    {
        $this->customerData = $customer;
    }

    public function handleCartData($cart)
    {
        $this->cartData = $cart;

        $stokKurang = false;
        foreach ($cart as $item) {
            if (isset($item['quantity']) && isset($item['stock']) && $item['quantity'] > $item['stock']) {
                $stokKurang = true;
                break;
            }
        }

        if ($stokKurang) {
            $this->forcePendingStatus = true;
            $this->order_status = 'pending'; // paksa pending
        } else {
            $this->forcePendingStatus = false;
        }
    }

    public function handleTotal($total)
    {
        $this->total = $total['total'];
        $this->kembalian = $total['kembalian'];
        $this->perluDibayar = $total['perlu_dibayar'];
    }

    public function submit()
    {
        $cleanInput = str_replace('.', '', $this->customer_paying); // hilangkan pemisah ribuan
        $cleanInput = str_replace(',', '.', $cleanInput); // ubah desimal jadi titik
        $bayar = floatval($cleanInput); // ubah ke angka

        $this->customer_paying = $bayar; // overwrite dengan versi numeric

        $this->validate();



        $transactionData = [
            'order_data' => [
                'order_status' => $this->order_status,
                'order_date' => $this->order_date,
                'complete_date' => $this->complete_date,
                'payment_type' => $this->payment_type,
                'optometrist_id' => $this->optometrist_id,
                'customer_paying' => $bayar,
                'payment_method' => $this->payment_method,
                'payment_status' => $this->payment_status,
                'asuransi_id' => $this->asuransi,
            ],
            'resep_data' => [
                'right_sph_d' => $this->right_sph_d,
                'right_cyl_d' => $this->right_cyl_d,
                'right_axis_d' => $this->right_axis_d,
                'right_va_d' => $this->right_va_d,
                'left_sph_d' => $this->left_sph_d,
                'left_cyl_d' => $this->left_cyl_d,
                'left_axis_d' => $this->left_axis_d,
                'left_va_d' => $this->left_va_d,
                'add_right' => $this->add_right,
                'add_left' => $this->add_left,
                'pd_right' => $this->pd_right,
                'pd_left' => $this->pd_left,
                'notes' => $this->notes,
            ],
            'customer_data' => $this->customerData,
            'cart_data' => $this->cartData,
            'total' => $this->total,
            'kembalian' => $this->kembalian,
            'perluDibayar' => $this->perluDibayar,
        ];

        $this->dispatch('initiateTransactionSave',  $transactionData);
    }

    public function render()
    {
        $this->asuransiList = Asuransi::where('cabang_id', session('cabang_id'))->get();
        $this->optometristList = Staff::where('cabang_id', session('cabang_id'))->get();

        return view('livewire.transaction-detail');
    }
}
