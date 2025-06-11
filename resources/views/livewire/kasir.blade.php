<style>
    .card-scroll {
        max-height: 300px;
        overflow-y: auto;
    }

    .table thead th {
        position: sticky;
        top: 0;
        background: #fff;
    }
</style>

<div class="container-fluid py-3">
    <h2 class="fw-bold "><i class="bi bi-cash-stack me-2"></i>Kasir</h2>
    <div id="formContainer" class="card mb-4 {{ $errors->any() ? '' : 'd-none' }}">
        <div class="card-body">
            <form id="customerForm" action="" method="POST">
                @csrf
                <input type="hidden" name="customer_id" id="customerId" value="">
                <div class="row g-3">
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
                </div>

                <div class="mt-4 d-flex justify-content-end gap-2">
                    <button type="button" id="btnCancel" class="btn btn-secondary px-4">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Save</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-3">
        <div class="row g-3 align-items-stretch">
            <livewire:selected-customer />
            <livewire:customer-search />
        </div>
        <livewire:selected-product />
        <livewire:product-search />
        <livewire:transaction-detail />
        <livewire:bill-order />
    </div>



</div>

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
