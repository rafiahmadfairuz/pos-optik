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
                    <button type="button" class="btn btn-primary px-4 rounded-pill" id="btnAddAccessories">
                        Tambah Data
                    </button>
                </div>

                <div id="formContainer" class="card mb-4 {{ $errors->any() ? '' : 'd-none' }}">
                    <div class="card-body">
                        <form id="accessoriesForm" action="{{ route('create.accessories') }}" method="POST">
                            @csrf
                            <input type="hidden" name="accessories_id" id="accessoriesId" value="">
                            <input type="hidden" name="cabang_id" id="cabang_id" />
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

                                <div class="col-md-6">
                                    <label for="accessoriesHarga" class="form-label">Harga</label>
                                    <input type="number" class="form-control @error('harga') is-invalid @enderror"
                                        id="accessoriesHarga" name="harga" value="{{ old('harga') }}">
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
                    <table class="table table-borderless align-middle" id="accessoryTable">
                        <thead class="table-light">
                            <tr class="align-middle">
                                <th class="py-3 px-4 fw-bold">Nama</th>
                                <th class="py-3 px-4 fw-bold">Jenis</th>
                                <th class="py-3 px-4 fw-bold">Harga</th>
                                <th class="py-3 px-4 fw-bold">Stok</th>
                                <th class="py-3 px-4 fw-bold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($accessories as $acc)
                                <tr data-id="{{ $acc->id }}" data-nama="{{ $acc->nama }}"
                                    data-jenis="{{ $acc->jenis }}" data-harga="{{ $acc->harga }}"
                                    data-stok="{{ $acc->stok }}">
                                    <td>{{ $acc->nama }}</td>
                                    <td>{{ $acc->jenis }}</td>
                                    <td>Rp {{ number_format($acc->harga, 0, ',', '.') }}</td>
                                    <td>{{ $acc->stok }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-edit-accessory" title="Edit Aksesori">
                                            <img src="assets/img/icons/edit.svg" alt="edit">
                                        </button>
                                        <button class="btn btn-sm btn-delete-accessory" title="Delete Aksesori"
                                            data-bs-toggle="modal" data-bs-target="#deleteAccessoryModal">
                                            <img src="assets/img/icons/delete.svg" alt="delete">
                                        </button>
                                    </td>
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
            const accessoriesName = document.getElementById('accessoriesName');
            const accessoriesJenis = document.getElementById('accessoriesjenis');
            const accessoriesHarga = document.getElementById('accessoriesHarga');
            const accessoriesStok = document.getElementById('accessoriesStok');
            const deleteAccessoriesForm = document.getElementById('deleteAccessoriesForm');
            document.getElementById('accessoriesForm').addEventListener('submit', function(e) {
                const cabangId = localStorage.getItem('cabang_id');
                if (!cabangId) {
                    alert('Cabang ID tidak ditemukan di localStorage.');
                    e.preventDefault();
                    return;
                }
                document.getElementById('cabang_id').value = cabangId;
            });

            // Tampilkan form tambah
            btnAddAccessories.addEventListener('click', () => {
                accessoriesForm.action = "{{ route('create.accessories') }}";
                accessoriesForm.querySelector('input[name="_method"]')?.remove(); // hapus _method jika ada
                accessoriesId.value = '';
                accessoriesName.value = '';
                accessoriesJenis.value = '';
                accessoriesHarga.value = '';
                accessoriesStok.value = '';
                formContainer.classList.remove('d-none');
            });

            // Cancel form
            btnCancel.addEventListener('click', () => {
                formContainer.classList.add('d-none');
                accessoriesForm.reset();
                accessoriesId.value = '';
            });

            // Tombol edit
            document.querySelectorAll('.btn-edit-accessory').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const tr = this.closest('tr');
                    if (!tr) return;

                    const id = tr.dataset.id;
                    const name = tr.dataset.nama;
                    const type = tr.dataset.jenis;
                    const price = tr.dataset.harga;
                    const stock = tr.dataset.stok;

                    accessoriesForm.action = `/accesories/${id}`;

                    let methodInput = accessoriesForm.querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        accessoriesForm.appendChild(methodInput);
                    }
                    methodInput.value = 'PUT';

                    accessoriesId.value = id;
                    accessoriesName.value = name;
                    accessoriesJenis.value = type;
                    accessoriesHarga.value = price;
                    accessoriesStok.value = stock;
                    formContainer.classList.remove('d-none');
                    accessoriesName.focus();
                });
            });

            // Tombol delete
            document.querySelectorAll('.btn-delete-accessory').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const tr = this.closest('tr');
                    const id = tr.dataset.id;

                    deleteAccessoriesForm.action = `/accesories/${id}`;
                    const methodInput = deleteAccessoriesForm.querySelector(
                        'input[name="_method"]');
                    if (methodInput) {
                        methodInput.value = 'DELETE';
                    }

                    const deleteModal = new bootstrap.Modal(document.getElementById(
                        'deleteAccessoriesModal'));
                    deleteModal.show();
                });
            });
        });
    </script>


</x-app>
