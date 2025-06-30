<x-app>
    @section('title', 'Customer')

    <div class="page-header d-flex justify-content-between align-items-center mb-3">
        <div class="page-title">
            <h4>Customer List</h4>
            <h6>Manage your Customer</h6>
        </div>
        <div class="page-btn">
            <button id="btnAddCustomer" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i> Add Customer
            </button>
        </div>
    </div>

    <div id="formContainer" class="card mb-4 {{ $errors->any() ? '' : 'd-none' }}">
        <div class="card-body">
            <form id="customerForm" action="" method="POST">
                @csrf
                <input type="hidden" name="customer_id" id="customerId" value="">

                <div class="row g-3">
                    <!-- Name -->
                    <div class="col-md-6">
                        <label for="customerName" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="customerName"
                            name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback d-flex align-items-center mt-1" style="display: block;">
                                <i class="bi bi-exclamation-circle-fill me-2"></i><span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <label for="customerEmail" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                            id="customerEmail" name="email" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback d-flex align-items-center mt-1" style="display: block;">
                                <i class="bi bi-exclamation-circle-fill me-2"></i><span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="col-md-6">
                        <label for="customerPhone" class="form-label">Phone</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                            id="customerPhone" name="phone" value="{{ old('phone') }}">
                        @error('phone')
                            <div class="invalid-feedback d-flex align-items-center mt-1" style="display: block;">
                                <i class="bi bi-exclamation-circle-fill me-2"></i><span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="col-md-6">
                        <label for="customerAlamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control @error('alamat') is-invalid @enderror"
                            id="customerAlamat" name="alamat" value="{{ old('alamat') }}">
                        @error('alamat')
                            <div class="invalid-feedback d-flex align-items-center mt-1" style="display: block;">
                                <i class="bi bi-exclamation-circle-fill me-2"></i><span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Umur -->
                    <div class="col-md-6">
                        <label for="customerUmur" class="form-label">Umur</label>
                        <input type="number" class="form-control @error('umur') is-invalid @enderror" id="customerUmur"
                            name="umur" value="{{ old('umur') }}" min="1">
                        @error('umur')
                            <div class="invalid-feedback d-flex align-items-center mt-1" style="display: block;">
                                <i class="bi bi-exclamation-circle-fill me-2"></i><span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Gender -->
                    <div class="col-md-6">
                        <label for="customerGender" class="form-label">Gender</label>
                        <select class="form-select @error('gender') is-invalid @enderror" id="customerGender"
                            name="gender">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback d-flex align-items-center mt-1" style="display: block;">
                                <i class="bi bi-exclamation-circle-fill me-2"></i><span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Button -->
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
                            <th>Alamat</th>
                            <th>Umur</th>
                            <th>Gender</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr data-id="{{ $customer->id }}" data-name="{{ $customer->name }}"
                                data-email="{{ $customer->email }}" data-phone="{{ $customer->phone }}"
                                data-alamat="{{ $customer->alamat }}" data-umur="{{ $customer->umur }}"
                                data-gender="{{ $customer->gender }}">
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->phone }}</td>
                                <td>{{ $customer->alamat }}</td>
                                <td>{{ $customer->umur }}</td>
                                <td>{{ ucfirst($customer->gender) }}</td>
                                <td>
                                    <a class="me-2" href="{{ route('customer.show', $customer->id) }}">
                                        <i class="bi bi-person fs-4 align-middle"></i>
                                    </a>
                                    <button class="btn btn-sm btn-edit" title="Edit Customer">
                                        <img src="assets/img/icons/edit.svg" alt="edit">
                                    </button>
                                    <button class="btn btn-sm btn-delete" title="Delete Customer"
                                        data-bs-toggle="modal" data-bs-target="#deleteCustomerModal">
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

    <div class="modal fade" id="deleteCustomerModal" tabindex="-1" aria-labelledby="deleteCustomerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <form id="deleteCustomerForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-content rounded-4 shadow-sm border-0">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-semibold " id="deleteCustomerModalLabel">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body fs-5 pt-2 pb-3 text-center">
                        Are you sure you want to delete this customer?
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
            const btnAddCustomer = document.getElementById('btnAddCustomer');
            const btnCancel = document.getElementById('btnCancel');

            const customerForm = document.getElementById('customerForm');
            const customerIdInput = document.getElementById('customerId');
            const customerNameInput = document.getElementById('customerName');
            const customerEmailInput = document.getElementById('customerEmail');
            const customerPhoneInput = document.getElementById('customerPhone');
            const customerAlamatInput = document.getElementById('customerAlamat');
            const customerUmurInput = document.getElementById('customerUmur');
            const customerGenderInput = document.getElementById('customerGender');


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

            document.querySelectorAll('.btn-edit').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const tr = e.target.closest('tr');
                    if (!tr) return;

                    const id = tr.dataset.id;
                    const name = tr.dataset.name;
                    const email = tr.dataset.email;
                    const phone = tr.dataset.phone;
                    const alamat = tr.dataset.alamat;
                    const umur = tr.dataset.umur;
                    const gender = tr.dataset.gender;

                    customerForm.action = "{{ url('customer') }}/" + id;
                    setFormMethod(customerForm, 'PUT');

                    customerIdInput.value = id;
                    customerNameInput.value = name;
                    customerEmailInput.value = email;
                    customerPhoneInput.value = phone;
                    customerAlamatInput.value = alamat;
                    customerUmurInput.value = umur;
                    customerGenderInput.value = gender;


                    formContainer.classList.remove('d-none');
                    customerNameInput.focus();
                });
            });

            const deleteCustomerForm = document.getElementById('deleteCustomerForm');
            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const tr = e.target.closest('tr');
                    if (!tr) return;
                    const id = tr.dataset.id;

                    deleteCustomerForm.action = "{{ url('customer') }}/" + id;

                    let methodInput = deleteCustomerForm.querySelector('input[name="_method"]');
                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        deleteCustomerForm.appendChild(methodInput);
                    }
                    methodInput.value = 'DELETE';
                    deleteCustomerForm.method = 'POST';
                });
            });

            const hasFormError = {{ $errors->any() ? 'true' : 'false' }};
            if (hasFormError) {
                formContainer.classList.remove('d-none');
            }
        });
    </script>
</x-app>
