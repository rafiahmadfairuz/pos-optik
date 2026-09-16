<?php

namespace App\Livewire;

use App\Models\Frame;
use App\Models\Softlen;
use Livewire\Component;
use App\Models\Supplier;
use App\Models\Accessories;
use App\Models\LensaFinish;
use App\Models\LensaKhusus;
use App\Models\Pembelian;
use App\Models\ProdukCabang;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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

    public function gotoPage($page)
    {
        $this->page = $page;
    }

    protected function getModelFromType($type)
    {
        return match ($type) {
            'frame' => Frame::class,
            'lensa_finish' => LensaFinish::class,
            'softlens' => Softlen::class,
            'accessory' => Accessories::class,
            'lensa_khusus' => LensaKhusus::class,
            default => throw new \InvalidArgumentException("Tipe produk tidak dikenali: $type"),
        };
    }

    public function submit()
    {
        $this->validate([
            'surat_jalan' => 'required|string|max:255',
            'tanggal_pemesanan' => 'required|date',
        ]);

        if (!$this->supplier) {
            $this->addError('supplier', 'Silakan pilih supplier terlebih dahulu.');
            return;
        }

        if (count($this->cart) === 0) {
            $this->addError('cart', 'Keranjang masih kosong.');
            return;
        }

        try {
            DB::beginTransaction();

            $kode = 'PB-' . date('Ymd') . '-' . strtoupper(Str::random(5));

            // Header Pembelian (Gudang Utama selalu cabang_id = 0)
            $pembelian = Pembelian::create([
                'cabang_id'   => 0,
                'supplier_id' => $this->supplier['id'],
                'tanggal'     => $this->tanggal_pemesanan,
                'kode'        => $kode,
                'total'       => $this->total,
                'status'      => 'completed',
            ]);

            foreach ($this->cart as $item) {
                $modelClass = $this->getModelFromType($item['type']);
                $produkMaster = $modelClass::find($item['id']);

                if (!$produkMaster) {
                    throw new \Exception("Produk {$item['name']} tidak ditemukan di Master Data.");
                }

                // Lock & Sync ke ProdukCabang (Gudang Utama cabang_id = 0)
                $produkCabang = ProdukCabang::where('cabang_id', 0)
                    ->where('itemable_type', $item['type'])
                    ->where('itemable_id', $item['id'])
                    ->lockForUpdate()
                    ->first();

                if ($produkCabang) {
                    $produkCabang->increment('stok', $item['quantity']);
                } else {
                    ProdukCabang::create([
                        'cabang_id'     => 0,
                        'itemable_type' => $item['type'],
                        'itemable_id'   => $item['id'],
                        'stok'          => $item['quantity'],
                    ]);
                }

                // Catat Detail Pembelian
                $pembelian->items()->create([
                    'itemable_type' => $item['type'],
                    'itemable_id'   => $item['id'],
                    'quantity'      => $item['quantity'],
                    'price'         => $item['price'],
                    'subtotal'      => $item['price'] * $item['quantity'],
                ]);
            }

            DB::commit();

            session()->flash('success', 'Pembelian barang ke Gudang Utama berhasil disimpan.');

            $this->reset(['cart', 'supplier', 'surat_jalan', 'tanggal_pemesanan']);

            return redirect()->route('frame.index');

        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            session()->flash('error', 'Terjadi kesalahan saat menyimpan pembelian: ' . $e->getMessage());
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

    protected function queryFrames()
    {
        $frames = Frame::where(function ($q) {
            $q->where('merk', 'like', "%{$this->search}%")
                ->orWhere('tipe', 'like', "%{$this->search}%")
                ->orWhere('warna', 'like', "%{$this->search}%");
        })->get();

        return $frames->map(function ($item) {
            // Stok selalu ambil dari produk_cabangs Gudang Utama (cabang_id = 0)
            $stok = ProdukCabang::where('itemable_id', $item->id)
                ->where('itemable_type', 'frame')
                ->where('cabang_id', 0)
                ->value('stok') ?? 0;

            return array_merge($item->toArray(), [
                'id'    => $item->id,
                'name'  => $item->merk,
                'price' => $item->harga,
                'laba'  => $item->laba,
                'stok'  => $stok,
                'type'  => 'frame',
            ]);
        });
    }

    protected function queryLensaFinish()
    {
        $items = LensaFinish::where(function ($q) {
            $q->where('merk', 'like', "%{$this->search}%")
                ->orWhere('desain', 'like', "%{$this->search}%")
                ->orWhere('tipe', 'like', "%{$this->search}%")
                ->orWhere('sph', 'like', "%{$this->search}%")
                ->orWhere('cyl', 'like', "%{$this->search}%")
                ->orWhere('add', 'like', "%{$this->search}%");
        })->get();

        return $items->map(function ($item) {
            $stok = ProdukCabang::where('itemable_id', $item->id)
                ->where('itemable_type', 'lensa_finish')
                ->where('cabang_id', 0)
                ->value('stok') ?? 0;

            return array_merge($item->toArray(), [
                'id'           => $item->id,
                'name'         => $item->merk,
                'display_name' => trim("{$item->merk} " . ($item->tipe ? "Tipe:{$item->tipe} " : "") . ($item->desain ? "Desain:{$item->desain} " : "")),
                'price'        => $item->harga,
                'laba'         => $item->laba,
                'stok'         => $stok,
                'type'         => 'lensa_finish',
            ]);
        });
    }

    protected function queryLensaKhusus()
    {
        $items = LensaKhusus::where(function ($q) {
            $q->where('merk', 'like', "%{$this->search}%")
                ->orWhere('desain', 'like', "%{$this->search}%")
                ->orWhere('tipe', 'like', "%{$this->search}%")
                ->orWhere('sph', 'like', "%{$this->search}%")
                ->orWhere('cyl', 'like', "%{$this->search}%")
                ->orWhere('add', 'like', "%{$this->search}%");
        })->get();

        return $items->map(function ($item) {
            $stok = ProdukCabang::where('itemable_id', $item->id)
                ->where('itemable_type', 'lensa_khusus')
                ->where('cabang_id', 0)
                ->value('stok') ?? 0;

            return array_merge($item->toArray(), [
                'id'           => $item->id,
                'name'         => $item->merk,
                'display_name' => trim("{$item->merk} " . ($item->tipe ? "Tipe:{$item->tipe} " : "") . ($item->desain ? "Desain:{$item->desain} " : "")),
                'price'        => $item->harga,
                'laba'         => $item->laba,
                'stok'         => $stok,
                'type'         => 'lensa_khusus',
            ]);
        });
    }

    protected function querySoftlens()
    {
        $items = Softlen::where(function ($q) {
            $q->where('merk', 'like', "%{$this->search}%")
                ->orWhere('tipe', 'like', "%{$this->search}%")
                ->orWhere('warna', 'like', "%{$this->search}%");
        })->get();

        return $items->map(function ($item) {
            $stok = ProdukCabang::where('itemable_id', $item->id)
                ->where('itemable_type', 'softlens')
                ->where('cabang_id', 0)
                ->value('stok') ?? 0;

            return array_merge($item->toArray(), [
                'id'    => $item->id,
                'name'  => $item->merk,
                'price' => $item->harga,
                'laba'  => $item->laba,
                'stok'  => $stok,
                'type'  => 'softlens',
            ]);
        });
    }

    protected function queryAccessories()
    {
        $items = Accessories::where(function ($q) {
            $q->where('nama', 'like', "%{$this->search}%")
                ->orWhere('jenis', 'like', "%{$this->search}%");
        })->get();

        return $items->map(function ($item) {
            $stok = ProdukCabang::where('itemable_id', $item->id)
                ->where('itemable_type', 'accessory')
                ->where('cabang_id', 0)
                ->value('stok') ?? 0;

            return array_merge($item->toArray(), [
                'id'    => $item->id,
                'name'  => $item->nama,
                'price' => $item->harga,
                'laba'  => $item->laba,
                'stok'  => $stok,
                'type'  => 'accessory',
            ]);
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
            'products'  => $products,
            'suppliers' => $suppliers,
            'cart'      => $this->cart,
            'supplier'  => $this->supplier,
            'total'     => $this->total,
        ]);
    }
}
