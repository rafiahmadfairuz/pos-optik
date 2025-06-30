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

class ProductSearch extends Component
{
    public $search = '';
    public $perPage = 5;
    public $page = 1;
    public $searchInput = '';
    protected function queryFrames()
    {
        return Frame::where(function ($q) {
            $q->where('merk', 'like', "%{$this->search}%")
                ->orWhere('tipe', 'like', "%{$this->search}%")
                ->orWhere('warna', 'like', "%{$this->search}%");
        })
            ->where('cabang_id', session('cabang_id'))
            ->get()
            ->map(fn($item) => array_merge(
                $item->toArray(),
                [
                    'id'    => $item->id,
                    'name'  => $item->merk,
                    'price' => $item->harga,
                    'laba'  => $item->laba,
                    'stock' => $item->stok,
                    'type'  => 'frame',
                ]
            ));
    }

    protected function queryLensaFinish()
    {
        return LensaFinish::where(function ($q) {
            $q->where('merk', 'like', "%{$this->search}%")
                ->orWhere('desain', 'like', "%{$this->search}%")
                ->orWhere('tipe', 'like', "%{$this->search}%")
                ->orWhere('sph', 'like', "%{$this->search}%")
                ->orWhere('cyl', 'like', "%{$this->search}%")
                ->orWhere('add', 'like', "%{$this->search}%");
        })
            ->where('cabang_id', session('cabang_id'))
            ->get()
            ->map(fn($item) => array_merge(
                $item->toArray(),
                [
                    'id'            => $item->id,
                    'name'          => $item->merk,
                    'display_name'  => trim(
                        "{$item->merk} " .
                            ($item->tipe ? "Tipe:{$item->tipe} " : "") .
                            ($item->desain ? "Desain:{$item->desain} " : "") .
                            ($item->sph ? "SPH:{$item->sph} " : "") .
                            ($item->cyl ? "CYL:{$item->cyl} " : "") .
                            ($item->add ? "ADD:{$item->add}" : "")
                    ),
                    'price'         => $item->harga,
                    'laba'          => $item->laba,
                    'stock'         => $item->stok,
                    'type'          => 'lensa_finish',
                ]
            ));
    }

    protected function queryLensaKhusus()
    {
        return LensaKhusus::where(function ($q) {
            $q->where('merk', 'like', "%{$this->search}%")
                ->orWhere('desain', 'like', "%{$this->search}%")
                ->orWhere('tipe', 'like', "%{$this->search}%")
                ->orWhere('sph', 'like', "%{$this->search}%")
                ->orWhere('cyl', 'like', "%{$this->search}%")
                ->orWhere('add', 'like', "%{$this->search}%");
        })
            ->where('cabang_id', session('cabang_id'))
            ->get()
            ->map(fn($item) => array_merge(
                $item->toArray(),
                [
                    'id'            => $item->id,
                    'name'          => $item->merk,
                    'display_name'  => trim(
                        "{$item->merk} " .
                            ($item->tipe ? "Tipe:{$item->tipe} " : "") .
                            ($item->desain ? "Desain:{$item->desain} " : "") .
                            ($item->sph ? "SPH:{$item->sph} " : "") .
                            ($item->cyl ? "CYL:{$item->cyl} " : "") .
                            ($item->add ? "ADD:{$item->add}" : "")
                    ),
                    'price'         => $item->harga,
                    'laba'          => $item->laba,
                    'stock'         => $item->stok,
                    'type'          => 'lensa_khusus',
                ]
            ));
    }

    protected function querySoftlens()
    {
        return Softlen::where(function ($q) {
            $q->where('merk', 'like', "%{$this->search}%")
                ->orWhere('tipe', 'like', "%{$this->search}%")
                ->orWhere('warna', 'like', "%{$this->search}%");
        })
            ->where('cabang_id', session('cabang_id'))
            ->get()
            ->map(fn($item) => array_merge(
                $item->toArray(),
                [
                    'id'    => $item->id,
                    'name'  => $item->merk,
                    'price' => $item->harga,
                    'laba'  => $item->laba,
                    'stock' => $item->stok,
                    'type'  => 'softlens',
                ]
            ));
    }

    protected function queryAccessories()
    {
        return Accessories::where(function ($q) {
            $q->where('nama', 'like', "%{$this->search}%")
                ->orWhere('jenis', 'like', "%{$this->search}%");
        })
            ->where('cabang_id', session('cabang_id'))
            ->get()
            ->map(fn($item) => array_merge(
                $item->toArray(),
                [
                    'id'    => $item->id,
                    'name'  => $item->nama,
                    'price' => $item->harga,
                    'laba'  => $item->laba,
                    'stock' => $item->stok,
                    'type'  => 'accessory',
                ]
            ));
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
