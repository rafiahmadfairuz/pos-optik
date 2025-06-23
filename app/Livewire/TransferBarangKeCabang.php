<?php


namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Cabang;
use App\Models\Frame;
use App\Models\LensaFinish;
use App\Models\Softlen;
use App\Models\Accessories;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TransferBarangKeCabang extends Component
{
    use WithPagination;

    public $search = '';
    public $searchInput = '';
    public $nama = '';
    public $slug = '';
    public $alamat = '';

    public $cabang = null;
    public $cart = [];

    public $page = 1;
    public $perPage = 5;

    public function selectCabang($id)
    {
        $this->cabang = Cabang::find($id);
        if ($this->cabang) {
            $this->nama = $this->cabang->nama;
            $this->slug = $this->cabang->slug;
            $this->alamat = $this->cabang->alamat;
        }
    }

    public function selectProduct($productId, $productType)
    {
        $product = $this->getAllProducts()->firstWhere(function ($item) use ($productId, $productType) {
            return $item['id'] == $productId && $item['type'] == $productType;
        });

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

    public function updatingSearchInput()
    {
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
            ->whereNull('cabang_id') // ✅ hanya milik gudang utama
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

    public function render()
    {
        $products = $this->paginateCollection(
            $this->getAllProducts(),
            $this->perPage,
            $this->page,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        $cabangs = Cabang::when($this->search, function ($q) {
            $q->where('nama', 'like', "%{$this->search}%")
              ->orWhere('slug', 'like', "%{$this->search}%")
              ->orWhere('alamat', 'like', "%{$this->search}%");
        })->paginate(4);

        return view('livewire.transfer-barang-ke-cabang', [
            'products' => $products,
            'cabangs' => $cabangs,
            'cart' => $this->cart,
            'cabang' => $this->cabang,
        ]);
    }
}
