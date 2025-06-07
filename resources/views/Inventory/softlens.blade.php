<x-app>
    @section('title', 'Softlens')

    <div class="container-fluid">
        <div class="page-header">
            <div class="page-title">
                <h1 class="fw-bold">Softlens Optik</h1>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body py-5 px-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">List Softlens</h5>
                    <button type="button" class="btn btn-primary px-4 rounded-pill" id="btnAddSoftlens">
                        Tambah Data
                    </button>
                </div>

                <div id="formContainer" class="card mb-4 {{ $errors->any() ? '' : 'd-none' }}">
                    <div class="card-body">
                        <form id="softlensForm" action="" method="POST">
                            @csrf
                            <input type="hidden" name="softlens_id" id="softlensId" value="">
                            <input type="hidden" name="cabang_id" id="cabang_id" />

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="merk" class="form-label">Merk <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('merk') is-invalid @enderror"
                                        id="merk" name="merk" value="{{ old('merk') }}" required>
                                    @error('merk')
                                        <div class="invalid-feedback d-flex align-items-center mt-1"
                                            style="display: block;">
                                            <i
                                                class="bi bi-exclamation-circle-fill me-2"></i><span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="tipe" class="form-label">Tipe</label>
                                    <input type="text" class="form-control @error('tipe') is-invalid @enderror"
                                        id="tipe" name="tipe" value="{{ old('tipe') }}">
                                    @error('tipe')
                                        <div class="invalid-feedback d-flex align-items-center mt-1"
                                            style="display: block;">
                                            <i
                                                class="bi bi-exclamation-circle-fill me-2"></i><span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="warna" class="form-label">Warna</label>
                                    <input type="text" class="form-control @error('warna') is-invalid @enderror"
                                        id="warna" name="warna" value="{{ old('warna') }}">
                                    @error('warna')
                                        <div class="invalid-feedback d-flex align-items-center mt-1"
                                            style="display: block;">
                                            <i
                                                class="bi bi-exclamation-circle-fill me-2"></i><span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="harga" class="form-label">Harga</label>
                                    <input type="number" class="form-control @error('harga') is-invalid @enderror"
                                        id="harga" name="harga" value="{{ old('harga') }}">
                                    @error('harga')
                                        <div class="invalid-feedback d-flex align-items-center mt-1"
                                            style="display: block;">
                                            <i
                                                class="bi bi-exclamation-circle-fill me-2"></i><span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="stok" class="form-label">Stok</label>
                                    <input type="number" class="form-control @error('stok') is-invalid @enderror"
                                        id="stok" name="stok" value="{{ old('stok') }}">
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
                    <table class="table table-borderless align-middle" id="softlensTable">
                        <thead class="table-light">
                            <tr>
                                <th>Merk</th>
                                <th>Tipe</th>
                                <th>Warna</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($softlens as $item)
                                <tr data-id="{{ $item->id }}" data-merk="{{ $item->merk }}"
                                    data-tipe="{{ $item->tipe }}" data-warna="{{ $item->warna }}"
                                    data-harga="{{ $item->harga }}" data-stok="{{ $item->stok }}">
                                    <td>{{ $item->merk }}</td>
                                    <td>{{ $item->tipe }}</td>
                                    <td>{{ $item->warna }}</td>
                                    <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                    <td>{{ $item->stok }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-edit-softlens" title="Edit Softlens"><img
                                                src="/assets/img/icons/edit.svg" alt="edit"></button>
                                        <button class="btn btn-sm btn-delete-softlens" data-bs-toggle="modal"
                                            data-bs-target="#deleteSoftlensModal"><img
                                                src="/assets/img/icons/delete.svg" alt="delete"></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteSoftlensModal" tabindex="-1" aria-labelledby="deleteSoftlensModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <form id="deleteSoftlensForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-content rounded-4 shadow-sm border-0">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-semibold">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body fs-5 pt-2 pb-3 text-center">
                        Yakin ingin menghapus softlens ini?
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
            const btnAdd = document.getElementById('btnAddSoftlens');
            const btnCancel = document.getElementById('btnCancel');
            const form = document.getElementById('softlensForm');
            const idInput = document.getElementById('softlensId');
            const cabangInput = document.getElementById('cabang_id');
            const deleteForm = document.getElementById('deleteSoftlensForm');

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

            btnAdd.addEventListener('click', () => {
                form.action = "{{ route('softlens.store') }}";
                form.querySelector('input[name="_method"]')?.remove();
                form.reset();
                idInput.value = '';
                formContainer.classList.remove('d-none');
            });

            btnCancel.addEventListener('click', () => {
                formContainer.classList.add('d-none');
                form.reset();
                idInput.value = '';
            });

            document.querySelectorAll('.btn-edit-softlens').forEach(btn => {
                btn.addEventListener('click', () => {
                    const tr = btn.closest('tr');
                    const id = tr.dataset.id;

                    form.action = `/softlens/${id}`;
                    setFormMethod(form, 'PUT');

                    idInput.value = id;
                    form.merk.value = tr.dataset.merk;
                    form.tipe.value = tr.dataset.tipe;
                    form.warna.value = tr.dataset.warna;
                    form.harga.value = tr.dataset.harga;
                    form.stok.value = tr.dataset.stok;

                    formContainer.classList.remove('d-none');
                    form.merk.focus();
                });
            });

            document.querySelectorAll('.btn-delete-softlens').forEach(btn => {
                btn.addEventListener('click', () => {
                    const tr = btn.closest('tr');
                    const id = tr.dataset.id;
                    deleteForm.action = `/softlens/${id}`;
                    setFormMethod(deleteForm, 'DELETE');
                    deleteForm.method = 'POST';
                });
            });

            form.addEventListener('submit', (e) => {
                const cabangId = localStorage.getItem('cabang_id');
                if (!cabangId) {
                    alert("Cabang ID tidak ditemukan.");
                    e.preventDefault();
                    return;
                }
                cabangInput.value = cabangId;
            });
        });
    </script>

</x-app>
