<div class="col-md-12">
    <div class="card">
        <div class="card-header fw-bold text-decoration-underline">Choose Product</div>
        <div class="card-body p-2">

            <div class="input-group mb-2">
                <input type="text" class="form-control"
                    placeholder="Cari produk berdasarkan merk, tipe, warna, desain, SPH, CYL, ADD, atau AXIS (bila ada)..."
                    wire:model.defer="searchInput" autocomplete="off">
                <button class="btn btn-primary" wire:click="runSearch">
                    <i class="bi bi-search"></i>
                </button>
                <button class="btn btn-outline-primary" wire:click="resetSearch">
                    <i class="bi bi-x-circle"></i>
                </button>
            </div>

            <div class="table-responsive card-scroll" style="max-height: 300px;">
                <table class="table table-sm table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="">Nama Produk</th>
                            <th class="text-center">Harga Jual</th>
                            @if (in_array(Auth::user()->role, ['admin', 'gudang']))
                                <th class="text-center">Laba</th>
                            @endif
                            <th class="text-center">Stok</th>
                            <th class="text-center">Desain</th>
                            <th class="text-center">Tipe</th>
                            <th class="text-center">Jenis</th>
                            <th class="text-center">Warna</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td class="">
                                    {{ $product['name'] }}

                                    {{-- Info tambahan untuk lensa --}}
                                    @if (in_array($product['type'], ['lensa_finish', 'lensa_khusus']))
                                        <br>
                                        <small class="text-muted">
                                            {{ $product['desain'] ?? 'N/A' }} |
                                            SPH: {{ $product['sph'] ?? 'N/A' }} |
                                            CYL: {{ $product['cyl'] ?? 'N/A' }} |
                                            ADD: {{ $product['add'] ?? 'N/A' }}
                                        </small>
                                    @endif
                                </td>

                                <td class="text-center">
                                    Rp. {{ number_format($product['price'], 0, ',', '.') }}
                                </td>

                                @if (in_array(Auth::user()->role, ['admin', 'gudang']))
                                    <td class="text-center">
                                        Rp. {{ number_format($product['laba'], 0, ',', '.') }}
                                    </td>
                                @endif

                                <td class="text-center">
                                    {{ $product['stock'] }}
                                </td>

                                <td class="text-center">
                                    {{ $product['desain'] ?? 'N/A' }}
                                </td>

                                <td class="text-center">
                                    {{ $product['tipe'] ?? 'N/A' }}
                                </td>
                                <td class="text-center">
                                    {{ $product['type'] ?? 'N/A' }}
                                </td>

                                <td class="text-center">
                                    {{ $product['warna'] ?? 'N/A' }}
                                </td>

                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary"
                                        wire:click="selectProduct({{ $product['id'] }}, '{{ $product['type'] }}')">
                                        +
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada produk ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>


            <!-- pagination tetap sama -->
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
                                    <li class="page-item active"><span class="page-link">{{ $page }}</span>
                                    </li>
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
