<x-app>
    @section('title', 'Lensa Khusus')

    <div class="container-fluid">
        <div class="page-header">
            <div class="page-title">
                <h1 class="fw-bold">Lensa Khusus</h1>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body py-5 px-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">List Lensa Khusus</h5>
                    @auth
                        @if (in_array(Auth::user()->role, ['admin', 'gudang_utama']))
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
                            <div class="col-md-6">
                                <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('sku') is-invalid @enderror"
                                    id="sku" name="sku" value="{{ old('sku') }}" required>
                                @error('sku')
                                    <div class="invalid-feedback d-flex align-items-center mt-1" style="display: block;">
                                        <i class="bi bi-exclamation-circle-fill me-2"></i><span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Merk <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('merk') is-invalid @enderror"
                                        id="merk" name="merk" value="{{ old('merk') }}" required>
                                    @error('merk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Desain</label>
                                    <input type="text" class="form-control @error('desain') is-invalid @enderror"
                                        id="desain" name="desain" value="{{ old('desain') }}">
                                    @error('desain')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Tipe</label>
                                    <input type="text" class="form-control @error('tipe') is-invalid @enderror"
                                        id="tipe" name="tipe" value="{{ old('tipe') }}">
                                    @error('tipe')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">SPH</label>
                                    <select class="form-select @error('sph') is-invalid @enderror" id="sph"
                                        name="sph">
                                        <option value="">Pilih SPH</option>
                                        @for ($i = -20; $i <= 20; $i += 0.25)
                                            <option value="{{ number_format($i, 2) }}"
                                                {{ old('sph') == number_format($i, 2) ? 'selected' : '' }}>
                                                {{ number_format($i, 2) }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('sph')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">CYL</label>
                                    <select class="form-select @error('cyl') is-invalid @enderror" id="cyl"
                                        name="cyl">
                                        <option value="">Pilih CYL</option>
                                        @for ($i = -8; $i <= 0; $i += 0.25)
                                            <option value="{{ number_format($i, 2) }}"
                                                {{ old('cyl') == number_format($i, 2) ? 'selected' : '' }}>
                                                {{ number_format($i, 2) }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('cyl')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">ADD</label>
                                    <select class="form-select @error('add') is-invalid @enderror" id="add"
                                        name="add">
                                        <option value="">Pilih ADD</option>
                                        @for ($i = 0.75; $i <= 3.5; $i += 0.25)
                                            <option value="{{ number_format($i, 2) }}"
                                                {{ old('add') == number_format($i, 2) ? 'selected' : '' }}>
                                                {{ number_format($i, 2) }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('add')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>


                                <div class="col-md-3">
                                    <label class="form-label">Stok</label>
                                    <input type="number" class="form-control @error('stok') is-invalid @enderror"
                                        id="stok" name="stok" value="{{ old('stok') }}">
                                    @error('stok')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>



                                @if (in_array(Auth::user()->role, ['admin', 'gudang_utama']))
                                    <div class="col-md-3">
                                        <label class="form-label">Harga Beli</label>
                                        <input type="number" step="any"
                                            class="form-control @error('harga_beli') is-invalid @enderror"
                                            id="harga_beli" name="harga_beli" value="{{ old('harga_beli') }}">
                                        @error('harga_beli')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif

                                <div class="col-md-3">
                                    <label class="form-label">Harga Jual</label>
                                    <input type="number" step="any"
                                        class="form-control @error('harga') is-invalid @enderror" id="harga"
                                        name="harga" value="{{ old('harga') }}">
                                    @error('harga')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Estimasi Selesai (Hari)</label>
                                    <input type="text"
                                        class="form-control @error('estimasi_selesai_hari') is-invalid @enderror"
                                        id="estimasi_selesai_hari" name="estimasi_selesai_hari"
                                        value="{{ old('estimasi_selesai_hari') }}">
                                    @error('estimasi_selesai_hari')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Status Pesanan</label>
                                    <select class="form-select @error('status_pesanan') is-invalid @enderror"
                                        id="status_pesanan" name="status_pesanan">
                                        <option value="">-- Pilih Status --</option>
                                        <option value="menunggu"
                                            {{ old('status_pesanan') == 'menunggu' ? 'selected' : '' }}>Menunggu
                                        </option>
                                        <option value="proses"
                                            {{ old('status_pesanan') == 'proses' ? 'selected' : '' }}>Proses</option>
                                        <option value="selesai"
                                            {{ old('status_pesanan') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                    @error('status_pesanan')
                                        <div class="invalid-feedback">{{ $message }}</div>
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
                    <form method="GET" action="{{ route('lensaKhusus.index') }}" class="input-group mb-4">
                        <input type="text" class="form-control" name="search"
                            placeholder="Search by merk, tipe or Desain..." value="{{ request('search') }}"
                            autocomplete="off">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                        <a href="{{ route('lensaKhusus.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    </form>
                    <table class="table table-borderless align-middle" id="lensaTable">
                        <thead class="table-light">
                            <tr>
                                <th>SKU</th>
                                <th>Merk</th>
                                <th>Desain</th>
                                <th>Type</th>
                                <th>SPH</th>
                                <th>CYL</th>
                                <th>ADD</th>
                                <th>Stok</th>
                                @if (in_array(Auth::user()->role, ['admin', 'gudang_utama']))
                                    <th>Harga Beli</th>
                                @endif
                                <th>Harga Jual</th>
                                @if (in_array(Auth::user()->role, ['admin', 'gudang_utama']))
                                    <th>Laba</th>
                                @endif
                                <th>Estimasi Selesai</th>
                                <th>Status Pesanan</th>
                                @if (in_array(Auth::user()->role, ['admin', 'gudang_utama']))
                                    <th>Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lensaKhusus as $item)
                                <tr data-id="{{ $item->id }}" data-sku="{{ $item->sku }}"
                                    data-merk="{{ $item->merk }}" data-desain="{{ $item->desain }}"
                                    data-tipe="{{ $item->tipe }}" data-sph="{{ $item->sph }}"
                                    data-cyl="{{ $item->cyl }}" data-add="{{ $item->add }}"
                                    data-stok="{{ $item->stok }}" data-harga="{{ $item->harga }}"
                                    data-estimasi_selesai_hari="{{ $item->estimasi_selesai_hari }}"
                                    data-status_pesanan="{{ $item->status_pesanan }}"
                                    @if (in_array(Auth::user()->role, ['admin', 'gudang_utama'])) data-harga_beli="{{ $item->harga_beli }}" @endif>
                                    <td>{{ $item->sku }}</td>
                                    <td>{{ $item->merk }}</td>
                                    <td>{{ $item->desain }}</td>
                                    <td>{{ $item->tipe }}</td>
                                    <td>{{ $item->sph }}</td>
                                    <td>{{ $item->cyl }}</td>
                                    <td>{{ $item->add }}</td>
                                    <td>{{ $item->stok }}</td>
                                    @if (in_array(Auth::user()->role, ['admin', 'gudang_utama']))
                                        <td>Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                    @endif
                                    <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                    @if (in_array(Auth::user()->role, ['admin', 'gudang_utama']))
                                        <td>Rp {{ number_format($item->laba, 0, ',', '.') }}</td>
                                    @endif
                                    <td>{{ $item->estimasi_selesai_hari }} Hari</td>
                                    <td>{{ ucfirst($item->status_pesanan) }}</td>
                                    @if (in_array(Auth::user()->role, ['admin', 'gudang_utama']))
                                        <td>
                                            <button class="btn btn-sm btn-edit-lensa-khusus" title="Edit">
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>
                                            <button class="btn btn-sm btn-delete-lensa-khusus text-danger"
                                                data-bs-toggle="modal" data-bs-target="#deleteLensaKhususForm">
                                                <i class="bi bi-trash-fill"></i>
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



    <div class="modal fade" id="deleteLensaKhususForm" tabindex="-1" aria-labelledby="deleteLensaKhususForm"
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
            const deleteForm = document.getElementById('deleteLensaKhususForm');

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

            if (btnAdd) {
                btnAdd.addEventListener('click', () => {
                    form.action = "{{ route('lensaKhusus.store') }}";
                    form.querySelector('input[name="_method"]')?.remove();
                    form.reset();
                    idInput.value = '';
                    formContainer.classList.remove('d-none');
                    form.merk.focus();
                    btnAdd.classList.add('d-none');
                });
            }

            btnCancel.addEventListener('click', () => {
                formContainer.classList.add('d-none');
                form.reset();
                idInput.value = '';
                btnAdd.classList.remove('d-none');
            });

            document.querySelectorAll('.btn-edit-lensa-khusus').forEach(btn => {
                btn.addEventListener('click', () => {
                    const tr = btn.closest('tr');
                    const id = tr.dataset.id;
                    form.action = `/lensaKhusus/${id}`;
                    setFormMethod(form, 'PUT');
                    idInput.value = id;
                    form.sku.value = tr.dataset.sku;
                    form.merk.value = tr.dataset.merk;
                    form.desain.value = tr.dataset.desain;
                    form.tipe.value = tr.dataset.tipe;
                    form.sph.value = tr.dataset.sph;
                    form.cyl.value = tr.dataset.cyl;
                    form.add.value = tr.dataset.add;
                    form.harga.value = tr.dataset.harga;
                    form.harga_beli.value = tr.dataset.harga_beli;
                    form.stok.value = tr.dataset.stok;
                    form.estimasi_selesai_hari.value = tr.dataset.estimasi_selesai_hari;
                    form.status_pesanan.value = tr.dataset.status_pesanan;
                    formContainer.classList.remove('d-none');
                    form.merk.focus();
                    btnAdd.classList.add('d-none');
                });
            });

            document.querySelectorAll('.btn-delete-lensa-khusus').forEach(btn => {
                btn.addEventListener('click', () => {
                    const tr = btn.closest('tr');
                    const id = tr.dataset.id;
                    deleteForm.action = `/lensaKhusus/${id}`;
                    setFormMethod(deleteForm, 'DELETE');
                    deleteForm.method = 'POST';
                });
            });


        });
    </script>


</x-app>
