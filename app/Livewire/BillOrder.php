<?php

namespace App\Livewire;

use Livewire\Component;

class BillOrder extends Component
{
    public $cart = [];
    public $total = 0;
    public $asuransi = 0;
    public $diskon = 0;
    public $customer_paying = '';

    protected $listeners = [
        'cartUpdated' => 'updateCart',
        'asuransiDipilih' => 'updateAsuransi',
        'customerPayingUpdated' => 'updateCustomerPaying',
        'diskon' => 'handleDiskon'
    ];

    public function updateCart($cart)
    {
        $this->cart = $cart;
        $this->recalculateTotal();
        $this->dispatchTotalUpdated();
    }

    public function updateAsuransi($nominal)
    {
        $this->asuransi = $this->cleanNumber($nominal);
        $this->dispatchTotalUpdated();
    }

    public function updateCustomerPaying($nominal)
    {
        $this->customer_paying = (string) $nominal;
        $this->dispatchTotalUpdated();
    }

    public function handleDiskon($nominal)
    {
        $this->diskon = $this->cleanNumber($nominal);
        $this->dispatchTotalUpdated();
    }

    private function cleanNumber($value)
    {
        if (empty($value)) {
            return 0;
        }

        $stringVal = (string) $value;
        $clean = preg_replace('/[^\d]/', '', $stringVal);

        return is_numeric($clean) ? (float) $clean : 0;
    }

    public function recalculateTotal()
    {
        $this->total = (float) collect($this->cart)->sum(
            fn($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 1)
        );
    }

    public function getFinalTotalProperty()
    {
        return (float) max($this->total - $this->asuransi - $this->diskon, 0);
    }

    public function getFormattedCustomerPayingProperty()
    {
        return $this->cleanNumber($this->customer_paying);
    }

    public function getKurangBayarProperty()
    {
        return (float) max($this->finalTotal - $this->formattedCustomerPaying, 0);
    }

    public function getKembalianProperty()
    {
        return (float) max($this->formattedCustomerPaying - $this->finalTotal, 0);
    }

    protected function dispatchTotalUpdated()
    {
        $this->dispatch('totalUpdated', [
            'total' => $this->total,
            'diskon' => $this->diskon,
            'perlu_dibayar' => $this->finalTotal,
            'customer_paying' => $this->formattedCustomerPaying,
            'kurang_bayar' => $this->kurangBayar,
            'kembalian' => $this->kembalian,
        ]);
    }

    public function render()
    {
        $this->recalculateTotal();

        return view('livewire.bill-order', [
            'total' => $this->total,
            'asuransi' => $this->asuransi,
            'diskon' => $this->diskon,
            'finalTotal' => $this->finalTotal,
            'customer_paying' => $this->formattedCustomerPaying,
            'kurang_bayar' => $this->kurangBayar,
            'kembalian' => $this->kembalian,
        ]);
    }
}
