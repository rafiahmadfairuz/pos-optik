<?php

namespace App\Livewire;

use Livewire\Component;

class BillOrder extends Component
{
    public $cart = [];
    public $asuransi = 0;
    public $customerPaying = 0;

    protected $listeners = [
        'cartUpdated' => 'updateCart',
        'asuransiDipilih' => 'updateAsuransi',
        'customerPaying' => 'updateCustomerPaying',
    ];

    public function updateCart($cart)
    {
        $this->cart = $cart;

        $total = $this->getTotalProperty();
        $this->dispatch('totalUpdated', $total); // optional
    }

    public function updateAsuransi($nominal)
    {
        $this->asuransi = $nominal;
    }

    public function updateCustomerPaying($nominal)
    {
        $this->customerPaying = (string) $nominal;
    }


    public function getTotalProperty()
    {
        return collect($this->cart)->sum(function ($item) {
            return ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        });
    }

    public function getFinalTotalProperty()
    {
        return max($this->total - $this->asuransi, 0);
    }

    public function getKembalianProperty()
    {
        $cleanBayar = preg_replace('/[^\d]/', '', $this->customerPaying);
        $bayar = is_numeric($cleanBayar) ? (int) $cleanBayar : 0;
        $totalFinal = is_numeric($this->finalTotal) ? $this->finalTotal : 0;

        return max($bayar - $totalFinal, 0);
    }


    public function render()
    {
        return view('livewire.bill-order', [
            'total' => $this->total,
            'asuransi' => $this->asuransi,
            'finalTotal' => $this->finalTotal,
            'customerPaying' => $this->customerPaying,
            'kembalian' => $this->kembalian,
        ]);
    }
}
