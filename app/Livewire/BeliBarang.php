<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Supplier;
use App\Models\Frame;
use App\Models\LensaFinish;
use App\Models\Softlen;
use App\Models\Accessories;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BeliBarang extends Component
{
    use WithPagination;

    public $search = '';
    public $searchInput = '';
    public $supplier = null;
    public $cart = [];
    public $page = 1;
    public $perPage = 5;
    public $order_date;
    public $complete_date;
    public $customer_paying;

    public function selectSupplier($id)
    {
        $this->supplier = Supplier::find($id);
    }

    public function selectProduct($productId, $productType)
    {
        $product = $this->getAllProducts()->firstWhere(fn($item) => $item['id'] == $productId && $item['type'] == $productType);

        if (!$product) return;

        foreach ($this->cart as &$item) {
            if ($item['id'] == $product['id'] && $item['type'] == $product['type']) {
                $item['quantity']++;
                return;
            }
        }

        $product['quantity'] = 1;
        $this->cart[] = $product;
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
    }

    public function clearCart()
    {
        $this->cart = [];
    }

    public function runSearch()
    {
        $this->search = $this->searchInput;
        $this->page = 1;
    }

    public function resetSearch()
    {
        $this->search = '';
        $this->searchInput = '';
        $this->page = 1;
    }

    public function gotoPage($page)
    {
        $this->page = $page;
    }

    protected function getAllProducts(): Collection
    {
        return $this->queryFrames()
            ->concat($this->queryLensaFinish())
            ->concat($this->querySoftlens())
            ->concat($this->queryAccessories());
    }

    protected function queryFrames()
    {
        return Frame::where('merk', 'like', "%{$this->search}%")
            ->whereNull('cabang_id')
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'name' => $item->merk,
                'price' => $item->harga,
                'laba' => $item->laba,
                'stock' => $item->stok,
                'type' => 'frame',
            ]);
    }

    protected function queryLensaFinish()
    {
        return LensaFinish::where('merk', 'like', "%{$this->search}%")
            ->whereNull('cabang_id')
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'name' => $item->merk,
                'price' => $item->harga,
                'laba' => $item->laba,
                'stock' => $item->stok,
                'type' => 'lensa_finish',
            ]);
    }

    protected function querySoftlens()
    {
        return Softlen::where('merk', 'like', "%{$this->search}%")
            ->whereNull('cabang_id')
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'name' => $item->merk,
                'price' => $item->harga,
                'laba' => $item->laba,
                'stock' => $item->stok,
                'type' => 'softlens',
            ]);
    }

    protected function queryAccessories()
    {
        return Accessories::where('nama', 'like', "%{$this->search}%")
            ->whereNull('cabang_id')
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'name' => $item->nama,
                'price' => $item->harga,
                'laba' => $item->laba,
                'stock' => $item->stok,
                'type' => 'accessory',
            ]);
    }

    protected function paginateCollection(Collection $items, $perPage, $page = null, $options = [])
    {
        $page = $page ?: \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $slice = $items->slice(($page - 1) * $perPage, $perPage)->values();
        return new LengthAwarePaginator($slice, $items->count(), $perPage, $page, $options);
    }

    public function getTotalProperty()
    {
        return collect($this->cart)->sum(fn($item) => ($item['price'] ?? 0) * $item['quantity']);
    }

    public function submit()
    {
        // Simpan logika penyimpanan transaksi di sini
        // Validasi dulu
        $this->validate([
            'supplier' => 'required',
            'order_date' => 'required|date',
            'complete_date' => 'required|date|after_or_equal:order_date',
            'customer_paying' => 'required|numeric|min:0'
        ]);

        // Simpan ke database di sini (masih dummy untuk sekarang)
        session()->flash('success', 'Pembelian berhasil disimpan.');

        // Reset form
        $this->cart = [];
        $this->supplier = null;
        $this->customer_paying = '';
        $this->order_date = '';
        $this->complete_date = '';
    }

    public function render()
    {
        $products = $this->paginateCollection(
            $this->getAllProducts(),
            $this->perPage,
            $this->page,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        $suppliers = Supplier::when($this->search, function ($q) {
            $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%")
                ->orWhere('phone', 'like', "%{$this->search}%");
        })->paginate(3);

        return view('livewire.beli-barang', [
            'products' => $products,
            'suppliers' => $suppliers,
            'cart' => $this->cart,
            'supplier' => $this->supplier,
            'total' => $this->total,
        ]);
    }
}
