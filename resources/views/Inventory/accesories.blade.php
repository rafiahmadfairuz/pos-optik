<x-app>
    @section('title', 'Aksesoris')

    <div class="container-fluid">
        <div class="page-header">
            <div class="page-title">
                <h1 class="fw-bold">Aksesoris Optik</h1>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body py-5 px-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">List Aksesoris</h5>
                    @auth
                        @if (in_array(Auth::user()->role, ['admin', 'gudang_utama']))
                            <button type="button" class="btn btn-primary px-4 rounded-pill" id="btnAddAccessories">
                                Tambah Data
                            </button>
                        @endif
                    @endauth
                </div>

                <div id="formContainer" class="card mb-4 {{ $errors->any() ? '' : 'd-none' }}">
                    <div class="card-body">
                        <form id="accessoriesForm" action="" method="POST">
                            @csrf
                            <input type="hidden" name="accessories_id" id="accessoriesId" value="">
                            <div class="col-md-6">
                                <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('sku') is-invalid @enderror"
                                    id="accessoriesSku" name="sku" value="{{ old('sku') }}" required>
                                @error('sku')
                                    <div class="invalid-feedback d-flex align-items-center mt-1" style="display: block;">
                                        <i class="bi bi-exclamation-circle-fill me-2"></i><span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="accessoriesName" class="form-label">Nama <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                        id="accessoriesName" name="nama" value="{{ old('nama') }}" required>
                                    @error('nama')
                                        <div class="invalid-feedback d-flex align-items-center mt-1"
                                            style="display: block;">
                                            <i
                                                class="bi bi-exclamation-circle-fill me-2"></i><span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="accessoriesJenis" class="form-label">Jenis</label>
                                    <input type="text" class="form-control @error('jenis') is-invalid @enderror"
                                        id="accessoriesjenis" name="jenis" value="{{ old('jenis') }}">
                                    @error('jenis')
                                        <div class="invalid-feedback d-flex align-items-center mt-1"
                                            style="display: block;">
                                            <i
                                                class="bi bi-exclamation-circle-fill me-2"></i><span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                @if (in_array(Auth::user()->role, ['admin', 'gudang_utama']))
                                    <div class="col-md-3">
                                        <label for="harga_beli" class="form-label">Harga Beli</label>
                                        <input type="number" step="any"
                                            class="form-control @error('harga_beli') is-invalid @enderror"
                                            id="accessoriesHargaBeli" name="harga_beli" value="{{ old('harga_beli') }}">
                                        @error('harga_beli')
                                            <div class="invalid-feedback d-flex align-items-center mt-1"
                                                style="display: block;">
                                                <i
                                                    class="bi bi-exclamation-circle-fill me-2"></i><span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                @endif

                                <div class="col-md-6">
                                    <label for="accessoriesHarga" class="form-label">Harga Jual</label>
                                    <input type="number" step="any"
                                        class="form-control @error('harga') is-invalid @enderror" id="accessoriesHarga"
                                        name="harga" value="{{ old('harga') }}">
                                    @error('harga')
                                        <div class="invalid-feedback d-flex align-items-center mt-1"
                                            style="display: block;">
                                            <i
                                                class="bi bi-exclamation-circle-fill me-2"></i><span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="accessoriesStok" class="form-label">Stok</label>
                                    <input type="number" class="form-control @error('stok') is-invalid @enderror"
                                        id="accessoriesStok" name="stok" value="{{ old('stok') }}">
                                    @error('stok')
                                        <div class="invalid-feedback d-flex align-items-center mt-1"
                                            style="display: block;">
                                            <i
                                                class="bi bi-exclamation-circle-fill me-2"></i><span>{{ $message }}</span>
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

                <hr class="mb-4 mt-0" style="border-top: 2px solid #dee2e6;">

                <div class="table-responsive mt-3">
                    <form method="GET" action="{{ route('accessories.index') }}" class="input-group mb-4">
                        <input type="text" class="form-control" name="search"
                            placeholder="Search by Nama or Jenis..." value="{{ request('search') }}"
                            autocomplete="off">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                        <a href="{{ route('accessories.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    </form>
                    <table class="table table-borderless align-middle" id="accessoryTable">
                        <thead class="table-light">
                            <tr class="align-middle">
                                <th class="py-3 px-4 fw-bold">SKU</th>
                                <th class="py-3 px-4 fw-bold">Nama</th>
                                <th class="py-3 px-4 fw-bold">Jenis</th>
                                @if (in_array(Auth::user()->role, ['admin', 'gudang_utama']))
                                    <th class="py-3 px-4 fw-bold">Harga Beli</th>
                                @endif
                                <th class="py-3 px-4 fw-bold">Harga Jual</th>
                                <th class="py-3 px-4 fw-bold text-center">Stok</th>
                                @if (in_array(Auth::user()->role, ['admin', 'gudang_utama']))
                                    <th class="py-3 px-4 fw-bold">Laba</th>
                                @endif
                                @if (in_array(Auth::user()->role, ['admin', 'gudang_utama']))
                                    <th class="py-3 px-4 fw-bold">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($accessories as $acc)
                                <tr data-id="{{ $acc->id }}" data-sku="{{ $acc->sku }}"
                                    data-nama="{{ $acc->nama }}" data-jenis="{{ $acc->jenis }}"
                                    data-harga="{{ $acc->harga }}"
                                    @if (in_array(Auth::user()->role, ['admin', 'gudang_utama'])) data-harga_beli="{{ $acc->harga_beli }}" @endif
                                    data-stok="{{ $acc->stok }}">
                                    <td>{{ $acc->sku }}</td>
                                    <td>{{ $acc->nama }}</td>
                                    <td>{{ $acc->jenis }}</td>
                                    @if (in_array(Auth::user()->role, ['admin', 'gudang_utama']))
                                        <td>Rp {{ number_format($acc->harga_beli, 0, ',', '.') }}</td>
                                    @endif
                                    <td>Rp {{ number_format($acc->harga, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $acc->stok }}</td>
                                    @if (in_array(Auth::user()->role, ['admin', 'gudang_utama']))
                                        <td>Rp {{ number_format($acc->laba, 0, ',', '.') }}</td>
                                    @endif
                                    @if (in_array(Auth::user()->role, ['admin', 'gudang_utama']))
                                        <td>
                                            <button class="btn btn-sm btn-edit-accessory" title="Edit Aksesori">
                                                <img src="/assets/img/icons/edit.svg" alt="edit">
                                            </button>
                                            <button class="btn btn-sm btn-delete-accessory" title="Delete Aksesori"
                                                data-bs-toggle="modal" data-bs-target="#deleteAccessoryModal">
                                                <img src="/assets/img/icons/delete.svg" alt="delete">
                                            </button>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>

            </div>
        </div>
    </div>


    <div class="modal fade" id="deleteAccessoriesModal" tabindex="-1" aria-labelledby="deleteAccessoriesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <form id="deleteAccessoriesForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-content rounded-4 shadow-sm border-0">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-semibold " id="deleteAccessoriesModalLabel">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body fs-5 pt-2 pb-3 text-center">
                        Are you sure you want to delete this Accessories?
                    </div>
                    <div class="modal-footer border-0 justify-content-center gap-3 pt-0">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger rounded-pill px-4">Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const formContainer = document.getElementById('formContainer');
            const btnAddAccessories = document.getElementById('btnAddAccessories');
            const btnCancel = document.getElementById('btnCancel');
            const accessoriesForm = document.getElementById('accessoriesForm');
            const accessoriesId = document.getElementById('accessoriesId');
            const deleteAccessoriesForm = document.getElementById('deleteAccessoriesForm');

            const accessoriesName = document.getElementById('accessoriesName');
            const accessoriesSku = document.getElementById('accessoriesSku');
            const accessoriesJenis = document.getElementById('accessoriesjenis');
            const accessoriesHarga = document.getElementById('accessoriesHarga');
            const accessoriesStok = document.getElementById('accessoriesStok');
            const accessoriesHargaBeli = document.getElementById('accessoriesHargaBeli');

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

            if (btnAddAccessories) {
                btnAddAccessories.addEventListener('click', () => {
                    accessoriesForm.action = "{{ route('accessories.store') }}";
                    accessoriesForm.querySelector('input[name="_method"]')?.remove();
                    accessoriesForm.reset();
                    accessoriesId.value = '';
                    formContainer.classList.remove('d-none');
                    accessoriesName.focus();
                    btnAddAccessories.classList.add('d-none');
                });
            }

            btnCancel.addEventListener('click', () => {
                formContainer.classList.add('d-none');
                accessoriesForm.reset();
                accessoriesId.value = '';
                btnAddAccessories.classList.remove('d-none');
            });

            document.querySelectorAll('.btn-edit-accessory').forEach(btn => {
                btn.addEventListener('click', () => {
                    const tr = btn.closest('tr');
                    const id = tr.dataset.id;

                    accessoriesForm.action = `/accessories/${id}`;
                    setFormMethod(accessoriesForm, 'PUT');

                    accessoriesId.value = id;
                    accessoriesSku.value = tr.dataset.sku;
                    accessoriesName.value = tr.dataset.nama;
                    accessoriesJenis.value = tr.dataset.jenis;
                    if (accessoriesHargaBeli) accessoriesHargaBeli.value = tr.dataset.harga_beli;
                    accessoriesHarga.value = tr.dataset.harga;
                    accessoriesStok.value = tr.dataset.stok;

                    formContainer.classList.remove('d-none');
                    accessoriesName.focus();
                    btnAddAccessories.classList.add('d-none');
                });
            });

            document.querySelectorAll('.btn-delete-accessory').forEach(btn => {
                btn.addEventListener('click', () => {
                    const tr = btn.closest('tr');
                    const id = tr.dataset.id;
                    deleteAccessoriesForm.action = `/accessories/${id}`;
                    setFormMethod(deleteAccessoriesForm, 'DELETE');
                    deleteAccessoriesForm.method = 'POST';

                    const deleteModal = new bootstrap.Modal(document.getElementById(
                        'deleteAccessoriesModal'));
                    deleteModal.show();
                });
            });

            const hasFormError = {{ $errors->any() ? 'true' : 'false' }};
            if (hasFormError) {
                formContainer.classList.remove('d-none');
                btnAddAccessories.classList.add('d-none');
            }
        });
    </script>

</x-app>
