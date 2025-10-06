<?php

namespace App\Livewire;

use App\Models\Frame;
use App\Models\Cabang;
use App\Models\Softlen;
use Livewire\Component;
use App\Models\Transfer;
use App\Models\Accessories;
use App\Models\LensaFinish;
use App\Models\LensaKhusus;
use Illuminate\Support\Str;
use App\Models\ProdukCabang;
use App\Models\TransferItem;
use Livewire\WithPagination;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\LengthAwarePaginator;

class TransferBarangKeCabang extends Component
{
    use WithPagination;

    public $selectedCabangId;
    public $cart = [];
    public $kodeTransfer;

    public $search = '';
    public $searchInput = '';
    public $page = 1;
    public $perPage = 5;

    public $nama = '';
    public $slug = '';
    public $alamat = '';
    public $cabang = null;

    public function mount()
    {
        $this->kodeTransfer = 'TF-' . strtoupper(Str::random(8));
    }


    public function selectCabang($id)
    {
        $this->cabang = Cabang::find($id);
        if ($this->cabang) {
            $this->selectedCabangId = $this->cabang->id;
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
            ->concat($this->queryAccessories())
            ->concat($this->queryLensaKhusus());
    }

    protected function queryFrames()
    {
        $isGudangUtama = Auth::user()->role === 'gudang_utama';
        $cabangId = session('cabang_id');

        $frames = Frame::where(function ($q) {
            $q->where('merk', 'like', "%{$this->search}%")
                ->orWhere('tipe', 'like', "%{$this->search}%")
                ->orWhere('warna', 'like', "%{$this->search}%");
        })->get();

        return $frames->map(function ($item) use ($isGudangUtama, $cabangId) {
            $stok = $isGudangUtama
                ? $item->stok
                : (ProdukCabang::where('itemable_id', $item->id)
                    ->where('itemable_type', 'frame')
                    ->where('cabang_id', $cabangId)
                    ->value('stok') ?? 0);

            return array_merge($item->toArray(), [
                'id'    => $item->id,
                'name'  => $item->merk,
                'price' => $item->harga,
                'laba'  => $item->laba,
                'stock' => $stok,
                'type'  => 'frame',
            ]);
        });
    }

    protected function queryLensaFinish()
    {
        $isGudangUtama = Auth::user()->role === 'gudang_utama';
        $cabangId = session('cabang_id');

        $items = LensaFinish::where(function ($q) {
            $q->where('merk', 'like', "%{$this->search}%")
                ->orWhere('desain', 'like', "%{$this->search}%")
                ->orWhere('tipe', 'like', "%{$this->search}%")
                ->orWhere('sph', 'like', "%{$this->search}%")
                ->orWhere('cyl', 'like', "%{$this->search}%")
                ->orWhere('add', 'like', "%{$this->search}%");
        })->get();

        return $items->map(function ($item) use ($isGudangUtama, $cabangId) {
            $stok = $isGudangUtama
                ? $item->stok
                : (ProdukCabang::where('itemable_id', $item->id)
                    ->where('itemable_type', 'lensa_finish')
                    ->where('cabang_id', $cabangId)
                    ->value('stok') ?? 0);

            return array_merge($item->toArray(), [
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
                'stock'         => $stok,
                'type'          => 'lensa_finish',
            ]);
        });
    }

    protected function queryLensaKhusus()
    {
        $isGudangUtama = Auth::user()->role === 'gudang_utama';
        $cabangId = session('cabang_id');

        $items = LensaKhusus::where(function ($q) {
            $q->where('merk', 'like', "%{$this->search}%")
                ->orWhere('desain', 'like', "%{$this->search}%")
                ->orWhere('tipe', 'like', "%{$this->search}%")
                ->orWhere('sph', 'like', "%{$this->search}%")
                ->orWhere('cyl', 'like', "%{$this->search}%")
                ->orWhere('add', 'like', "%{$this->search}%");
        })->get();

        return $items->map(function ($item) use ($isGudangUtama, $cabangId) {
            $stok = $isGudangUtama
                ? $item->stok
                : (ProdukCabang::where('itemable_id', $item->id)
                    ->where('itemable_type', 'lensa_khusus')
                    ->where('cabang_id', $cabangId)
                    ->value('stok') ?? 0);

            return array_merge($item->toArray(), [
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
                'stock'         => $stok,
                'type'          => 'lensa_khusus',
            ]);
        });
    }

    protected function querySoftlens()
    {
        $isGudangUtama = Auth::user()->role === 'gudang_utama';
        $cabangId = session('cabang_id');

        $items = Softlen::where(function ($q) {
            $q->where('merk', 'like', "%{$this->search}%")
                ->orWhere('tipe', 'like', "%{$this->search}%")
                ->orWhere('warna', 'like', "%{$this->search}%");
        })->get();

        return $items->map(function ($item) use ($isGudangUtama, $cabangId) {
            $stok = $isGudangUtama
                ? $item->stok
                : (ProdukCabang::where('itemable_id', $item->id)
                    ->where('itemable_type', 'softlens')
                    ->where('cabang_id', $cabangId)
                    ->value('stok') ?? 0);

            return array_merge($item->toArray(), [
                'id'    => $item->id,
                'name'  => $item->merk,
                'price' => $item->harga,
                'laba'  => $item->laba,
                'stock' => $stok,
                'type'  => 'softlens',
            ]);
        });
    }

    protected function queryAccessories()
    {
        $isGudangUtama = Auth::user()->role === 'gudang_utama';
        $cabangId = session('cabang_id');

        $items = Accessories::where(function ($q) {
            $q->where('nama', 'like', "%{$this->search}%")
                ->orWhere('jenis', 'like', "%{$this->search}%");
        })->get();

        return $items->map(function ($item) use ($isGudangUtama, $cabangId) {
            $stok = $isGudangUtama
                ? $item->stok
                : (ProdukCabang::where('itemable_id', $item->id)
                    ->where('itemable_type', 'accessory')
                    ->where('cabang_id', $cabangId)
                    ->value('stok') ?? 0);

            return array_merge($item->toArray(), [
                'id'    => $item->id,
                'name'  => $item->nama,
                'price' => $item->harga,
                'laba'  => $item->laba,
                'stock' => $stok,
                'type'  => 'accessory',
            ]);
        });
    }



    protected function paginateCollection(Collection $items, $perPage, $page = null, $options = [])
    {
        $page = $page ?: \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $slice = $items->slice(($page - 1) * $perPage, $perPage)->values();
        return new LengthAwarePaginator($slice, $items->count(), $perPage, $page, $options);
    }

    private function resolveModelClass($type)
    {
        return [
            'frame' => Frame::class,
            'lensa_finish' => LensaFinish::class,
            'lensa_khusus' => LensaKhusus::class,
            'softlens' => Softlen::class,
            'accessory' => Accessories::class,
        ][$type] ?? null;
    }

    public function transfer()
    {
        logger()->info('[TRANSFER] Proses dimulai');

        try {
            if (empty($this->cart)) {
                throw new \Exception('Keranjang kosong. Tambahkan barang terlebih dahulu.');
            }

            $this->validate([
                'selectedCabangId' => 'required|exists:cabangs,id',
            ]);

            DB::beginTransaction();
            logger()->info('[TRANSFER] Transaksi dimulai');

            $transfer = Transfer::create([
                'cabang_id' => $this->selectedCabangId,
                'tanggal' => now(),
                'kode' => $this->kodeTransfer,
                'retur' => false,
            ]);

            foreach ($this->cart as $item) {
                $modelClass = $this->resolveModelClass($item['type']);
                if (!$modelClass) {
                    throw new \Exception('Tipe produk tidak valid.');
                }

                // 🔹 Ambil produk dari gudang utama (tanpa cabang_id)
                $produk = $modelClass::find($item['id']);
                if (!$produk) {
                    throw new \Exception('Produk tidak ditemukan di gudang utama.');
                }

                if ($produk->stok < $item['quantity']) {
                    throw new \Exception('Stok tidak cukup untuk produk: ' . ($produk->nama ?? $produk->merk ?? ''));
                }

                // 🔻 Kurangi stok di gudang utama
                $produk->decrement('stok', $item['quantity']);

                // 🔹 Cek stok cabang di produk_cabangs
                $produkCabang = ProdukCabang::where('cabang_id', $this->selectedCabangId)
                    ->where('itemable_id', $produk->id)
                    ->where('itemable_type', $item['type'])
                    ->first();

                if ($produkCabang) {
                    $produkCabang->increment('stok', $item['quantity']);
                    logger()->info('[TRANSFER] Tambah stok di cabang', [
                        'produk' => $produk->sku ?? $produk->merk,
                        'stok_baru' => $produkCabang->stok,
                    ]);
                } else {
                    ProdukCabang::create([
                        'cabang_id' => $this->selectedCabangId,
                        'itemable_id' => $produk->id,
                        'itemable_type' => $item['type'],
                        'stok' => $item['quantity'],
                    ]);
                    logger()->info('[TRANSFER] Produk baru dibuat di cabang', [
                        'produk' => $produk->sku ?? $produk->merk,
                        'qty' => $item['quantity'],
                    ]);
                }

                // 🔹 Simpan item transfer
                TransferItem::create([
                    'transfer_id' => $transfer->id,
                    'itemable_id' => $produk->id,
                    'itemable_type' => $item['type'], // alias morphMap
                    'quantity' => $item['quantity'],
                    'price' => $produk->harga,
                ]);
            }

            DB::commit();
            session()->flash('success', 'Transfer berhasil.');
            $this->reset(['cart']);
        } catch (\Throwable $e) {
            DB::rollBack();
            logger()->error('[TRANSFER] Gagal', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            session()->flash('error', $e->getMessage());
        }

        return redirect()->route('frame.index');
    }


    public function render()
    {
        $products = $this->paginateCollection(
            $this->getAllProducts(),
            $this->perPage,
            $this->page,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        $cabangs = Cabang::paginate(4);

        return view('livewire.transfer-barang-ke-cabang', [
            'products' => $products,
            'cabangs' => $cabangs,
            'cart' => $this->cart,
            'cabang' => $this->cabang,
        ]);
    }
}
