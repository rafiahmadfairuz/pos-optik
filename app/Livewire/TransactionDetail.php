<?php

namespace App\Livewire;

use App\Models\Staff;
use Livewire\Component;
use App\Models\Asuransi;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class TransactionDetail extends Component
{
    public $right_sph_d, $right_cyl_d, $right_axis_d, $right_va_d;
    public $left_sph_d, $left_cyl_d, $left_axis_d, $left_va_d;
    public $add_right, $add_left;
    public $pd_right, $pd_left, $notes;

    public $order_status, $order_date, $complete_date;
    public $payment_type, $optometrist_id, $diskon, $customer_paying;
    public $payment_method, $payment_status, $asuransi;

    public $asuransiList = [];
    public $optometristList = [];

    public $customerData = [];
    public $cartData = [];
    public $total = 0;
    public $kembalian = 0;
    public $perluDibayar = 0;
    public $kurangBayar = 0;

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
        $this->dispatch('customerPayingUpdated', $value);
        Log::info("Customer Membayar : " . $value);
    }

    public function updatedDiskon($value)
    {
        $this->dispatch('diskon', $value);
        Log::info("Dapat Diskon : " . $value);
    }



    public function rules()
    {
        return [
            'order_status' => ['required', Rule::in(['pending', 'complete'])],
            'order_date' => 'required|date',
            'complete_date' => 'required|date|after_or_equal:order_date',
            'payment_type' => ['required', Rule::in(['pelunasan', 'asuransi'])],
            'optometrist_id' => 'required|exists:staff,id',
            'customer_paying' => 'required|numeric|min:0',
            'payment_method' => ['required', Rule::in(['cash', 'card'])],
            'payment_status' => ['required', Rule::in(['paid', 'DP', 'unpaid'])],
            'asuransi' => 'nullable|exists:asuransis,id',

            'right_sph_d' => 'nullable|numeric|between:-20,20|regex:/^\-?\d+(\.\d{1,2})?$/',
            'right_cyl_d' => 'nullable|numeric|between:-6,6|regex:/^\-?\d+(\.\d{1,2})?$/',
            'right_axis_d' => 'nullable|integer|between:0,180',
            'right_va_d' => 'nullable|numeric',

            'left_sph_d' => 'nullable|numeric|between:-20,20|regex:/^\-?\d+(\.\d{1,2})?$/',
            'left_cyl_d' => 'nullable|numeric|between:-6,6|regex:/^\-?\d+(\.\d{1,2})?$/',
            'left_axis_d' => 'nullable|integer|between:0,180',
            'left_va_d' => 'nullable|numeric',

            'add_right' => 'nullable|numeric|between:0.75,3.5|regex:/^\d+(\.\d{1,2})?$/',
            'add_left' => 'nullable|numeric|between:0.75,3.5|regex:/^\d+(\.\d{1,2})?$/',

            'pd_right' => 'nullable|numeric|between:25,40',
            'pd_left' => 'nullable|numeric|between:25,40',

            'notes' => 'nullable|max:2000',
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
        $this->total = $total['total'] ?? 0;
        $this->diskon = $total['diskon'] ?? 0;
        $this->kembalian = $total['kembalian'] ?? 0;
        $this->perluDibayar = $total['perlu_dibayar'] ?? 0;
        $this->kurangBayar = $total['kurang_bayar'] ?? 0;

        logger('==================== HANDLE TOTAL ====================');
        logger('TOTAL          : Rp ' . number_format($this->total, 0, ',', '.'));
        logger('DISKON         : Rp ' . number_format($this->diskon, 0, ',', '.'));
        logger('KEMBALIAN      : Rp ' . number_format($this->kembalian, 0, ',', '.'));
        logger('PERLU DIBAYAR  : Rp ' . number_format($this->perluDibayar, 0, ',', '.'));
        logger('KURANG BAYAR   : Rp ' . number_format($this->kurangBayar, 0, ',', '.'));
        logger('======================================================');
    }



    public function submit()
    {
        $cleanInput = str_replace('.', '', $this->customer_paying);
        $cleanInput = str_replace(',', '.', $cleanInput);
        $bayar = floatval($cleanInput);

        $this->customer_paying = $bayar;

        $this->validate();

        $transactionData = [
            'order_data' => [
                'order_status' => $this->order_status,
                'order_date' => $this->order_date,
                'complete_date' => $this->complete_date,
                'payment_type' => $this->payment_type,
                'optometrist_id' => $this->optometrist_id,
                'customer_paying' => $bayar,
                'diskon' => $this->diskon,
                'kurang_bayar' => $this->kurangBayar,
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
            'perluDibayar' => $this->perluDibayar,
            'kembalian' => $this->kembalian,
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
