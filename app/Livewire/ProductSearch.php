<?php

namespace App\Livewire;

use App\Models\Accessories;
use App\Models\Frame;
use App\Models\LensaFinish;
use App\Models\LensaKhusus;
use App\Models\ProdukCabang;
use App\Models\Softlen;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Livewire\Component;

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
                'id'     => $pc->itemable_id,
                'name'   => $pc->itemable->merk ?? '-',
                'price'  => $pc->itemable->harga ?? 0,
                'laba'   => $pc->itemable->laba ?? 0,
                'stock'  => $pc->stok,
                'type'   => 'frame',
                'tipe'   => $pc->itemable->tipe ?? null,
                'warna'  => $pc->itemable->warna ?? null,
                'desain' => null,
                'jenis'  => null,
                'sph'    => null,
                'cyl'    => null,
                'add'    => null,
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
                'id'     => $pc->itemable_id,
                'name'   => $pc->itemable->merk ?? '-',
                'price'  => $pc->itemable->harga ?? 0,
                'laba'   => $pc->itemable->laba ?? 0,
                'stock'  => $pc->stok,
                'type'   => 'lensa_finish',
                'tipe'   => $pc->itemable->tipe ?? null,
                'warna'  => null,
                'desain' => $pc->itemable->desain ?? null,
                'jenis'  => null,
                'sph'    => $pc->itemable->sph ?? null,
                'cyl'    => $pc->itemable->cyl ?? null,
                'add'    => $pc->itemable->add ?? null,
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
                'id'     => $pc->itemable_id,
                'name'   => $pc->itemable->merk ?? '-',
                'price'  => $pc->itemable->harga ?? 0,
                'laba'   => $pc->itemable->laba ?? 0,
                'stock'  => $pc->stok,
                'type'   => 'lensa_khusus',
                'tipe'   => $pc->itemable->tipe ?? null,
                'warna'  => null,
                'desain' => $pc->itemable->desain ?? null,
                'jenis'  => null,
                'sph'    => $pc->itemable->sph ?? null,
                'cyl'    => $pc->itemable->cyl ?? null,
                'add'    => $pc->itemable->add ?? null,
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
                'id'     => $pc->itemable_id,
                'name'   => $pc->itemable->merk ?? '-',
                'price'  => $pc->itemable->harga ?? 0,
                'laba'   => $pc->itemable->laba ?? 0,
                'stock'  => $pc->stok,
                'type'   => 'softlens',
                'tipe'   => $pc->itemable->tipe ?? null,
                'warna'  => $pc->itemable->warna ?? null,
                'desain' => null,
                'jenis'  => null,
                'sph'    => null,
                'cyl'    => null,
                'add'    => null,
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
                'id'     => $pc->itemable_id,
                'name'   => $pc->itemable->nama ?? '-',
                'price'  => $pc->itemable->harga ?? 0,
                'laba'   => $pc->itemable->laba ?? 0,
                'stock'  => $pc->stok,
                'type'   => 'accessory',
                'tipe'   => null,
                'warna'  => null,
                'desain' => null,
                'jenis'  => $pc->itemable->jenis ?? null,
                'sph'    => null,
                'cyl'    => null,
                'add'    => null,
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
