<x-app>
    @section('title', 'Staff')

    <div class="page-header d-flex justify-content-between align-items-center mb-3">
        <div class="page-title">
            <h4>Staff List</h4>
            <h6>Manage your Staff</h6>
        </div>
        <div class="page-btn">
            <button id="btnAddStaff" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i> Add Staff
            </button>
        </div>
    </div>

    {{-- Form Add Staff --}}
    <div id="formContainer" class="card mb-4 {{ $errors->any() ? '' : 'd-none' }}">
        <div class="card-body">
            <form id="staffForm" action="{{ route('create.staff') }}" method="POST">
                @csrf
                <input type="hidden" name="staff_id" id="staffId" value="">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="staffName" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="staffName"
                            name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback d-flex align-items-center mt-1" style="display: block;">
                                <i class="bi bi-exclamation-circle-fill me-2"></i><span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="staffEmail" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="staffEmail"
                            name="email" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback d-flex align-items-center mt-1" style="display: block;">
                                <i class="bi bi-exclamation-circle-fill me-2"></i><span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="staffPassword" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="staffPassword" name="password" value="{{ old('password') }}">
                        @error('password')
                            <div class="invalid-feedback d-flex align-items-center mt-1" style="display: block;">
                                <i class="bi bi-exclamation-circle-fill me-2"></i><span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="staffRole" class="form-label">Role</label>
                        <select class="form-select @error('role') is-invalid @enderror" id="staffRole" name="role"
                            required>
                            <option selected disabled>Select Role</option>
                            <option value="gudang" {{ old('role') == 'gudang' ? 'selected' : '' }}>Gudang</option>
                            <option value="cabang" {{ old('role') == 'cabang' ? 'selected' : '' }}>Cabang</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback d-flex align-items-center mt-1" style="display: block;">
                                <i class="bi bi-exclamation-circle-fill me-2"></i><span>{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 {{ old('role') == 'cabang' ? '' : 'd-none' }}" id="branchSelectContainer">
                        <label for="branchNumber" class="form-label">Cabang ke</label>
                        <select class="form-select @error('cabang') is-invalid @enderror" id="branchNumber"
                            name="cabang">
                            <option selected disabled>Pilih Cabang</option>
                            @foreach ($cabang as $cab)
                                <option value="{{ $cab->id }}"
                                    {{ old('cabang') == $cab->nama ? 'selected' : '' }}>
                                    Cabang {{ $cab->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('cabang')
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

    {{-- Staff Table --}}
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($staffs as $staff)
                            <tr data-id="{{ $staff->id }}" data-name="{{ $staff->name }}"
                                data-email="{{ $staff->email }}" data-role="{{ $staff->role }}"
                                data-cabang="{{ $staff->cabang_id ?? '' }}">
                                <td>{{ $staff->name }}</td>
                                <td>{{ $staff->email }}</td>
                                <td>{{ ucfirst($staff->role) }}</td>
                                <td>
                                    <button class="btn btn-sm btn-edit" title="Edit Staff">
                                        <img src="assets/img/icons/edit.svg" alt="edit">
                                    </button>
                                    <button class="btn btn-sm btn-delete" title="Delete Staff" data-bs-toggle="modal"
                                        data-bs-target="#deleteStaffModal">
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

    <div class="modal fade" id="deleteStaffModal" tabindex="-1" aria-labelledby="deleteStaffModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <form id="deleteStaffForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-content rounded-4 shadow-sm border-0">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-semibold " id="deleteStaffModalLabel">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body fs-5 pt-2 pb-3 text-center">
                        Are you sure you want to delete this staff?
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
        const btnAddStaff = document.getElementById('btnAddStaff');
        const btnCancel = document.getElementById('btnCancel');
        const staffForm = document.getElementById('staffForm');
        const staffIdInput = document.getElementById('staffId');
        const staffNameInput = document.getElementById('staffName');
        const staffEmailInput = document.getElementById('staffEmail');
        const staffPasswordInput = document.getElementById('staffPassword');
        const staffRoleSelect = document.getElementById('staffRole');
        const branchSelectContainer = document.getElementById('branchSelectContainer');
        const branchSelect = document.getElementById('branchNumber');

        // Input _method untuk method spoofing
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

        function toggleBranch() {
            if (staffRoleSelect.value === 'cabang') {
                branchSelectContainer.classList.remove('d-none');
            } else {
                branchSelectContainer.classList.add('d-none');
                branchSelect.value = '';
            }
        }
        staffRoleSelect.addEventListener('change', toggleBranch);
        toggleBranch();

        btnAddStaff.addEventListener('click', () => {
            staffForm.action = "{{ route('create.staff') }}";  // pastikan route create.staff ada di web.php
            setFormMethod(staffForm, 'POST'); // POST buat create
            staffIdInput.value = '';
            staffForm.reset();
            toggleBranch();
            formContainer.classList.remove('d-none');
        });

        btnCancel.addEventListener('click', () => {
            formContainer.classList.add('d-none');
            staffForm.reset();
            staffIdInput.value = '';
        });

        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const tr = e.target.closest('tr');
                if (!tr) return;

                const id = tr.dataset.id;
                const name = tr.dataset.name;
                const email = tr.dataset.email;
                const role = tr.dataset.role;
                const cabang = tr.dataset.cabang;

                staffForm.action = "{{ url('staff') }}/" + id;

                setFormMethod(staffForm, 'PUT');

                staffIdInput.value = id;
                staffNameInput.value = name;
                staffEmailInput.value = email;
                staffPasswordInput.value = ''; 
                staffRoleSelect.value = role;
                toggleBranch();

                if (role === 'cabang' && cabang) {
                    branchSelect.value = cabang;
                } else {
                    branchSelect.value = '';
                }

                formContainer.classList.remove('d-none');
                staffNameInput.focus();
            });
        });

        // Delete button click handler
        const deleteStaffForm = document.getElementById('deleteStaffForm');
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const tr = e.target.closest('tr');
                if (!tr) return;
                const id = tr.dataset.id;

                // pakai route name delete.staff dan method DELETE
                deleteStaffForm.action = "{{ url('staff') }}/" + id;

                // set method spoofing DELETE
                let methodInput = deleteStaffForm.querySelector('input[name="_method"]');
                if (!methodInput) {
                    methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    deleteStaffForm.appendChild(methodInput);
                }
                methodInput.value = 'DELETE';
                deleteStaffForm.method = 'POST'; // form tetap POST
            });
        });

        const hasFormError = {{ $errors->any() ? 'true' : 'false' }};
        if (hasFormError) {
            formContainer.classList.remove('d-none');
        }
    });
</script>

</x-app>
