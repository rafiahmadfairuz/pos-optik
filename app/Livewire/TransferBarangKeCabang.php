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
        return Frame::where(function ($q) {
            $q->where('merk', 'like', "%{$this->search}%")
                ->orWhere('tipe', 'like', "%{$this->search}%")
                ->orWhere('warna', 'like', "%{$this->search}%");
        })
            ->whereNull('cabang_id')
            ->get()
            ->map(fn($item) => array_merge(
                $item->toArray(),
                [
                    'id' => $item->id,
                    'name' => $item->merk,
                    'price' => $item->harga,
                    'laba' => $item->laba,
                    'stock' => $item->stok,
                    'type' => 'frame',
                ]
            ));
    }

    protected function queryLensaFinish()
    {
        return LensaFinish::where(function ($q) {
            $q->where('merk', 'like', "%{$this->search}%")
                ->orWhere('desain', 'like', "%{$this->search}%")
                ->orWhere('tipe', 'like', "%{$this->search}%");
        })
            ->whereNull('cabang_id')
            ->get()
            ->map(fn($item) => array_merge(
                $item->toArray(),
                [
                    'id' => $item->id,
                    'name' => $item->merk,
                    'price' => $item->harga,
                    'laba' => $item->laba,
                    'stock' => $item->stok,
                    'type' => 'lensa_finish',
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
            ->whereNull('cabang_id')
            ->get()
            ->map(fn($item) => array_merge(
                $item->toArray(),
                [
                    'id' => $item->id,
                    'name' => $item->merk,
                    'price' => $item->harga,
                    'laba' => $item->laba,
                    'stock' => $item->stok,
                    'type' => 'softlens',
                ]
            ));
    }

    protected function queryAccessories()
    {
        return Accessories::where(function ($q) {
            $q->where('nama', 'like', "%{$this->search}%")
                ->orWhere('jenis', 'like', "%{$this->search}%");
        })
            ->whereNull('cabang_id')
            ->get()
            ->map(fn($item) => array_merge(
                $item->toArray(),
                [
                    'id' => $item->id,
                    'name' => $item->nama,
                    'price' => $item->harga,
                    'laba' => $item->laba,
                    'stock' => $item->stok,
                    'type' => 'accessory',
                ]
            ));
    }

    protected function queryLensaKhusus()
    {
        return LensaKhusus::where(function ($q) {
            $q->where('merk', 'like', "%{$this->search}%")
                ->orWhere('desain', 'like', "%{$this->search}%")
                ->orWhere('tipe', 'like', "%{$this->search}%");
        })
            ->whereNull('cabang_id')
            ->get()
            ->map(fn($item) => array_merge(
                $item->toArray(),
                [
                    'id' => $item->id,
                    'name' => $item->merk,
                    'price' => $item->harga,
                    'laba' => $item->laba,
                    'stock' => $item->stok,
                    'type' => 'lensa_khusus',
                ]
            ));
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
            logger()->info('[TRANSFER] Validasi berhasil');

            DB::beginTransaction();
            logger()->info('[TRANSFER] Transaksi dimulai');

            $transfer = Transfer::create([
                'cabang_id' => $this->selectedCabangId,
                'tanggal' => now(),
                'kode' => $this->kodeTransfer,
                'retur' => false,
            ]);
            logger()->info('[TRANSFER] Transfer baru dibuat', ['kode' => $transfer->kode]);

            foreach ($this->cart as $item) {
                logger()->info('[TRANSFER] Memproses item', $item);

                $modelClass = $this->resolveModelClass($item['type']);
                if (!$modelClass) {
                    throw new \Exception('Tipe produk tidak valid.');
                }

                $produk = $modelClass::where('id', $item['id'])->whereNull('cabang_id')->first();
                if (!$produk) {
                    throw new \Exception('Produk tidak ditemukan di gudang utama.');
                }

                if ($produk->stok < $item['quantity']) {
                    throw new \Exception('Stok tidak cukup untuk produk: ' . ($produk->nama ?? $produk->merk ?? ''));
                }

                $produk->decrement('stok', $item['quantity']);

                $columns = Schema::getColumnListing((new $modelClass)->getTable());

                $query = $modelClass::query();

                $cekKolom = ['sku', 'merk', 'tipe', 'warna', 'desain', 'nama', 'jenis', 'sph', 'cyl', 'add'];

                foreach ($cekKolom as $kolom) {
                    if (in_array($kolom, $columns) && isset($produk->$kolom)) {
                        $query->where($kolom, $produk->$kolom);
                    }
                }

                $query->where('cabang_id', $this->selectedCabangId);

                $existing = $query->first();

                if ($existing) {
                    $existing->increment('stok', $item['quantity']);
                } else {
                    $data = [
                        'stok' => $item['quantity'],
                        'cabang_id' => $this->selectedCabangId,
                    ];

                    $kolomIsi = array_diff($columns, ['id', 'created_at', 'updated_at', 'stok', 'cabang_id', 'status_pesanan', 'estimasi_selesai_hari']);

                    foreach ($kolomIsi as $field) {
                        if (isset($produk->$field)) {
                            $data[$field] = $produk->$field;
                        }
                    }

                    $modelClass::create($data);
                }

                TransferItem::create([
                    'transfer_id' => $transfer->id,
                    'itemable_id' => $produk->id,
                    'itemable_type' => $modelClass,
                    'quantity' => $item['quantity'],
                    'price' => $produk->harga,
                ]);
            }

            DB::commit();
            session()->flash('success', 'Transfer berhasil');
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

        if (Auth::user()->role === 'admin') {
            return redirect()->route('dashboard');
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
