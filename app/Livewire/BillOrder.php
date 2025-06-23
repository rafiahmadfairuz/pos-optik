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
        $this->total = $this->getTotalProperty();

        $this->logUpdate('updateCart');

        $this->dispatchTotalUpdated();
    }

    public function updateAsuransi($nominal)
    {
        $this->asuransi = is_numeric($nominal) ? (int) $nominal : 0;

        $this->logUpdate('updateAsuransi');

        $this->dispatchTotalUpdated();
    }

    public function updateCustomerPaying($nominal)
    {
        $this->customer_paying = (string) $nominal;

        $this->logUpdate('updateCustomerPaying');

        $this->dispatchTotalUpdated();
    }

    public function handleDiskon($nominal)
    {
        $nominal = str_replace('.', '', $nominal);
        $this->diskon = is_numeric($nominal) ? (float) $nominal : 0;

        $this->logUpdate('handleDiskon');

        $this->dispatchTotalUpdated();
    }
    public function getTotalProperty()
    {
        return round(collect($this->cart)->sum(
            fn($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 1)
        ), 2);
    }


    public function getFinalTotalProperty()
    {
        return round(max(
            (is_numeric($this->total) ? $this->total : 0)
                - (is_numeric($this->asuransi) ? $this->asuransi : 0)
                - (is_numeric($this->diskon) ? $this->diskon : 0),
            0
        ), 2);
    }

    public function getFormattedCustomerPayingProperty()
    {
        $bayar = str_replace(['.', ','], ['', '.'], $this->customer_paying);
        return round(is_numeric($bayar) ? floatval($bayar) : 0, 2);
    }

    public function getKurangBayarProperty()
    {
        return round(max($this->finalTotal - $this->formattedCustomerPaying, 0), 2);
    }

    public function getKembalianProperty()
    {
        return round(max($this->formattedCustomerPaying - $this->finalTotal, 0), 2);
    }

    protected function logUpdate($event)
    {
        logger(json_encode([
            'event' => $event,
            'total' => $this->total,
            'diskon' => $this->diskon,
            'asuransi' => $this->asuransi,
            'customer_paying' => $this->formattedCustomerPaying,
            'perlu_dibayar' => $this->finalTotal,
            'kurang_bayar' => $this->kurangBayar,
            'kembalian' => $this->kembalian,
        ]));
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
        logger('==================== RENDER AWAL ====================');
        logger('TOTAL (awal)                 : Rp ' . number_format($this->total, 0, ',', '.'));
        logger('ASURANSI (awal)              : Rp ' . number_format($this->asuransi, 0, ',', '.'));
        logger('DISKON (awal)                : Rp ' . number_format($this->diskon, 0, ',', '.'));
        logger('FINAL TOTAL (awal)           : Rp ' . number_format($this->finalTotal, 0, ',', '.'));
        logger('CUSTOMER PAYING (awal, raw)  : ' . $this->customer_paying);
        logger('KURANG BAYAR (awal)          : Rp ' . number_format($this->kurangBayar, 0, ',', '.'));
        logger('KEMBALIAN (awal)             : Rp ' . number_format($this->kembalian, 0, ',', '.'));

        $viewData = [
            'total' => $this->total,
            'asuransi' => $this->asuransi,
            'diskon' => $this->diskon,
            'finalTotal' => $this->finalTotal,
            'customer_paying' => $this->customer_paying,
            'kurang_bayar' => $this->kurangBayar,
            'kembalian' => $this->kembalian,
        ];

        logger('-------------------- VIEW DATA ----------------------');
        logger('TOTAL (akhir)                : Rp ' . number_format($viewData['total'], 0, ',', '.'));
        logger('ASURANSI (akhir)             : Rp ' . number_format($viewData['asuransi'], 0, ',', '.'));
        logger('DISKON (akhir)               : Rp ' . number_format($viewData['diskon'], 0, ',', '.'));
        logger('FINAL TOTAL (akhir)          : Rp ' . number_format($viewData['finalTotal'], 0, ',', '.'));
        logger('CUSTOMER PAYING (akhir, raw) : ' . $viewData['customer_paying']);
        logger('KURANG BAYAR (akhir)         : Rp ' . number_format($viewData['kurang_bayar'], 0, ',', '.'));
        logger('KEMBALIAN (akhir)            : Rp ' . number_format($viewData['kembalian'], 0, ',', '.'));
        logger('======================================================');

        return view('livewire.bill-order', $viewData);
    }
}
