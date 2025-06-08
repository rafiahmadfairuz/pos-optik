<?php

namespace App\Livewire;

use Livewire\Component;

class SelectedProduct extends Component
{
    public $cart = [];

    // Menerima event productSelected dari komponen produk
    protected $listeners = ['productSelected' => 'addToCart'];

    // Tambahkan produk ke cart
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

        // Kirim cart ke parent (TransactionDetail) via dispatch
        $this->dispatch('cartUpdated', $this->cart);
    }

    // Kurangi jumlah produk dalam cart
    public function decreaseQuantity($index)
    {
        if (!isset($this->cart[$index])) return;

        if ($this->cart[$index]['quantity'] > 1) {
            $this->cart[$index]['quantity']--;
        } else {
            unset($this->cart[$index]);
            $this->cart = array_values($this->cart);
        }

        // Kirim cart terbaru ke parent
        $this->dispatch('cartUpdated', $this->cart);
    }

    // Kosongkan semua isi cart
    public function clearCart()
    {
        $this->cart = [];

        // Emit cart kosong ke parent
        $this->dispatch('cartUpdated', $this->cart);
    }

    // Render tampilan
    public function render()
    {
        return view('livewire.selected-product', [
            'cart' => $this->cart,
        ]);
    }
}
