<?php

namespace App\Livewire;

use App\Models\Accessories;
use App\Models\Cabang;
use App\Models\Frame;
use App\Models\LensaFinish;
use App\Models\LensaKhusus;
use App\Models\ProdukCabang;
use App\Models\Softlen;
use App\Models\Transfer;
use App\Models\TransferItem;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

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

    protected function queryFrames()
    {
        return ProdukCabang::with('itemable')
            ->where('cabang_id', session('cabang_id'))
            ->where('itemable_type', 'frame')
            ->whereHasMorph('itemable', [Frame::class], function ($q) {
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
            ->whereHasMorph('itemable', [LensaFinish::class], function ($q) {
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
            ->whereHasMorph('itemable', [LensaKhusus::class], function ($q) {
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
            ->whereHasMorph('itemable', [Softlen::class], function ($q) {
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
            ->whereHasMorph('itemable', [Accessories::class], function ($q) {
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

    protected function paginateCollection(Collection $items, $perPage, $page = null, $options = [])
    {
        $page = $page ?: Paginator::resolveCurrentPage() ?: 1;
        $slice = $items->slice(($page - 1) * $perPage, $perPage)->values();
        return new LengthAwarePaginator($slice, $items->count(), $perPage, $page, $options);
    }

   public function transfer()
{
    if (empty($this->cart)) {
        session()->flash('error', 'Keranjang kosong. Tambahkan barang terlebih dahulu.');
        return;
    }

    $this->validate([
        'selectedCabangId' => 'required|integer',
    ]);

    $fromCabangId = session('cabang_id');
    $toCabangId = $this->selectedCabangId;

    if ($fromCabangId == $toCabangId) {
        session()->flash('error', 'Cabang asal dan cabang tujuan tidak boleh sama.');
        return;
    }

    try {
        DB::beginTransaction();

        // 1. Buat Header Transfer dengan status 'completed'
        $transfer = Transfer::create([
            'from_cabang_id' => $fromCabangId,
            'to_cabang_id'   => $toCabangId,
            'tanggal'        => now(),
            'kode'           => $this->kodeTransfer,
            'status'         => 'completed',
        ]);

        foreach ($this->cart as $item) {
            // 2. Kunci row stok cabang asal (lockForUpdate)
            $stokAsal = ProdukCabang::where('cabang_id', $fromCabangId)
                ->where('itemable_type', $item['type'])
                ->where('itemable_id', $item['id'])
                ->lockForUpdate()
                ->first();

            if (!$stokAsal || $stokAsal->stok < $item['quantity']) {
                throw new \Exception("Stok tidak mencukupi untuk produk: {$item['name']}");
            }

            // Kurangi stok asal
            $stokAsal->decrement('stok', $item['quantity']);

            // 3. Kunci row stok cabang tujuan (lockForUpdate)
            $stokTujuan = ProdukCabang::where('cabang_id', $toCabangId)
                ->where('itemable_type', $item['type'])
                ->where('itemable_id', $item['id'])
                ->lockForUpdate()
                ->first();

            if ($stokTujuan) {
                $stokTujuan->increment('stok', $item['quantity']);
            } else {
                ProdukCabang::create([
                    'cabang_id'     => $toCabangId,
                    'itemable_type' => $item['type'],
                    'itemable_id'   => $item['id'],
                    'stok'          => $item['quantity'],
                ]);
            }

            // 4. Catat Item Transfer
            TransferItem::create([
                'transfer_id'   => $transfer->id,
                'itemable_type' => $item['type'],
                'itemable_id'   => $item['id'],
                'quantity'      => $item['quantity'],
                'price'         => $item['price'],
            ]);
        }

        DB::commit();
        session()->flash('success', 'Transfer barang berhasil dilakukan.');
        return redirect()->route('frame.index');

    } catch (\Throwable $e) {
        DB::rollBack();
        session()->flash('error', $e->getMessage());
    }
}

    public function render()
    {
        $products = $this->paginateCollection(
            $this->getAllProducts(),
            $this->perPage,
            $this->page,
            ['path' => Paginator::resolveCurrentPath()]
        );

        $cabangs = Cabang::where('id', '!=', session('cabang_id'))->paginate(4);

        return view('livewire.transfer-barang-ke-cabang', [
            'products' => $products,
            'cabangs'  => $cabangs,
            'cart'     => $this->cart,
            'cabang'   => $this->cabang,
        ]);
    }
}
