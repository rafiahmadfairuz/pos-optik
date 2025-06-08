<div class="col-md-5">
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
                            <th>Harga</th>
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
                                <td>{{ $product['stock'] }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $product['type'])) }}</td>
                                <td>
                                    <button class="btn"
                                        wire:click="selectProduct({{ $product['id'] }}, '{{ $product['type'] }}')">+</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No products found.</td>
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
                                    <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
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
