<x-app>
    @section('title', 'Detail / Edit Resep')

    @php
        // Set ke true jika ingin mengunci form, atau false untuk mode edit aktif
        $isReadOnly = $isReadOnly ?? false;
    @endphp

    <div class="container-fluid py-4">
        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-pencil-square me-2"></i>Detail / Edit Resep Kacamata #{{ $resep->id }}
            </h2>
            <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <!-- NOTIFIKASI SUCCESS / ERROR -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- INFO USER / CUSTOMER -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body bg-light rounded d-flex align-items-center justify-content-between py-2">
                <div>
                    <span class="text-muted small d-block">Pelanggan:</span>
                    <h5 class="fw-bold mb-0 text-primary">
                        <i class="bi bi-person-circle me-1"></i>{{ $resep->user->name ?? 'Customer Umum' }}
                    </h5>
                </div>
                <div>
                    <span class="text-muted small d-block">No. Telepon:</span>
                    <span class="fw-semibold">{{ $resep->user->phone ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- FORM UTAMA -->
        <form action="{{ route('resep.update', $resep->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="row g-3">

                        <!-- BAGIAN 1: TANGGAL & PEMERIKSA (STAFF) -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold"><i class="bi bi-calendar-event me-1"></i>Tanggal</label>
                            <input type="date" name="tanggal_pemeriksaan"
                                class="form-control @error('tanggal_pemeriksaan') is-invalid @enderror"
                                value="{{ old('tanggal_pemeriksaan', $resep->tanggal_pemeriksaan) }}"
                                @if($isReadOnly) disabled readonly style="background-color: #e9ecef;" @endif>
                            @error('tanggal_pemeriksaan')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold"><i class="bi bi-person-badge me-1"></i>Pemeriksa / Optometris</label>
                            <select name="staff_id" class="form-select @error('staff_id') is-invalid @enderror"
                                @if($isReadOnly) disabled style="background-color: #e9ecef;" @endif>
                                <option value="">-- Pilih Pemeriksa --</option>
                                @foreach ($optometristList ?? [] as $staff)
                                    <option value="{{ $staff->id }}" {{ old('staff_id', $resep->staff_id) == $staff->id ? 'selected' : '' }}>
                                        {{ $staff->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('staff_id')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- BAGIAN 2: TABEL OD/OS -->
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle table-sm mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th></th>
                                            <th style="width: 12%;">SPH</th>
                                            <th style="width: 12%;">CYL</th>
                                            <th style="width: 12%;">AXIS</th>
                                            <th style="width: 12%;">ADD</th>
                                            <th style="width: 12%;">PRISMA</th>
                                            <th style="width: 12%;">BASE</th>
                                            <th>VA</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- ROW OD (Mata Kanan) -->
                                        <tr>
                                            <td class="fw-bold text-start ps-2">OD</td>

                                            <!-- OD SPH (+20.00 s/d -20.00 step 0.25) -->
                                            <td>
                                                <select name="od_sph" class="form-select form-select-sm @error('od_sph') is-invalid @enderror"
                                                    @if($isReadOnly) disabled style="background-color: #e9ecef;" @endif>
                                                    <option value="0.00" {{ old('od_sph', number_format($resep->od_sph ?? 0, 2)) == '0.00' ? 'selected' : '' }}>0.00</option>
                                                    @for ($i = 20; $i >= -20; $i -= 0.25)
                                                        @if($i != 0)
                                                            @php $formatted = number_format($i, 2); @endphp
                                                            <option value="{{ $formatted }}" {{ old('od_sph', number_format($resep->od_sph ?? 0, 2)) == $formatted ? 'selected' : '' }}>
                                                                {{ $i > 0 ? '+' : '' }}{{ $formatted }}
                                                            </option>
                                                        @endif
                                                    @endfor
                                                </select>
                                                @error('od_sph') <span class="text-danger small">{{ $message }}</span> @enderror
                                            </td>

                                            <!-- OD CYL (0.00 s/d -6.00 step 0.25) -->
                                            <td>
                                                <select name="od_cyl" class="form-select form-select-sm @error('od_cyl') is-invalid @enderror"
                                                    @if($isReadOnly) disabled style="background-color: #e9ecef;" @endif>
                                                    <option value="0.00" {{ old('od_cyl', number_format($resep->od_cyl ?? 0, 2)) == '0.00' ? 'selected' : '' }}>0.00</option>
                                                    @for ($i = -0.25; $i >= -6; $i -= 0.25)
                                                        @php $formatted = number_format($i, 2); @endphp
                                                        <option value="{{ $formatted }}" {{ old('od_cyl', number_format($resep->od_cyl ?? 0, 2)) == $formatted ? 'selected' : '' }}>
                                                            {{ $formatted }}
                                                        </option>
                                                    @endfor
                                                </select>
                                                @error('od_cyl') <span class="text-danger small">{{ $message }}</span> @enderror
                                            </td>

                                            <!-- OD AXIS (0 - 180 Step 1) -->
                                            <td>
                                                <input type="number" step="1" min="0" max="180" placeholder="0-180"
                                                    name="od_axis" value="{{ old('od_axis', $resep->od_axis) }}"
                                                    class="form-control form-control-sm text-center @error('od_axis') is-invalid @enderror"
                                                    @if($isReadOnly) readonly style="background-color: #e9ecef;" @endif>
                                                @error('od_axis') <span class="text-danger small">{{ $message }}</span> @enderror
                                            </td>

                                            <!-- OD ADD (0.00 s/d +4.00 step 0.25) -->
                                            <td>
                                                <select name="od_add" class="form-select form-select-sm @error('od_add') is-invalid @enderror"
                                                    @if($isReadOnly) disabled style="background-color: #e9ecef;" @endif>
                                                    <option value="0.00" {{ old('od_add', number_format($resep->od_add ?? 0, 2)) == '0.00' ? 'selected' : '' }}>0.00</option>
                                                    @for ($i = 0.25; $i <= 4; $i += 0.25)
                                                        @php $formatted = number_format($i, 2); @endphp
                                                        <option value="{{ $formatted }}" {{ old('od_add', number_format($resep->od_add ?? 0, 2)) == $formatted ? 'selected' : '' }}>
                                                            +{{ $formatted }}
                                                        </option>
                                                    @endfor
                                                </select>
                                                @error('od_add') <span class="text-danger small">{{ $message }}</span> @enderror
                                            </td>

                                            <!-- OD PRISMA (0.00 s/d 10.00 step 0.25) -->
                                            <td>
                                                <select name="od_prisma" class="form-select form-select-sm @error('od_prisma') is-invalid @enderror"
                                                    @if($isReadOnly) disabled style="background-color: #e9ecef;" @endif>
                                                    <option value="0.00" {{ old('od_prisma', number_format($resep->od_prisma ?? 0, 2)) == '0.00' ? 'selected' : '' }}>0.00</option>
                                                    @for ($i = 0.25; $i <= 10; $i += 0.25)
                                                        @php $formatted = number_format($i, 2); @endphp
                                                        <option value="{{ $formatted }}" {{ old('od_prisma', number_format($resep->od_prisma ?? 0, 2)) == $formatted ? 'selected' : '' }}>
                                                            {{ $formatted }}
                                                        </option>
                                                    @endfor
                                                </select>
                                                @error('od_prisma') <span class="text-danger small">{{ $message }}</span> @enderror
                                            </td>

                                            <!-- OD BASE -->
                                            <td>
                                                <select name="od_base" class="form-select form-select-sm @error('od_base') is-invalid @enderror"
                                                    @if($isReadOnly) disabled style="background-color: #e9ecef;" @endif>
                                                    <option value=""></option>
                                                    @foreach(['In', 'Out', 'Up', 'Down'] as $b)
                                                        <option value="{{ $b }}" {{ old('od_base', $resep->od_base) == $b ? 'selected' : '' }}>{{ $b }}</option>
                                                    @endforeach
                                                </select>
                                                @error('od_base') <span class="text-danger small">{{ $message }}</span> @enderror
                                            </td>

                                            <!-- OD VA -->
                                            <td>
                                                <input type="text" placeholder="misal 6/6" name="od_va"
                                                    value="{{ old('od_va', $resep->od_va) }}"
                                                    class="form-control form-control-sm text-center @error('od_va') is-invalid @enderror"
                                                    @if($isReadOnly) readonly style="background-color: #e9ecef;" @endif>
                                                @error('od_va') <span class="text-danger small">{{ $message }}</span> @enderror
                                            </td>
                                        </tr>

                                        <!-- ROW OS (Mata Kiri) -->
                                        <tr>
                                            <td class="fw-bold text-start ps-2">OS</td>

                                            <!-- OS SPH -->
                                            <td>
                                                <select name="os_sph" class="form-select form-select-sm @error('os_sph') is-invalid @enderror"
                                                    @if($isReadOnly) disabled style="background-color: #e9ecef;" @endif>
                                                    <option value="0.00" {{ old('os_sph', number_format($resep->os_sph ?? 0, 2)) == '0.00' ? 'selected' : '' }}>0.00</option>
                                                    @for ($i = 20; $i >= -20; $i -= 0.25)
                                                        @if($i != 0)
                                                            @php $formatted = number_format($i, 2); @endphp
                                                            <option value="{{ $formatted }}" {{ old('os_sph', number_format($resep->os_sph ?? 0, 2)) == $formatted ? 'selected' : '' }}>
                                                                {{ $i > 0 ? '+' : '' }}{{ $formatted }}
                                                            </option>
                                                        @endif
                                                    @endfor
                                                </select>
                                                @error('os_sph') <span class="text-danger small">{{ $message }}</span> @enderror
                                            </td>

                                            <!-- OS CYL -->
                                            <td>
                                                <select name="os_cyl" class="form-select form-select-sm @error('os_cyl') is-invalid @enderror"
                                                    @if($isReadOnly) disabled style="background-color: #e9ecef;" @endif>
                                                    <option value="0.00" {{ old('os_cyl', number_format($resep->os_cyl ?? 0, 2)) == '0.00' ? 'selected' : '' }}>0.00</option>
                                                    @for ($i = -0.25; $i >= -6; $i -= 0.25)
                                                        @php $formatted = number_format($i, 2); @endphp
                                                        <option value="{{ $formatted }}" {{ old('os_cyl', number_format($resep->os_cyl ?? 0, 2)) == $formatted ? 'selected' : '' }}>
                                                            {{ $formatted }}
                                                        </option>
                                                    @endfor
                                                </select>
                                                @error('os_cyl') <span class="text-danger small">{{ $message }}</span> @enderror
                                            </td>

                                            <!-- OS AXIS -->
                                            <td>
                                                <input type="number" step="1" min="0" max="180" placeholder="0-180"
                                                    name="os_axis" value="{{ old('os_axis', $resep->os_axis) }}"
                                                    class="form-control form-control-sm text-center @error('os_axis') is-invalid @enderror"
                                                    @if($isReadOnly) readonly style="background-color: #e9ecef;" @endif>
                                                @error('os_axis') <span class="text-danger small">{{ $message }}</span> @enderror
                                            </td>

                                            <!-- OS ADD -->
                                            <td>
                                                <select name="os_add" class="form-select form-select-sm @error('os_add') is-invalid @enderror"
                                                    @if($isReadOnly) disabled style="background-color: #e9ecef;" @endif>
                                                    <option value="0.00" {{ old('os_add', number_format($resep->os_add ?? 0, 2)) == '0.00' ? 'selected' : '' }}>0.00</option>
                                                    @for ($i = 0.25; $i <= 4; $i += 0.25)
                                                        @php $formatted = number_format($i, 2); @endphp
                                                        <option value="{{ $formatted }}" {{ old('os_add', number_format($resep->os_add ?? 0, 2)) == $formatted ? 'selected' : '' }}>
                                                            +{{ $formatted }}
                                                        </option>
                                                    @endfor
                                                </select>
                                                @error('os_add') <span class="text-danger small">{{ $message }}</span> @enderror
                                            </td>

                                            <!-- OS PRISMA -->
                                            <td>
                                                <select name="os_prisma" class="form-select form-select-sm @error('os_prisma') is-invalid @enderror"
                                                    @if($isReadOnly) disabled style="background-color: #e9ecef;" @endif>
                                                    <option value="0.00" {{ old('os_prisma', number_format($resep->os_prisma ?? 0, 2)) == '0.00' ? 'selected' : '' }}>0.00</option>
                                                    @for ($i = 0.25; $i <= 10; $i += 0.25)
                                                        @php $formatted = number_format($i, 2); @endphp
                                                        <option value="{{ $formatted }}" {{ old('os_prisma', number_format($resep->os_prisma ?? 0, 2)) == $formatted ? 'selected' : '' }}>
                                                            {{ $formatted }}
                                                        </option>
                                                    @endfor
                                                </select>
                                                @error('os_prisma') <span class="text-danger small">{{ $message }}</span> @enderror
                                            </td>

                                            <!-- OS BASE -->
                                            <td>
                                                <select name="os_base" class="form-select form-select-sm @error('os_base') is-invalid @enderror"
                                                    @if($isReadOnly) disabled style="background-color: #e9ecef;" @endif>
                                                    <option value=""></option>
                                                    @foreach(['In', 'Out', 'Up', 'Down'] as $b)
                                                        <option value="{{ $b }}" {{ old('os_base', $resep->os_base) == $b ? 'selected' : '' }}>{{ $b }}</option>
                                                    @endforeach
                                                </select>
                                                @error('os_base') <span class="text-danger small">{{ $message }}</span> @enderror
                                            </td>

                                            <!-- OS VA -->
                                            <td>
                                                <input type="text" placeholder="misal 6/6" name="os_va"
                                                    value="{{ old('os_va', $resep->os_va) }}"
                                                    class="form-control form-control-sm text-center @error('os_va') is-invalid @enderror"
                                                    @if($isReadOnly) readonly style="background-color: #e9ecef;" @endif>
                                                @error('os_va') <span class="text-danger small">{{ $message }}</span> @enderror
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- BAGIAN 3: NOTE -->
                        <div class="col-md-12">
                            <label class="form-label fw-bold">NOTE</label>
                            <textarea name="notes" rows="2" class="form-control @error('notes') is-invalid @enderror"
                                placeholder="Catatan tambahan..." @if($isReadOnly) readonly style="background-color: #e9ecef;" @endif>{{ old('notes', $resep->notes) }}</textarea>
                            @error('notes')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- BAGIAN 4: DATA PRECAL -->
                        <div class="col-md-12 pt-2">
                            <h6 class="fw-bold text-decoration-underline">DATA PRECAL</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center align-middle table-sm mt-2 mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>PDR</th>
                                            <th>PDL</th>
                                            <th>PV</th>
                                            <th>A</th>
                                            <th>B</th>
                                            <th>D</th>
                                            <th>DIAG</th>
                                            <th style="width: 20%;">FRAME</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input type="number" step="0.5" placeholder="0.0" name="pdr" value="{{ old('pdr', $resep->pdr) }}" class="form-control form-control-sm text-center @error('pdr') is-invalid @enderror" @if($isReadOnly) readonly style="background-color: #e9ecef;" @endif>
                                                @error('pdr') <span class="text-danger small">{{ $message }}</span> @enderror
                                            </td>
                                            <td>
                                                <input type="number" step="0.5" placeholder="0.0" name="pdl" value="{{ old('pdl', $resep->pdl) }}" class="form-control form-control-sm text-center @error('pdl') is-invalid @enderror" @if($isReadOnly) readonly style="background-color: #e9ecef;" @endif>
                                                @error('pdl') <span class="text-danger small">{{ $message }}</span> @enderror
                                            </td>
                                            <td>
                                                <input type="number" step="0.1" placeholder="0" name="pv" value="{{ old('pv', $resep->pv) }}" class="form-control form-control-sm text-center @error('pv') is-invalid @enderror" @if($isReadOnly) readonly style="background-color: #e9ecef;" @endif>
                                                @error('pv') <span class="text-danger small">{{ $message }}</span> @enderror
                                            </td>
                                            <td>
                                                <input type="number" step="0.1" placeholder="0" name="frame_a" value="{{ old('frame_a', $resep->frame_a) }}" class="form-control form-control-sm text-center @error('frame_a') is-invalid @enderror" @if($isReadOnly) readonly style="background-color: #e9ecef;" @endif>
                                                @error('frame_a') <span class="text-danger small">{{ $message }}</span> @enderror
                                            </td>
                                            <td>
                                                <input type="number" step="0.1" placeholder="0" name="frame_b" value="{{ old('frame_b', $resep->frame_b) }}" class="form-control form-control-sm text-center @error('frame_b') is-invalid @enderror" @if($isReadOnly) readonly style="background-color: #e9ecef;" @endif>
                                                @error('frame_b') <span class="text-danger small">{{ $message }}</span> @enderror
                                            </td>
                                            <td>
                                                <input type="number" step="0.1" placeholder="0" name="frame_d" value="{{ old('frame_d', $resep->frame_d) }}" class="form-control form-control-sm text-center @error('frame_d') is-invalid @enderror" @if($isReadOnly) readonly style="background-color: #e9ecef;" @endif>
                                                @error('frame_d') <span class="text-danger small">{{ $message }}</span> @enderror
                                            </td>
                                            <td>
                                                <input type="number" step="0.1" placeholder="0" name="frame_diag" value="{{ old('frame_diag', $resep->frame_diag) }}" class="form-control form-control-sm text-center @error('frame_diag') is-invalid @enderror" @if($isReadOnly) readonly style="background-color: #e9ecef;" @endif>
                                                @error('frame_diag') <span class="text-danger small">{{ $message }}</span> @enderror
                                            </td>
                                            <td>
                                                <select name="frame" class="form-select form-select-sm @error('frame') is-invalid @enderror" @if($isReadOnly) disabled style="background-color: #e9ecef;" @endif>
                                                    <option value="">-- Pilih --</option>
                                                    <option value="Full Metal" {{ old('frame', $resep->frame) == 'Full Metal' ? 'selected' : '' }}>Full Metal</option>
                                                    <option value="Full Plastik" {{ old('frame', $resep->frame) == 'Full Plastik' ? 'selected' : '' }}>Full Plastik</option>
                                                    <option value="Nylon" {{ old('frame', $resep->frame) == 'Nylon' ? 'selected' : '' }}>Nylon</option>
                                                    <option value="Rimless" {{ old('frame', $resep->frame) == 'Rimless' ? 'selected' : '' }}>Rimless</option>
                                                </select>
                                                @error('frame') <span class="text-danger small">{{ $message }}</span> @enderror
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div> <!-- end row -->
                </div>
            </div>

            @if(!$isReadOnly)
                <div class="d-flex justify-content-end mb-4">
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                        <i class="bi bi-check-circle me-1"></i> Simpan Perubahan Resep
                    </button>
                </div>
            @endif
        </form>
    </div>
</x-app>
