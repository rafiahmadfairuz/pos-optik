<?php

namespace App\Livewire;

use Livewire\Component;

class SelectedProduct extends Component
{
    public $cart = [];

    protected $listeners = ['productSelected' => 'addToCart'];

    public function addToCart($product)
    {
        $exists = false;

        foreach ($this->cart as &$item) {
            if ($item['id'] === $product['id'] && $item['type'] === $product['type']) {
                $item['quantity']++;
                $exists = true;
                break;
            }
        }

        if (!$exists) {
            $product['quantity'] = 1;
            $this->cart[] = $product;
        }

        $this->dispatch('cartUpdated', $this->cart);
    }

    public function decreaseQuantity($index)
    {
        if (!isset($this->cart[$index])) return;

        if ($this->cart[$index]['quantity'] > 1) {
            $this->cart[$index]['quantity']--;
        } else {
            unset($this->cart[$index]);
            $this->cart = array_values($this->cart);
        }

        $this->dispatch('cartUpdated', $this->cart);
    }

    public function clearCart()
    {
        $this->cart = [];

        $this->dispatch('cartUpdated', $this->cart);
    }

    public function render()
    {
        return view('livewire.selected-product', [
            'cart' => $this->cart,
        ]);
    }
}
