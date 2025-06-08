<x-app>
    @section('title', 'Lensa Finish')

    <div class="container-fluid">
        <div class="page-header">
            <div class="page-title">
                <h1 class="fw-bold">Lensa Finish</h1>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body py-5 px-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">List Lensa Finish</h5>
                    @auth
                        @if (in_array(Auth::user()->role, ['admin', 'cabang']))
                            <button type="button" class="btn btn-primary px-4 rounded-pill" id="btnAddLensa">
                                Tambah Data
                            </button>
                        @endif
                    @endauth

                </div>

                <div id="formContainer" class="card mb-4 {{ $errors->any() ? '' : 'd-none' }}">
                    <div class="card-body">
                        <form id="lensaForm" action="" method="POST">
                            @csrf
                            <input type="hidden" name="lensa_id" id="lensaId" value="">

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
                                    <label for="desain" class="form-label">Desain</label>
                                    <input type="text" class="form-control @error('desain') is-invalid @enderror"
                                        id="desain" name="desain" value="{{ old('desain') }}">
                                    @error('desain')
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

                                <div class="col-md-3">
                                    <label for="sph" class="form-label">SPH</label>
                                    <input type="text" class="form-control @error('sph') is-invalid @enderror"
                                        id="sph" name="sph" value="{{ old('sph') }}">
                                    @error('sph')
                                        <div class="invalid-feedback d-flex align-items-center mt-1"
                                            style="display: block;">
                                            <i
                                                class="bi bi-exclamation-circle-fill me-2"></i><span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="cyl" class="form-label">CYL</label>
                                    <input type="text" class="form-control @error('cyl') is-invalid @enderror"
                                        id="cyl" name="cyl" value="{{ old('cyl') }}">
                                    @error('cyl')
                                        <div class="invalid-feedback d-flex align-items-center mt-1"
                                            style="display: block;">
                                            <i
                                                class="bi bi-exclamation-circle-fill me-2"></i><span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="add" class="form-label">ADD</label>
                                    <input type="text" class="form-control @error('add') is-invalid @enderror"
                                        id="add" name="add" value="{{ old('add') }}">
                                    @error('add')
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
                    <table class="table table-borderless align-middle" id="lensaTable">
                        <thead class="table-light">
                            <tr>
                                <th>Merk</th>
                                <th>Desain</th>
                                <th>Type</th>
                                <th>SPH</th>
                                <th>CYL</th>
                                <th>ADD</th>
                                <th>Stok</th>
                                <th>Harga</th>
                                @auth
                                    @if (in_array(Auth::user()->role, ['admin', 'cabang']))
                                        <th>Aksi</th>
                                    @endif
                                @endauth

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lensaFinish as $item)
                                <tr data-id="{{ $item->id }}" data-merk="{{ $item->merk }}"
                                    data-desain="{{ $item->desain }}" data-type="{{ $item->tipe }}"
                                    data-sph="{{ $item->sph }}" data-cyl="{{ $item->cyl }}"
                                    data-add="{{ $item->add }}" data-stok="{{ $item->stok }}"
                                    data-harga="{{ $item->harga }}">
                                    <td>{{ $item->merk }}</td>
                                    <td>{{ $item->desain }}</td>
                                    <td>{{ $item->tipe }}</td>
                                    <td>{{ $item->sph }}</td>
                                    <td>{{ $item->cyl }}</td>
                                    <td>{{ $item->add }}</td>
                                    <td>{{ $item->stok }}</td>
                                    <td>{{ $item->harga }}</td>
                                    @auth
                                        @if (in_array(Auth::user()->role, ['admin', 'cabang']))
                                            <td>
                                                <button class="btn btn-sm btn-edit-lensa" title="Edit Lensa"><i
                                                        class="bi bi-pencil-fill"></i></button>
                                                <button class="btn btn-sm btn-delete-lensa text-danger"
                                                    data-bs-toggle="modal" data-bs-target="#deleteLensaModal"><i
                                                        class="bi bi-trash-fill"></i></button>
                                            </td>
                                        @endif
                                    @endauth

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteLensaModal" tabindex="-1" aria-labelledby="deleteLensaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <form id="deleteLensaForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-content rounded-4 shadow-sm border-0">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-semibold">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body fs-5 pt-2 pb-3 text-center">
                        Yakin ingin menghapus data lensa ini?
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
            const btnAdd = document.getElementById('btnAddLensa');
            const btnCancel = document.getElementById('btnCancel');
            const form = document.getElementById('lensaForm');
            const idInput = document.getElementById('lensaId');
            const cabangInput = document.getElementById('cabang_id');
            const deleteForm = document.getElementById('deleteLensaForm');

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
                form.action = "{{ route('lensaFinish.store') }}";
                form.querySelector('input[name="_method"]')?.remove();
                form.reset();
                idInput.value = '';
                formContainer.classList.remove('d-none');
                form.merk.focus();
                btnAdd.classList.add('d-none');
            });

            btnCancel.addEventListener('click', () => {
                formContainer.classList.add('d-none');
                form.reset();
                idInput.value = '';
                btnAdd.classList.remove('d-none');
            });

            document.querySelectorAll('.btn-edit-lensa').forEach(btn => {
                btn.addEventListener('click', () => {
                    const tr = btn.closest('tr');
                    const id = tr.dataset.id;
                    form.action = `/lensaFinish/${id}`;
                    setFormMethod(form, 'PUT');
                    idInput.value = id;
                    form.merk.value = tr.dataset.merk;
                    form.desain.value = tr.dataset.desain;
                    form.tipe.value = tr.dataset.tipe;
                    form.sph.value = tr.dataset.sph;
                    form.cyl.value = tr.dataset.cyl;
                    form.add.value = tr.dataset.add;
                    form.stok.value = tr.dataset.stok;
                    form.harga.value = tr.dataset.harga;
                    formContainer.classList.remove('d-none');
                    form.merk.focus();
                    btnAdd.classList.add('d-none');
                });
            });

            document.querySelectorAll('.btn-delete-lensa').forEach(btn => {
                btn.addEventListener('click', () => {
                    const tr = btn.closest('tr');
                    const id = tr.dataset.id;
                    deleteForm.action = `/lensaFinish/${id}`;
                    setFormMethod(deleteForm, 'DELETE');
                    deleteForm.method = 'POST';
                });
            });


        });
    </script>


</x-app>
