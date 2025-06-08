<?php

namespace App\Livewire;

use Livewire\Component;

class BillOrder extends Component
{
    public $cart = [];

    protected $listeners = ['cartUpdated' => 'updateCart'];

    public function updateCart($cart)
    {
        $this->cart = $cart;

        $total = $this->getTotalProperty();
        $this->dispatch('totalUpdated', $total);
    }

    public function getTotalProperty()
    {
        return collect($this->cart)->sum(function ($item) {
            return ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        });
    }

    public function render()
    {
        return view('livewire.bill-order', [
            'total' => $this->total,
        ]);
    }
}
