<div class="container-fluid py-3">
    <h2 class="fw-bold "><i class="bi bi-cash-stack me-2"></i>Beli Barang</h2>
    <div id="formContainer" class="card mb-4 {{ $errors->any() ? '' : 'd-none' }}">
        <div class="card-body">
            <form id="supplierForm" action="" method="POST">
                @csrf
                <input type="hidden" name="supplier_id" id="supplierId" value="">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="supplierName" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="supplierName"
                            name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback d-flex align-items-center mt-1" style="display: block;">
                                <i class="bi bi-exclamation-circle-fill me-2"></i><span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="supplierEmail" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                            id="supplierEmail" name="email" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback d-flex align-items-center mt-1" style="display: block;">
                                <i class="bi bi-exclamation-circle-fill me-2"></i><span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="supplierPhone" class="form-label">Phone</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                            id="supplierPhone" name="phone" value="{{ old('phone') }}">
                        @error('phone')
                            <div class="invalid-feedback d-flex align-items-center mt-1" style="display: block;">
                                <i class="bi bi-exclamation-circle-fill me-2"></i><span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <button type="button" id="btnCancel" class="btn btn-secondary px-4">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Save</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Informasi Supplier --}}
    <div class="row g-3 align-items-stretch">
        <div class="col-lg-5 col-md-12">
            <div class="card shadow-sm h-100 border-0 rounded-3">
                <div class="card-header bg-white fw-semibold border-bottom d-flex align-items-center">
                    <i class="bi bi-person fs-3 me-2"></i>
                    Supplier Information
                </div>
                <div class="card-body py-4" style="min-height: 160px;">
                    @if ($supplier)
                        <div class="d-flex align-items-center mb-3 text-truncate" title="{{ $supplier['name'] }}">
                            <i class="bi bi-person-fill fs-4 text-primary me-3"></i>
                            <div>
                                <div class="text-muted small">Name</div>
                                <strong class="fs-5">{{ $supplier['name'] }}</strong>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3 text-truncate" title="{{ $supplier['phone'] }}">
                            <i class="bi bi-telephone-fill fs-4 text-success me-3"></i>
                            <div>
                                <div class="text-muted small">Phone</div>
                                <span class="fs-5">{{ $supplier['phone'] }}</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center text-truncate" title="{{ $supplier['email'] }}">
                            <i class="bi bi-envelope-fill fs-4 text-warning me-3"></i>
                            <div>
                                <div class="text-muted small">Email</div>
                                <span class="fs-5">{{ $supplier['email'] }}</span>
                            </div>
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-info-circle display-5 d-block mb-3"></i>
                            <small>Please select supplier first</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Pilih Supplier --}}
        <div class="col-lg-7 col-md-12">
            <div class="card shadow-sm h-100">
                <div
                    class="card-header fw-semibold d-flex justify-content-between align-items-center bg-white border-bottom">
                    <div><i class="bi bi-search me-2"></i>Choose Supplier</div>
                    {{-- <button type="button" class="btn btn-primary px-4 rounded-pill" id="btnAddSupplier">
                        Tambah Data
                    </button> --}}
                </div>
                <div class="card-body p-3 position-relative">
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


                    <div wire:loading.flex wire:target="selectSupplier"
                        class="position-absolute top-50 start-50 translate-middle z-10 align-items-center justify-content-center">
                        <div class="spinner-border text-primary" role="status"><span
                                class="visually-hidden">Loading...</span></div>
                    </div>



                    <div wire:loading.class="opacity-50" wire:target="selectSupplier"
                        class="table-responsive card-scroll">
                        <table class="table table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($suppliers as $supplier)
                                    <tr>
                                        <td>{{ $supplier->name }}</td>
                                        <td>{{ $supplier->phone }}</td>
                                        <td>{{ $supplier->email }}</td>
                                        <td><button wire:click="selectSupplier({{ $supplier->id }})" class="btn"
                                                wire:loading.attr="disabled">+</button></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada supplier ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-3 d-flex justify-content-end">
                            @if ($suppliers->hasPages())
                                <nav>
                                    <ul class="pagination pagination-sm mb-0">
                                        {{-- Previous Page Link --}}
                                        @if ($suppliers->onFirstPage())
                                            <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                                        @else
                                            <li class="page-item">
                                                <a href="#"
                                                    wire:click.prevent="gotoPage({{ $suppliers->currentPage() - 1 }})"
                                                    class="page-link">&laquo;</a>
                                            </li>
                                        @endif

                                        {{-- Pagination Elements --}}
                                        @foreach ($suppliers->links()->elements[0] as $page => $url)
                                            @if ($page == $suppliers->currentPage())
                                                <li class="page-item active"><span
                                                        class="page-link">{{ $page }}</span></li>
                                            @else
                                                <li class="page-item">
                                                    <a href="#"
                                                        wire:click.prevent="gotoPage({{ $page }})"
                                                        class="page-link">{{ $page }}</a>
                                                </li>
                                            @endif
                                        @endforeach

                                        {{-- Next Page Link --}}
                                        @if ($suppliers->hasMorePages())
                                            <li class="page-item">
                                                <a href="#"
                                                    wire:click.prevent="gotoPage({{ $suppliers->currentPage() + 1 }})"
                                                    class="page-link">&raquo;</a>
                                            </li>
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
        </div>
    </div>

    {{-- Pilih Produk --}}
    <div class="col-md-12 mt-3">
        <div class="card">
            <div class="card-header fw-bold text-decoration-underline">Choose Product</div>
            <div class="card-body p-2">
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

    {{-- Cart dan Bill Summary --}}
    <div class="row mt-3">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header fw-bold text-decoration-underline">Shopping Cart</div>
                <div class="card-body text-muted text-center">
                    @if (count($cart) === 0)
                        Your cart is empty
                    @else
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Harga Beli</th>
                                    <th>Harga Jual</th>
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
                                        <td>Rp. {{ number_format($item['harga_jual'] ?? 0, 0, ',', '.') }}</td>
                                        <td>{{ $item['quantity'] }}</td>
                                        <td>Rp.
                                            {{ number_format(($item['price'] ?? 0) * $item['quantity'], 0, ',', '.') }}
                                        </td>
                                        <td>
                                            <button class="btn btn-sm"
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

        {{-- Bill Summary --}}
        <div class="col-md-4">
            <div class="card mb-4 shadow rounded-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-decoration-underline"><i class="bi bi-receipt-cutoff me-2"></i>Bill
                        Summary</span>
                    <small class="text-muted">{{ date('Y/m/d') }}</small>
                </div>
                <div class="card-body fs-5">
                    <p>
                        <i class="bi bi-cash-stack me-2"></i>
                        Total:
                        <strong class="float-end">Rp. {{ number_format($total, 2, ',', '.') }}</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Input Surat Jalan & Tanggal Pemesanan --}}
    <div class="card mb-4">
        <div class="card-header fw-bold text-decoration-underline">Detail Tambahan</div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="suratJalan" class="form-label">Surat Jalan</label>
                    <input type="text" wire:model.lazy="surat_jalan" id="suratJalan"
                        class="form-control @error('surat_jalan') is-invalid @enderror">
                    @error('surat_jalan')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="tanggalPemesanan" class="form-label">Tanggal Pemesanan</label>
                    <input type="date" wire:model.lazy="tanggal_pemesanan" id="tanggalPemesanan"
                        class="form-control @error('tanggal_pemesanan') is-invalid @enderror">
                    @error('tanggal_pemesanan')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="text-end mt-4">
        <div class="d-flex flex-wrap gap-2 justify-content-start">
            <button wire:click='submit' class="btn btn-primary px-4">
                <i class="bi bi-check-circle me-1"></i> Selesai
            </button>
        </div>
    </div>
</div>


<script>
    function formatRupiah(el) {
        let value = el.value.replace(/[^\d,]/g, '');
        let parts = value.split(',');
        let number = parts[0];
        let formatted = number.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        el.value = parts.length > 1 ? formatted + ',' + parts[1] : formatted;
    }
</script>


<script>
    const formContainer = document.getElementById('formContainer');
    const btnAddCustomer = document.getElementById('btnAddCustomer');
    const btnCancel = document.getElementById('btnCancel');
    const customerForm = document.getElementById('customerForm');
    const customerIdInput = document.getElementById('customerId');
    const customerNameInput = document.getElementById('customerName');
    const customerEmailInput = document.getElementById('customerEmail');
    const customerPhoneInput = document.getElementById('customerPhone');

    function setFormMethod(form, method) {
        let methodInput = form.querySelector('input[name="_method"]');
        if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            form.appendChild(methodInput);
        }
        methodInput.value = method;
    }

    btnAddCustomer.addEventListener('click', () => {
        customerForm.action = "{{ route('customer.store') }}";
        customerForm.querySelector('input[name="_method"]')?.remove();
        customerIdInput.value = '';
        customerForm.reset();
        formContainer.classList.remove('d-none');
    });

    btnCancel.addEventListener('click', () => {
        formContainer.classList.add('d-none');
        customerForm.reset();
        customerIdInput.value = '';
    });

    const hasFormError = {{ $errors->any() ? 'true' : 'false' }};
    if (hasFormError) {
        formContainer.classList.remove('d-none');
    }
</script>
