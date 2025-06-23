<x-app>
    @section('title', 'Supplier')

    <div class="page-header d-flex justify-content-between align-items-center mb-3">
        <div class="page-title">
            <h4>Supplier List</h4>
            <h6>Manage your Supplier</h6>
        </div>
        <div class="page-btn">
            <button id="btnAddSupplier" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i> Add Supplier
            </button>
        </div>
    </div>

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

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($suppliers as $supplier)
                            <tr data-id="{{ $supplier->id }}" data-name="{{ $supplier->name }}"
                                data-email="{{ $supplier->email }}" data-phone="{{ $supplier->phone }}">
                                <td>{{ $supplier->name }}</td>
                                <td>{{ $supplier->email }}</td>
                                <td>{{ $supplier->phone }}</td>
                                <td>
                                    <a class="me-2" href="{{ route('supplier.show', $supplier->id) }}">
                                        <i class="bi bi-person fs-4 align-middle"></i>
                                    </a>
                                    <button class="btn btn-sm btn-edit" title="Edit Supplier">
                                        <img src="assets/img/icons/edit.svg" alt="edit">
                                    </button>
                                    <button class="btn btn-sm btn-delete" title="Delete Supplier" data-bs-toggle="modal"
                                        data-bs-target="#deleteSupplierModal">
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

    <div class="modal fade" id="deleteSupplierModal" tabindex="-1" aria-labelledby="deleteSupplierModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <form id="deleteSupplierForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-content rounded-4 shadow-sm border-0">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-semibold " id="deleteSupplierModalLabel">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body fs-5 pt-2 pb-3 text-center">
                        Are you sure you want to delete this supplier?
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
            const btnAddSupplier = document.getElementById('btnAddSupplier');
            const btnCancel = document.getElementById('btnCancel');
            const supplierForm = document.getElementById('supplierForm');
            const supplierIdInput = document.getElementById('supplierId');
            const supplierNameInput = document.getElementById('supplierName');
            const supplierEmailInput = document.getElementById('supplierEmail');
            const supplierPhoneInput = document.getElementById('supplierPhone');

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

            btnAddSupplier.addEventListener('click', () => {
                supplierForm.action = "{{ route('supplier.store') }}";
                supplierForm.querySelector('input[name="_method"]')?.remove();
                supplierIdInput.value = '';
                supplierForm.reset();
                formContainer.classList.remove('d-none');
            });

            btnCancel.addEventListener('click', () => {
                formContainer.classList.add('d-none');
                supplierForm.reset();
                supplierIdInput.value = '';
            });

            document.querySelectorAll('.btn-edit').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const tr = e.target.closest('tr');
                    if (!tr) return;

                    const id = tr.dataset.id;
                    const name = tr.dataset.name;
                    const email = tr.dataset.email;
                    const phone = tr.dataset.phone;

                    supplierForm.action = "{{ url('supplier') }}/" + id;

                    setFormMethod(supplierForm, 'PUT');

                    supplierIdInput.value = id;
                    supplierNameInput.value = name;
                    supplierEmailInput.value = email;
                    supplierPhoneInput.value = phone;

                    formContainer.classList.remove('d-none');
                    supplierNameInput.focus();
                });
            });

            const deleteSupplierForm = document.getElementById('deleteSupplierForm');
            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const tr = e.target.closest('tr');
                    if (!tr) return;
                    const id = tr.dataset.id;

                    deleteSupplierForm.action = "{{ url('supplier') }}/" + id;

                    let methodInput = deleteSupplierForm.querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        deleteSupplierForm.appendChild(methodInput);
                    }
                    methodInput.value = 'DELETE';
                    deleteSupplierForm.method = 'POST';
                });
            });

            const hasFormError = {{ $errors->any() ? 'true' : 'false' }};
            if (hasFormError) {
                formContainer.classList.remove('d-none');
            }
        });
    </script>
</x-app>
