<?php

namespace App\Livewire;

use App\Models\Accessories;
use App\Models\Frame;
use Livewire\Component;
use App\Models\LensaFinish;
use App\Models\LensaKhusus;
use App\Models\Softlen;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\ProdukCabang;

class ProductSearch extends Component
{
    public $search = '';
    public $perPage = 5;
    public $page = 1;
    public $searchInput = '';
   

protected function queryFrames()
{
    return ProdukCabang::with('itemable')
        ->where('cabang_id', session('cabang_id'))
        ->where('itemable_type', 'frame')
        ->whereHasMorph('itemable', [\App\Models\Frame::class], function ($q) {
            $q->where('merk', 'like', "%{$this->search}%")
              ->orWhere('tipe', 'like', "%{$this->search}%")
              ->orWhere('warna', 'like', "%{$this->search}%");
        })
        ->get()
        ->map(fn($pc) => [
            'id'    => $pc->itemable_id,
            'name'  => $pc->itemable->merk ?? '-',
            'price' => $pc->itemable->harga,
            'laba'  => $pc->itemable->laba,
            'stock' => $pc->stok,
            'type'  => 'frame',
        ]);
}

protected function queryLensaFinish()
{
    return ProdukCabang::with('itemable')
        ->where('cabang_id', session('cabang_id'))
        ->where('itemable_type', 'lensa_finish')
        ->whereHasMorph('itemable', [\App\Models\LensaFinish::class], function ($q) {
            $q->where('merk', 'like', "%{$this->search}%")
              ->orWhere('desain', 'like', "%{$this->search}%")
              ->orWhere('tipe', 'like', "%{$this->search}%")
              ->orWhere('sph', 'like', "%{$this->search}%")
              ->orWhere('cyl', 'like', "%{$this->search}%")
              ->orWhere('add', 'like', "%{$this->search}%");
        })
        ->get()
        ->map(fn($pc) => [
            'id'    => $pc->itemable_id,
            'name'  => $pc->itemable->merk ?? '-',
            'price' => $pc->itemable->harga,
            'laba'  => $pc->itemable->laba,
            'stock' => $pc->stok,
            'type'  => 'lensa_finish',
        ]);
}

protected function queryLensaKhusus()
{
    return ProdukCabang::with('itemable')
        ->where('cabang_id', session('cabang_id'))
        ->where('itemable_type', 'lensa_khusus')
        ->whereHasMorph('itemable', [\App\Models\LensaKhusus::class], function ($q) {
            $q->where('merk', 'like', "%{$this->search}%")
              ->orWhere('desain', 'like', "%{$this->search}%")
              ->orWhere('tipe', 'like', "%{$this->search}%")
              ->orWhere('sph', 'like', "%{$this->search}%")
              ->orWhere('cyl', 'like', "%{$this->search}%")
              ->orWhere('add', 'like', "%{$this->search}%");
        })
        ->get()
        ->map(fn($pc) => [
            'id'    => $pc->itemable_id,
            'name'  => $pc->itemable->merk ?? '-',
            'price' => $pc->itemable->harga,
            'laba'  => $pc->itemable->laba,
            'stock' => $pc->stok,
            'type'  => 'lensa_khusus',
        ]);
}

protected function querySoftlens()
{
    return ProdukCabang::with('itemable')
        ->where('cabang_id', session('cabang_id'))
        ->where('itemable_type', 'softlens')
        ->whereHasMorph('itemable', [\App\Models\Softlen::class], function ($q) {
            $q->where('merk', 'like', "%{$this->search}%")
              ->orWhere('tipe', 'like', "%{$this->search}%")
              ->orWhere('warna', 'like', "%{$this->search}%");
        })
        ->get()
        ->map(fn($pc) => [
            'id'    => $pc->itemable_id,
            'name'  => $pc->itemable->merk ?? '-',
            'price' => $pc->itemable->harga,
            'laba'  => $pc->itemable->laba,
            'stock' => $pc->stok,
            'type'  => 'softlens',
        ]);
}

protected function queryAccessories()
{
    return ProdukCabang::with('itemable')
        ->where('cabang_id', session('cabang_id'))
        ->where('itemable_type', 'accessory')
        ->whereHasMorph('itemable', [\App\Models\Accessories::class], function ($q) {
            $q->where('nama', 'like', "%{$this->search}%")
              ->orWhere('jenis', 'like', "%{$this->search}%");
        })
        ->get()
        ->map(fn($pc) => [
            'id'    => $pc->itemable_id,
            'name'  => $pc->itemable->nama ?? '-',
            'price' => $pc->itemable->harga,
            'laba'  => $pc->itemable->laba,
            'stock' => $pc->stok,
            'type'  => 'accessory',
        ]);
}

    protected function getAllProducts(): Collection
    {
        return $this->queryFrames()
            ->concat($this->queryLensaFinish())
            ->concat($this->querySoftlens())
            ->concat($this->queryAccessories())
            ->concat($this->queryLensaKhusus());
    }

    public function paginateCollection(Collection $items, $perPage, $page = null, $options = [])
    {
        $page = $page ?: Paginator::resolveCurrentPage() ?: 1;
        $items = $items instanceof Collection ? $items : Collection::make($items);
        $slice = $items->slice(($page - 1) * $perPage, $perPage)->values();
        return new LengthAwarePaginator($slice, $items->count(), $perPage, $page, $options);
    }

    public function render()
    {
        $allProducts = $this->getAllProducts();

        $paginated = $this->paginateCollection($allProducts, $this->perPage, $this->page, [
            'path' => Paginator::resolveCurrentPath(),
        ]);

        return view('livewire.product-search', [
            'products' => $paginated,
        ]);
    }

    public function updatingSearchInput()
    {
        $this->page = 1;
    }

    public function runSearch()
    {
        $this->search = $this->searchInput;
        $this->page = 1;
    }

    public function resetSearch()
    {
        $this->searchInput = '';
        $this->search = '';
        $this->page = 1;
    }

    public function gotoPage($page)
    {
        $this->page = $page;
    }

    public function selectProduct($productId, $productType)
    {
        $allProducts = $this->getAllProducts();
        $product = $allProducts->firstWhere(function ($item) use ($productId, $productType) {
            return $item['id'] == $productId && $item['type'] == $productType;
        });

        if ($product) {
            $this->dispatch('productSelected', $product);
        }
    }
}
