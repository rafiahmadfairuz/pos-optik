<?php

namespace App\Livewire;

use App\Models\Staff;
use Livewire\Component;
use App\Models\Asuransi;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class TransactionDetail extends Component
{
    public $resep_option = 'tanpa_resep';
    public $selected_resep_id = null;

    public $od_sph, $od_cyl, $od_axis, $od_add, $od_prisma, $od_base, $od_va;
    public $os_sph, $os_cyl, $os_axis, $os_add, $os_prisma, $os_base, $os_va;
    public $pdr, $pdl, $pv;
    public $frame_a, $frame_b, $frame_d, $frame_diag, $frame;
    public $tanggal_pemeriksaan, $notes;

    public $order_status, $order_date, $complete_date;
    public $payment_type, $optometrist_id, $diskon, $customer_paying;
    public $payment_method, $payment_status, $asuransi;

    // TAMBAHAN KECIL: Variabel penampung staff khusus resep
    public $resep_staff_id;

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
        'customerDataSent'         => 'handleCustomerData',
        'cartUpdated'              => 'handleCartData',
        'totalUpdated'             => 'handleTotal',
        'resepSelectedFromHistory' => 'handleResepHistory',
        'resepOptionUpdated'       => 'handleResepOption',
        'resepDataUpdated'         => 'handleResepDataUpdated',
    ];

    public function handleResepDataUpdated($data = [])
    {
        if (is_array($data)) {
            $this->od_sph               = $data['od_sph'] ?? $this->od_sph;
            $this->od_cyl               = $data['od_cyl'] ?? $this->od_cyl;
            $this->od_axis              = $data['od_axis'] ?? $this->od_axis;
            $this->od_add               = $data['od_add'] ?? $this->od_add;
            $this->od_prisma            = $data['od_prisma'] ?? $this->od_prisma;
            $this->od_base              = $data['od_base'] ?? $this->od_base;
            $this->od_va                = $data['od_va'] ?? $this->od_va;

            $this->os_sph               = $data['os_sph'] ?? $this->os_sph;
            $this->os_cyl               = $data['os_cyl'] ?? $this->os_cyl;
            $this->os_axis              = $data['os_axis'] ?? $this->os_axis;
            $this->os_add               = $data['os_add'] ?? $this->os_add;
            $this->os_prisma            = $data['os_prisma'] ?? $this->os_prisma;
            $this->os_base              = $data['os_base'] ?? $this->os_base;
            $this->os_va                = $data['os_va'] ?? $this->os_va;

            $this->pdr                  = $data['pdr'] ?? $this->pdr;
            $this->pdl                  = $data['pdl'] ?? $this->pdl;
            $this->pv                   = $data['pv'] ?? $this->pv;

            $this->frame_a              = $data['frame_a'] ?? $this->frame_a;
            $this->frame_b              = $data['frame_b'] ?? $this->frame_b;
            $this->frame_d              = $data['frame_d'] ?? $this->frame_d;
            $this->frame_diag           = $data['frame_diag'] ?? $this->frame_diag;
            $this->frame                = $data['frame'] ?? $this->frame;

            $this->tanggal_pemeriksaan = $data['tanggal_pemeriksaan'] ?? $this->tanggal_pemeriksaan;
            $this->notes               = $data['notes'] ?? $this->notes;

            // TAMBAHAN KECIL: Tangkap staff_id dari resep ke variabel khusus
            if (isset($data['staff_id'])) {
                $this->resep_staff_id = $data['staff_id'];
            }
        }
    }

    public function mount()
    {
        $this->order_date = date('Y-m-d');
        $this->tanggal_pemeriksaan = date('Y-m-d');
    }

    public function updatedAsuransi($value)
    {
        if ($this->payment_type === 'asuransi' && $value) {
            $asuransi = Asuransi::find($this->asuransi);
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

    public function handleResepOption($option)
    {
        $this->resep_option = $option;
    }

    public function handleResepHistory($resepId)
    {
        $this->selected_resep_id = $resepId;
        $this->resep_option = 'resep_lama';
    }

  public function rules()
    {
        $rules = [
            'resep_option' => ['required', Rule::in(['resep_baru', 'resep_lama', 'tanpa_resep'])],
        ];

        $hasCart = count($this->cartData) > 0;
        $hasResep = in_array($this->resep_option, ['resep_baru', 'resep_lama']);

        // 1. JIKA ADA ORDER BELANJA: Wajib isi Optometris Kasir/Transaksi ($this->optometrist_id)
        if ($hasCart) {
            $rules['optometrist_id'] = 'required|exists:staff,id';
        }

        // 2. JIKA ADA RESEP BARU: Wajib isi Staf Resep ($this->resep_staff_id)
        if ($this->resep_option === 'resep_baru') {
            $rules['resep_staff_id'] = 'required|exists:staff,id';
        }

        // 3. VALIDASI TRANSAKSI / ORDERAN (Hanya jika ada barang di keranjang)
        if ($hasCart) {
            $rules = array_merge($rules, [
                'order_status'    => ['required', Rule::in(['pending', 'complete'])],
                'order_date'      => 'required|date',
                'complete_date'   => 'nullable|date|after_or_equal:order_date',
                'payment_type'    => ['required', Rule::in(['pelunasan', 'asuransi'])],
                'customer_paying' => 'required|numeric|min:0',
                'payment_method'  => ['required', Rule::in(['cash', 'card'])],
                'payment_status'  => ['required', Rule::in(['paid', 'DP', 'unpaid'])],
                'asuransi'        => [
                    $this->payment_type === 'asuransi' ? 'required' : 'nullable',
                    'exists:asuransis,id'
                ],
            ]);
        }

        // 4. SISA VALIDASI RESEP BARU LAINNYA... (biarkan seperti semula)
        if ($this->resep_option === 'resep_baru') {
            $rules = array_merge($rules, [
                'od_sph' => 'nullable|numeric|between:-20,20',
                'od_cyl' => 'nullable|numeric|between:-6,6',
                'od_axis' => 'nullable|integer|between:0,180',
                'od_add' => 'nullable|numeric|between:0,4',
                'od_prisma' => 'nullable|numeric|between:0,10',
                'od_base' => ['nullable', Rule::in(['In', 'Out', 'Up', 'Down'])],
                'od_va' => 'nullable|string|max:50',

                'os_sph' => 'nullable|numeric|between:-20,20',
                'os_cyl' => 'nullable|numeric|between:-6,6',
                'os_axis' => 'nullable|integer|between:0,180',
                'os_add' => 'nullable|numeric|between:0,4',
                'os_prisma' => 'nullable|numeric|between:0,10',
                'os_base' => ['nullable', Rule::in(['In', 'Out', 'Up', 'Down'])],
                'os_va' => 'nullable|string|max:50',

                'pdr' => 'nullable|numeric|min:0',
                'pdl' => 'nullable|numeric|min:0',
                'pv' => 'nullable|numeric|min:0',

                'frame_a' => 'nullable|numeric|min:0',
                'frame_b' => 'nullable|numeric|min:0',
                'frame_d' => 'nullable|numeric|min:0',
                'frame_diag' => 'nullable|numeric|min:0',
                'frame' => ['nullable', Rule::in(['Full Metal', 'Full Plastik', 'Nylon', 'Rimless'])],

                'tanggal_pemeriksaan' => 'required|date',
                'notes' => 'nullable|string|max:2000',
            ]);
        }

        // 5. VALIDASI RESEP LAMA
        if ($this->resep_option === 'resep_lama') {
            $rules['selected_resep_id'] = 'required|exists:reseps,id';
        }

        return $rules;
    }
    public function messages()
    {
        return [
            'optometrist_id.required'    => 'Pemeriksa / Optometris wajib dipilih.',
            'selected_resep_id.required' => 'Pilih resep lama dari riwayat pelanggan.',
            'asuransi.required'          => 'Pilih jenis asuransi jika jenis pembayaran adalah Asuransi.',
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
            $this->order_status = 'pending';
        } else {
            $this->forcePendingStatus = false;
        }
    }

public function handleTotal($total)
{
    $this->total        = $total['total'] ?? 0;
    $this->diskon       = $total['diskon'] ?? 0;
    $this->kembalian    = $total['kembalian'] ?? 0;
    $this->perluDibayar = $total['perlu_dibayar'] ?? 0;
    $this->kurangBayar  = $total['kurang_bayar'] ?? 0;

    // FIX: Tangkap juga customer_paying dari komponen BillOrder
    if (isset($total['customer_paying']) && $total['customer_paying'] > 0) {
        $this->customer_paying = $total['customer_paying'];
    }
}

    public function submit()
    {
        $hasCart = count($this->cartData) > 0;
        $hasResep = in_array($this->resep_option, ['resep_baru', 'resep_lama']);

        // CEK VALIDASI UTAMA: Jangan sampai Keranjang KOSONG dan Tanpa Resep sekaligus
        if (!$hasCart && !$hasResep) {
            $this->addError('resep_option', 'Transaksi kosong! Masukkan barang ke keranjang atau buat/pilih resep kacamata.');
            return;
        }

        // Clean Numeric Inputs
        $cleanPaying = 0;
        if ($this->customer_paying) {
            $cleanPaying = floatval(preg_replace('/[^\d]/', '', (string) $this->customer_paying));
            $this->customer_paying = $cleanPaying;
        }

        $cleanDiskon = 0;
        if ($this->diskon) {
            $cleanDiskon = floatval(preg_replace('/[^\d]/', '', (string) $this->diskon));
            $this->diskon = $cleanDiskon;
        }

        $this->validate();

        // SUSUN PAYLOAD DATA SECARA PROPOSIONAL
        $transactionData = [
            'resep_option'      => $this->resep_option,
            'selected_resep_id' => $this->selected_resep_id,
            'customer_data'     => $this->customerData,
            'has_order'         => $hasCart,
            'has_resep'         => $hasResep,
        ];

        if ($hasCart) {
            $transactionData['order_data'] = [
                'order_status'    => $this->order_status,
                'order_date'      => $this->order_date,
                'complete_date'   => $this->complete_date,
                'payment_type'    => $this->payment_type,
                'optometrist_id'  => $this->optometrist_id,
                'customer_paying' => $cleanPaying,
                'diskon'          => $cleanDiskon,
                'kurang_bayar'    => $this->kurangBayar,
                'payment_method'  => $this->payment_method,
                'payment_status'  => $this->payment_status,
                'asuransi_id'     => $this->asuransi,
            ];
            $transactionData['cart_data'] = $this->cartData;
            $transactionData['total'] = $this->total;
            $transactionData['perluDibayar'] = $this->perluDibayar;
            $transactionData['kembalian'] = $this->kembalian;
        }

        if ($this->resep_option === 'resep_baru') {
            $transactionData['resep_data'] = [
                // UBAH DARI $this->optometrist_id JADI $this->resep_staff_id SUPAYA AMAN & TIDAK NYASAR
                'staff_id'            => $this->resep_staff_id,
                'od_sph'              => $this->od_sph,
                'od_cyl'              => $this->od_cyl,
                'od_axis'             => $this->od_axis,
                'od_add'              => $this->od_add,
                'od_prisma'           => $this->od_prisma,
                'od_base'             => $this->od_base,
                'od_va'               => $this->od_va,
                'os_sph'              => $this->os_sph,
                'os_cyl'              => $this->os_cyl,
                'os_axis'             => $this->os_axis,
                'os_add'              => $this->os_add,
                'os_prisma'           => $this->os_prisma,
                'os_base'             => $this->os_base,
                'os_va'               => $this->os_va,
                'pdr'                 => $this->pdr,
                'pdl'                 => $this->pdl,
                'pv'                  => $this->pv,
                'frame_a'             => $this->frame_a,
                'frame_b'             => $this->frame_b,
                'frame_d'             => $this->frame_d,
                'frame_diag'          => $this->frame_diag,
                'frame'               => $this->frame,
                'tanggal_pemeriksaan' => $this->tanggal_pemeriksaan,
                'notes'               => $this->notes,
            ];
        }

        $this->dispatch('initiateTransactionSave', $transactionData);
    }

    public function render()
    {
        $this->asuransiList = Asuransi::where('cabang_id', session('cabang_id'))->get();
        $this->optometristList = Staff::where('cabang_id', session('cabang_id'))->get();

        return view('livewire.transaction-detail');
    }
}
