<x-app>
    @section('title', 'Asuransi')

    <div class="container-fluid">
        <div class="page-header">
            <div class="page-title">
                <h1 class="fw-bold">Asuransi</h1>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body py-5 px-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">List Asuransi</h5>
                    <button type="button" class="btn btn-primary px-4 rounded-pill" id="btnAddInsurance">
                        Tambah Data
                    </button>
                </div>

                <div id="formContainer" class="card mb-4 {{ $errors->any() ? '' : 'd-none' }}">
                    <div class="card-body">
                        <form id="insuranceForm" action="" method="POST">
                            @csrf
                            <input type="hidden" name="insurance_id" id="insuranceId" value="">
                            <input type="hidden" name="cabang_id" id="cabang_id" />

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nama" class="form-label">Nama <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                        id="nama" name="nama" value="{{ old('nama') }}" required>
                                    @error('nama')
                                        <div class="invalid-feedback d-flex align-items-center mt-1"
                                            style="display: block;">
                                            <i
                                                class="bi bi-exclamation-circle-fill me-2"></i><span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="nominal" class="form-label">Nominal <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('nominal') is-invalid @enderror"
                                        id="nominal" name="nominal" value="{{ old('nominal') }}" required>
                                    @error('nominal')
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
                    <table class="table table-borderless align-middle" id="insuranceTable">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>Nominal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($asuransi as $item)
                                <tr data-id="{{ $item->id }}" data-nama="{{ $item->nama }}"
                                    data-nominal="{{ $item->nominal }}">
                                    <td>{{ $item->nama }}</td>
                                    <td>Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-edit-insurance text-primary" title="Edit">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <button class="btn btn-sm btn-delete-insurance text-danger"
                                            data-bs-toggle="modal" data-bs-target="#deleteInsuranceModal"
                                            title="Delete">
                                            <i class="bi bi-trash-fill"></i>
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

    <div class="modal fade" id="deleteInsuranceModal" tabindex="-1" aria-labelledby="deleteInsuranceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <form id="deleteInsuranceForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-content rounded-4 shadow-sm border-0">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-semibold">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body fs-5 pt-2 pb-3 text-center">
                        Yakin ingin menghapus asuransi ini?
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
            const btnAdd = document.getElementById('btnAddInsurance');
            const btnCancel = document.getElementById('btnCancel');
            const form = document.getElementById('insuranceForm');
            const idInput = document.getElementById('insuranceId');
            const cabangInput = document.getElementById('cabang_id');
            const deleteForm = document.getElementById('deleteInsuranceForm');

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
                form.action = "{{ route('asuransi.store') }}";
                form.querySelector('input[name="_method"]')?.remove();
                form.reset();
                idInput.value = '';
                formContainer.classList.remove('d-none');
                form.nama.focus();
                btnAdd.classList.add('d-none');
            });

            btnCancel.addEventListener('click', () => {
                formContainer.classList.add('d-none');
                form.reset();
                idInput.value = '';
                btnAdd.classList.remove('d-none');
            });

            document.querySelectorAll('.btn-edit-insurance').forEach(btn => {
                btn.addEventListener('click', () => {
                    const tr = btn.closest('tr');
                    const id = tr.dataset.id;
                    form.action = `/asuransi/${id}`;
                    setFormMethod(form, 'PUT');
                    idInput.value = id;
                    form.nama.value = tr.dataset.nama;
                    form.nominal.value = tr.dataset.nominal;
                    formContainer.classList.remove('d-none');
                    form.nama.focus();
                    btnAdd.classList.add('d-none');
                });
            });

            document.querySelectorAll('.btn-delete-insurance').forEach(btn => {
                btn.addEventListener('click', () => {
                    const tr = btn.closest('tr');
                    const id = tr.dataset.id;
                    deleteForm.action = `/asuransi/${id}`;
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
