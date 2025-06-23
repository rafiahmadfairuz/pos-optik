<style>
    .card-scroll {
        max-height: 300px;
        overflow-y: auto;
    }

    .table thead th {
        position: sticky;
        top: 0;
        background: #fff;
    }
</style>
<div class="container-fluid py-3">
    <h2 class="fw-bold "><i class="bi bi-cash-stack me-2"></i>Transfer Barang</h2>

    <div class="row g-3">
        <div class="row g-3 align-items-stretch">
            <div class="col-lg-5 col-md-12">
                <div class="card shadow-sm h-100 border-0 rounded-3">
                    <div class="card-header bg-white fw-semibold border-bottom d-flex align-items-center">
                        <i class="bi bi-person-circle fs-3  me-2"></i>
                        Informasi Cabang
                    </div>
                    <div class="card-body py-4" style="min-height: 160px;">
                        @if ($cabang)
                            <div class="d-flex align-items-center mb-3 text-truncate" title="{{ $cabang['nama'] }}">
                                <i class="bi bi-person-fill fs-4 text-primary me-3"></i>
                                <div>
                                    <div class="text-muted small">Name</div>
                                    <strong class="fs-5">{{ $cabang['nama'] }}</strong>
                                </div>
                            </div>
                            <div class="d-flex align-items-center text-truncate" title="{{ $cabang['alamat'] }}">
                                <i class="bi bi-envelope-fill fs-4 text-warning me-3"></i>
                                <div>
                                    <div class="text-muted small">Alamat</div>
                                    <span class="fs-5">{{ $cabang['alamat'] }}</span>
                                </div>
                            </div>
                        @else
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-info-circle display-5 d-block mb-3"></i>
                                <small>Pilih Cabang Terlebih Dahulu</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-7 col-md-12">
                <div class="card shadow-sm h-100">
                    <div
                        class="card-header fw-semibold d-flex justify-content-between align-items-center bg-white border-bottom">
                        <div>
                            <i class="bi bi-search me-2"></i>Pilih Cabang Yang Dituju
                        </div>
                    </div>

                    <div class="card-body p-3 position-relative">


                        <div wire:loading.class="opacity-50" wire:target="selectedSupplier"
                            class="table-responsive card-scroll">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama</th>
                                        <th>Alamat</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($cabangs as $cabang)
                                        <tr>
                                            <td>{{ $cabang->nama }}</td>
                                            <td>{{ $cabang->alamat }}</td>
                                            <td>
                                                <button wire:click="selectSupplier({{ $cabang->id }})" class="btn"
                                                    wire:loading.attr="disabled">
                                                    +
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Cabang Tidak Ditemukan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-3 d-flex justify-content-end">
                                {{ $cabangs->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header fw-bold text-decoration-underline">Choose Product</div>
                <div class="card-body p-2">

                    <div class="input-group mb-2">
                        <input type="text" class="form-control" placeholder="Search products by name..."
                            wire:model.defer="searchInput" autocomplete="off">
                        <button class="btn btn-primary" wire:click="runSearch">
                            <i class="bi bi-search"></i>
                        </button>
                        <button class="btn btn-outline-primary" wire:click="resetSearch">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </div>

                    <div class="table-responsive card-scroll" style="max-height: 300px;">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Harga Jual</th>
                                    @if (in_array(Auth::user()->role, ['admin', 'gudang']))
                                        <th>Laba</th>
                                    @endif
                                    <th>Stok</th>
                                    <th>Tipe</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                    <tr>
                                        <td>{{ $product['name'] }}</td>
                                        <td>Rp. {{ number_format($product['price'], 0, ',', '.') }}</td>
                                        @if (in_array(Auth::user()->role, ['admin', 'gudang']))
                                            <td>Rp. {{ number_format($product['laba'], 0, ',', '.') }}</td>
                                        @endif
                                        <td>{{ $product['stock'] }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $product['type'])) }}</td>
                                        <td>
                                            <button class="btn"
                                                wire:click="selectProduct({{ $product['id'] }}, '{{ $product['type'] }}')">+</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No products found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                    <div class="mt-2 d-flex justify-content-center">
                        @if ($products->hasPages())
                            <nav>
                                <ul class="pagination pagination-sm mb-0">
                                    {{-- Previous Page Link --}}
                                    @if ($products->onFirstPage())
                                        <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                                    @else
                                        <li class="page-item"><a href="#"
                                                wire:click.prevent="gotoPage({{ $products->currentPage() - 1 }})"
                                                class="page-link">&laquo;</a></li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($products->links()->elements[0] as $page => $url)
                                        @if ($page == $products->currentPage())
                                            <li class="page-item active"><span
                                                    class="page-link">{{ $page }}</span></li>
                                        @else
                                            <li class="page-item"><a href="#"
                                                    wire:click.prevent="gotoPage({{ $page }})"
                                                    class="page-link">{{ $page }}</a></li>
                                        @endif
                                    @endforeach
                                    {{-- Next Page Link --}}
                                    @if ($products->hasMorePages())
                                        <li class="page-item"><a href="#"
                                                wire:click.prevent="gotoPage({{ $products->currentPage() + 1 }})"
                                                class="page-link">&raquo;</a></li>
                                    @else
                                        <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                                    @endif
                                </ul>
                            </nav>
                        @endif
                    </div>

                </div>

            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header fw-bold text-decoration-underline">Produk Yang Dikirim</div>
                <div class="card-body text-muted text-center">
                    @if (count($cart) === 0)
                        Produk Dipilih Masih Kosong
                    @else
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cart as $index => $item)
                                    <tr>
                                        <td>{{ $item['name'] }}</td>
                                        <td>Rp. {{ number_format($item['price'] ?? 0, 0, ',', '.') }}</td>
                                        <td>{{ $item['quantity'] }}</td>
                                        <td>Rp.
                                            {{ number_format(($item['price'] ?? 0) * $item['quantity'], 0, ',', '.') }}
                                        </td>
                                        <td>
                                            <button class="btn btn-sm "
                                                wire:click="decreaseQuantity({{ $index }})">-</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <button class="btn btn-sm btn-danger mt-2" wire:click="clearCart">Kosongkan Keranjang</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="text-end mt-4">
        <div class="d-flex flex-wrap gap-2 justify-content-start">
            <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-check-circle me-1"></i> Kirim Barang
            </button>
        </div>
    </div>
</div>
