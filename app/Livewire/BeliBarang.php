<?php

namespace App\Livewire;

use App\Models\Frame;
use App\Models\Softlen;
use Livewire\Component;
use App\Models\Supplier;
use App\Models\Accessories;
use App\Models\LensaFinish;
use App\Models\LensaKhusus;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

class BeliBarang extends Component
{
    use WithPagination;

    public $search = '';
    public $searchInput = '';
    public $searchProduk = '';
    public $searchInputProduk = '';
    public $supplier = null;
    public $cart = [];
    public $page = 1;
    public $perPage = 5;

    public $surat_jalan;
    public $tanggal_pemesanan;

    public function getTotalProperty()
    {
        return collect($this->cart)->sum(function ($item) {
            return ($item['price'] ?? 0) * $item['quantity'];
        });
    }

    public function selectSupplier($id)
    {
        $this->supplier = Supplier::find($id);
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
        $product['harga_jual'] = $product['price'] + ($product['laba'] ?? 0);
        $this->cart[] = $product;
    }

    public function decreaseQuantity($index)
    {
        if (!isset($this->cart[$index])) return;

        if ($this->cart[$index]['quantity'] > 1) {
            $this->cart[$index]['quantity']--;
        } else {
            unset($this->cart[$index]);
            $this->cart = array_values($this->cart); // reset index
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
    public function runSearchProduk()
    {
        $this->searchProduk = $this->searchInputProduk;
        $this->page = 1;
    }

    public function resetSearchProduk()
    {
        $this->searchProduk = '';
        $this->searchInputProduk = '';
        $this->page = 1;
    }

    public function gotoPage($page)
    {
        $this->page = $page;
    }
    protected function getModelFromType($type)
    {
        return match ($type) {
            'frame' => \App\Models\Frame::class,
            'lensa_finish' => \App\Models\LensaFinish::class,
            'softlens' => \App\Models\Softlen::class,
            'accessory' => \App\Models\Accessories::class,
            'lensa_khusus' => \App\Models\LensaKhusus::class,
            default => throw new \InvalidArgumentException("Tipe produk tidak dikenali: $type"),
        };
    }


    public function submit()
    {
        $this->validate([
            'surat_jalan' => 'required|string|max:255',
            'tanggal_pemesanan' => 'required|date',
        ]);

        if (count($this->cart) === 0) {
            $this->addError('cart', 'Keranjang masih kosong.');
            return;
        }

        try {
            DB::beginTransaction();

            $kode = 'PB-' . strtoupper(Str::random(8));

            $pembelian = \App\Models\Pembelian::create([
                'supplier_id' => $this->supplier['id'],
                'tanggal' => $this->tanggal_pemesanan,
                'kode' => $kode,
                'total' => $this->total,
            ]);

            foreach ($this->cart as $item) {
                $model = $this->getModelFromType($item['type']);
                $produk = $model::find($item['id']);

                if (!$produk) {
                    throw new \Exception("Produk dengan ID {$item['id']} tidak ditemukan.");
                }

                $produk->stok += $item['quantity'];
                $produk->save();

                $pembelian->items()->create([
                    'itemable_type' => $model,
                    'itemable_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }

            DB::commit();

            session()->flash('success', 'Pembelian berhasil disimpan.');

            $this->cart = [];
            $this->supplier = null;
            $this->surat_jalan = '';
            $this->tanggal_pemesanan = '';

            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('dashboard');
            } elseif ($user->role === 'gudang_utama') {
                return redirect()->route('frame.index');
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            session()->flash('error', 'Terjadi kesalahan saat menyimpan pembelian. Silakan coba lagi.');
        }
    }


    protected function getAllProducts(): Collection
    {
        return $this->queryFrames()
            ->concat($this->queryLensaFinish())
            ->concat($this->querySoftlens())
            ->concat($this->queryAccessories())
            ->concat($this->queryLensaKhusus());
    }

    protected function queryLensaKhusus()
    {
        return LensaKhusus::where('merk', 'like', "%{$this->searchProduk}%")
            ->whereNull('cabang_id')
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'name' => $item->merk,
                'price' => $item->harga,
                'laba' => $item->laba,
                'stock' => $item->stok,
                'type' => 'lensa_khusus',
            ]);
    }


    protected function queryFrames()
    {
        return Frame::where('merk', 'like', "%{$this->searchProduk}%")
            ->whereNull('cabang_id')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->merk,
                    'price' => $item->harga,
                    'laba' => $item->laba,
                    'stock' => $item->stok,
                    'type' => 'frame',
                ];
            });
    }

    protected function queryLensaFinish()
    {
        return LensaFinish::where('merk', 'like', "%{$this->searchProduk}%")
            ->whereNull('cabang_id')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->merk,
                    'price' => $item->harga,
                    'laba' => $item->laba,
                    'stock' => $item->stok,
                    'type' => 'lensa_finish',
                ];
            });
    }

    protected function querySoftlens()
    {
        return Softlen::where('merk', 'like', "%{$this->searchProduk}%")
            ->whereNull('cabang_id')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->merk,
                    'price' => $item->harga,
                    'laba' => $item->laba,
                    'stock' => $item->stok,
                    'type' => 'softlens',
                ];
            });
    }

    protected function queryAccessories()
    {
        return Accessories::where('nama', 'like', "%{$this->searchProduk}%")
            ->whereNull('cabang_id')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->nama,
                    'price' => $item->harga,
                    'laba' => $item->laba,
                    'stock' => $item->stok,
                    'type' => 'accessory',
                ];
            });
    }

    protected function paginateCollection(Collection $items, $perPage, $page = null, $options = [])
    {
        $page = $page ?: \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $slice = $items->slice(($page - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator(
            $slice,
            $items->count(),
            $perPage,
            $page,
            $options
        );
    }

    public function render()
    {
        $products = $this->paginateCollection(
            $this->getAllProducts(),
            $this->perPage,
            $this->page,
            [
                'path' => request()->url(),
                'pageName' => 'page',
            ]
        );

        $suppliers = Supplier::when($this->search, function ($q) {
            $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%")
                ->orWhere('phone', 'like', "%{$this->search}%");
        })->paginate(3)->withQueryString();

        return view('livewire.beli-barang', [
            'products' => $products,
            'suppliers' => $suppliers,
            'cart' => $this->cart,
            'supplier' => $this->supplier,
            'total' => $this->total,
        ]);
    }
}
