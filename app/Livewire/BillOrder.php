<?php

namespace App\Livewire;

use Livewire\Component;

class BillOrder extends Component
{
    public $cart = [];
    public $total = 0;
    public $asuransi = 0;
    public $customer_paying = '';

    protected $listeners = [
        'cartUpdated' => 'updateCart',
        'asuransiDipilih' => 'updateAsuransi',
        'customerPayingUpdated' => 'updateCustomerPaying',
    ];

    public function updateCart($cart)
    {
        $this->cart = $cart;
        $this->total = $this->getTotalProperty();

        logger(json_encode([
            'total' => $this->total,
            'perlu_dibayar' => $this->finalTotal,
            'kembalian' => $this->kembalian,
        ]));
        // Hitung ulang nilai
        $this->dispatch('totalUpdated', [
            'total' => $this->total,
            'perlu_dibayar' => $this->finalTotal,
            'kembalian' => $this->kembalian,
        ]);
    }

    public function updateAsuransi($nominal)
    {
        $this->asuransi = is_numeric($nominal) ? (int) $nominal : 0;

        logger(json_encode([
            'total' => $this->total,
            'perlu_dibayar' => $this->finalTotal,
            'kembalian' => $this->kembalian,
        ]));
        $this->dispatch('totalUpdated', [
            'total' => $this->total,
            'perlu_dibayar' => $this->finalTotal,
            'kembalian' => $this->kembalian,
        ]);
    }

    public function updateCustomerPaying($nominal)
    {
        $this->customer_paying = (string) $nominal;

        logger(json_encode([
            'total' => $this->total,
            'perlu_dibayar' => $this->finalTotal,
            'kembalian' => $this->kembalian,
        ]));
        $this->dispatch('totalUpdated', [
            'total' => $this->total,
            'perlu_dibayar' => $this->finalTotal,
            'kembalian' => $this->kembalian,
        ]);
    }


    public function getTotalProperty()
    {
        return collect($this->cart)->sum(function ($item) {
            return ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        });
    }

    public function getFinalTotalProperty()
    {
        $total = is_numeric($this->total) ? $this->total : 0;
        $asuransi = is_numeric($this->asuransi) ? $this->asuransi : 0;

        return max($total - $asuransi, 0);
    }

    public function getKembalianProperty()
    {
        // Membersihkan input (boleh pakai . atau , tergantung format)
        $bayar = str_replace(['.', ','], ['', '.'], $this->customer_paying);
        $bayar = is_numeric($bayar) ? floatval($bayar) : 0;

        $totalFinal = is_numeric($this->finalTotal) ? $this->finalTotal : 0;

        return max($bayar - $totalFinal, 0);
    }

    public function render()
    {
        return view('livewire.bill-order', [
            'total' => $this->total,
            'asuransi' => $this->asuransi,
            'finalTotal' => $this->finalTotal,
            'customer_paying' => $this->customer_paying,
            'kembalian' => $this->kembalian,
        ]);
    }
}
