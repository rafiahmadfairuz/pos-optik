<div>
 <div class="card mb-4">
    <div class="card-header fw-bold text-decoration-underline">Resep Kacamata</div>
    <div class="card-body">

        <!-- OPSI PEMILIHAN (RADIO BUTTON) -->
        <div class="row mb-3">
            <div class="col-md-12">
                <label class="form-label fw-bold">Opsi Resep Kacamata:</label>
                <div class="d-flex flex-wrap gap-4 mt-1">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" wire:model.live="opsi_resep" id="opsiTanpa" value="tanpa_resep">
                        <label class="form-check-label" for="opsiTanpa">
                            Tanpa resep
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" wire:model.live="opsi_resep" id="opsiLama" value="resep_lama">
                        <label class="form-check-label" for="opsiLama">
                            Gunakan resep lama
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" wire:model.live="opsi_resep" id="opsiBaru" value="resep_baru">
                        <label class="form-check-label" for="opsiBaru">
                            Buat resep baru
                        </label>
                    </div>
                </div>
                @error('opsi_resep')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- DROPDOWN PERIODE / HISTORI RESEP LAMA (Hanya Muncul Jika Pilih "Gunakan resep lama") -->
        @if ($opsi_resep === 'resep_lama')
            <div class="row mb-3 bg-light p-3 rounded border">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Pilih Periode Resep Lama (User):</label>
                    <select wire:model.live="selected_resep_id" class="form-select @error('selected_resep_id') is-invalid @enderror">
                        <option value="">-- Pilih Periode Resep --</option>
                        {{-- Menggunakan variabel listResepLama yang dikirim dari controller --}}
                        @forelse ($listResepLama ?? [] as $item)
                            <option value="{{ $item->id }}">
                                Periode: {{ \Carbon\Carbon::parse($item->tanggal_pemeriksaan)->format('d-m-Y') }}
                                {{ $item->notes ? '('.Str::limit($item->notes, 20).')' : '' }}
                            </option>
                        @empty
                            <option value="" disabled>Tidak ada data resep lama</option>
                        @endforelse
                    </select>
                    @error('selected_resep_id')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        @endif

        <!-- FORM INPUT RESEP KACAMATA -->
        <!-- Form hanya muncul jika memilih "Gunakan resep lama" (dan sudah memilih periode) ATAU "Buat resep baru" -->
        @if ($opsi_resep === 'resep_baru' || ($opsi_resep === 'resep_lama' && $selected_resep_id))

            @php
                // Variabel bantuan untuk menentukan apakah input dalam mode Readonly / Disabled
                $isReadOnly = ($opsi_resep === 'resep_lama');
            @endphp

            <div class="border-top pt-3 mt-3">
                <div class="row g-3">

                    
                    <!-- BAGIAN 1: TANGGAL & PEMERIKSA (STAFF) -->
<div class="col-md-4">
    <label class="form-label fw-bold"><i class="bi bi-calendar-event me-1"></i>Tanggal</label>
    <input type="date" wire:model.lazy="tanggal_pemeriksaan" name="tanggal_pemeriksaan"
        class="form-control @error('tanggal_pemeriksaan') is-invalid @enderror"
        @if($isReadOnly) disabled readonly style="background-color: #e9ecef;" @endif>
    @error('tanggal_pemeriksaan')
        <span class="text-danger error-message">{{ $message }}</span>
    @enderror
</div>

<div class="col-md-4">
    <label class="form-label fw-bold"><i class="bi bi-person-badge me-1"></i>Pemeriksa / Optometris</label>
    <select wire:model.lazy="staff_id" class="form-select @error('staff_id') is-invalid @enderror"
        @if($isReadOnly) disabled style="background-color: #e9ecef;" @endif>
        <option value="">-- Pilih Pemeriksa --</option>
        @foreach ($optometristList ?? [] as $staff)
            <option value="{{ $staff->id }}">{{ $staff->name }}</option>
        @endforeach
    </select>
    @error('staff_id')
        <span class="text-danger small">{{ $message }}</span>
    @enderror
</div>

                    <!-- BAGIAN 2: TABEL OD/OS -->
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center align-middle table-sm">
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

                                        <!-- OD SPH -->
                                        <td>
                                            <select wire:model.lazy="od_sph" class="form-select form-select-sm @error('od_sph') is-invalid @enderror"
                                                @if($isReadOnly) disabled style="background-color: #e9ecef;" @endif>
                                                <option value="0.00">0.00</option>
                                                @for ($i = 20; $i >= -20; $i -= 0.25)
                                                    @if($i != 0)
                                                        <option value="{{ number_format($i, 2) }}">
                                                            {{ $i > 0 ? '+' : '' }}{{ number_format($i, 2) }}
                                                        </option>
                                                    @endif
                                                @endfor
                                            </select>
                                            @error('od_sph') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </td>

                                        <!-- OD CYL -->
                                        <td>
                                            <select wire:model.lazy="od_cyl" class="form-select form-select-sm @error('od_cyl') is-invalid @enderror"
                                                @if($isReadOnly) disabled style="background-color: #e9ecef;" @endif>
                                                <option value="0.00">0.00</option>
                                                @for ($i = -0.25; $i >= -6; $i -= 0.25)
                                                    <option value="{{ number_format($i, 2) }}">{{ number_format($i, 2) }}</option>
                                                @endfor
                                            </select>
                                            @error('od_cyl') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </td>

                                        <!-- OD AXIS -->
                                        <td>
                                            <input type="number" step="1" min="0" max="180" placeholder="0-180"
                                                wire:model.lazy="od_axis" class="form-control form-control-sm @error('od_axis') is-invalid @enderror"
                                                @if($isReadOnly) readonly style="background-color: #e9ecef;" @endif>
                                            @error('od_axis') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </td>

                                        <!-- OD ADD -->
                                        <td>
                                            <select wire:model.lazy="od_add" class="form-select form-select-sm @error('od_add') is-invalid @enderror"
                                                @if($isReadOnly) disabled style="background-color: #e9ecef;" @endif>
                                                <option value="0.00">0.00</option>
                                                @for ($i = 0.25; $i <= 4; $i += 0.25)
                                                    <option value="{{ number_format($i, 2) }}">+{{ number_format($i, 2) }}</option>
                                                @endfor
                                            </select>
                                            @error('od_add') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </td>

                                        <!-- OD PRISMA -->
                                        <td>
                                            <select wire:model.lazy="od_prisma" class="form-select form-select-sm @error('od_prisma') is-invalid @enderror"
                                                @if($isReadOnly) disabled style="background-color: #e9ecef;" @endif>
                                                <option value="0.00">0.00</option>
                                                @for ($i = 0.25; $i <= 10; $i += 0.25)
                                                    <option value="{{ number_format($i, 2) }}">{{ number_format($i, 2) }}</option>
                                                @endfor
                                            </select>
                                            @error('od_prisma') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </td>

                                        <!-- OD BASE -->
                                        <td>
                                            <select wire:model.lazy="od_base" class="form-select form-select-sm @error('od_base') is-invalid @enderror"
                                                @if($isReadOnly) disabled style="background-color: #e9ecef;" @endif>
                                                <option value=""></option>
                                                <option value="In">In</option>
                                                <option value="Out">Out</option>
                                                <option value="Up">Up</option>
                                                <option value="Down">Down</option>
                                            </select>
                                            @error('od_base') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </td>

                                        <!-- OD VA -->
                                        <td>
                                            <input type="text" placeholder="misal 6/6" wire:model.lazy="od_va"
                                                class="form-control form-control-sm @error('od_va') is-invalid @enderror"
                                                @if($isReadOnly) readonly style="background-color: #e9ecef;" @endif>
                                            @error('od_va') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </td>
                                    </tr>

                                    <!-- ROW OS (Mata Kiri) -->
                                    <tr>
                                        <td class="fw-bold text-start ps-2">OS</td>

                                        <!-- OS SPH -->
                                        <td>
                                            <select wire:model.lazy="os_sph" class="form-select form-select-sm @error('os_sph') is-invalid @enderror"
                                                @if($isReadOnly) disabled style="background-color: #e9ecef;" @endif>
                                                <option value="0.00">0.00</option>
                                                @for ($i = 20; $i >= -20; $i -= 0.25)
                                                    @if($i != 0)
                                                        <option value="{{ number_format($i, 2) }}">
                                                            {{ $i > 0 ? '+' : '' }}{{ number_format($i, 2) }}
                                                        </option>
                                                    @endif
                                                @endfor
                                            </select>
                                            @error('os_sph') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </td>

                                        <!-- OS CYL -->
                                        <td>
                                            <select wire:model.lazy="os_cyl" class="form-select form-select-sm @error('os_cyl') is-invalid @enderror"
                                                @if($isReadOnly) disabled style="background-color: #e9ecef;" @endif>
                                                <option value="0.00">0.00</option>
                                                @for ($i = -0.25; $i >= -6; $i -= 0.25)
                                                    <option value="{{ number_format($i, 2) }}">{{ number_format($i, 2) }}</option>
                                                @endfor
                                            </select>
                                            @error('os_cyl') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </td>

                                        <!-- OS AXIS -->
                                        <td>
                                            <input type="number" step="1" min="0" max="180" placeholder="0-180"
                                                wire:model.lazy="os_axis" class="form-control form-control-sm @error('os_axis') is-invalid @enderror"
                                                @if($isReadOnly) readonly style="background-color: #e9ecef;" @endif>
                                            @error('os_axis') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </td>

                                        <!-- OS ADD -->
                                        <td>
                                            <select wire:model.lazy="os_add" class="form-select form-select-sm @error('os_add') is-invalid @enderror"
                                                @if($isReadOnly) disabled style="background-color: #e9ecef;" @endif>
                                                <option value="0.00">0.00</option>
                                                @for ($i = 0.25; $i <= 4; $i += 0.25)
                                                    <option value="{{ number_format($i, 2) }}">+{{ number_format($i, 2) }}</option>
                                                @endfor
                                            </select>
                                            @error('os_add') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </td>

                                        <!-- OS PRISMA -->
                                        <td>
                                            <select wire:model.lazy="os_prisma" class="form-select form-select-sm @error('os_prisma') is-invalid @enderror"
                                                @if($isReadOnly) disabled style="background-color: #e9ecef;" @endif>
                                                <option value="0.00">0.00</option>
                                                @for ($i = 0.25; $i <= 10; $i += 0.25)
                                                    <option value="{{ number_format($i, 2) }}">{{ number_format($i, 2) }}</option>
                                                @endfor
                                            </select>
                                            @error('os_prisma') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </td>

                                        <!-- OS BASE -->
                                        <td>
                                            <select wire:model.lazy="os_base" class="form-select form-select-sm @error('os_base') is-invalid @enderror"
                                                @if($isReadOnly) disabled style="background-color: #e9ecef;" @endif>
                                                <option value=""></option>
                                                <option value="In">In</option>
                                                <option value="Out">Out</option>
                                                <option value="Up">Up</option>
                                                <option value="Down">Down</option>
                                            </select>
                                            @error('os_base') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </td>

                                        <!-- OS VA -->
                                        <td>
                                            <input type="text" placeholder="misal 6/6" wire:model.lazy="os_va"
                                                class="form-control form-control-sm @error('os_va') is-invalid @enderror"
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
                        <textarea wire:model.lazy="notes" rows="2" class="form-control @error('notes') is-invalid @enderror"
                            placeholder="Catatan tambahan..." @if($isReadOnly) readonly style="background-color: #e9ecef;" @endif></textarea>
                        @error('notes')
                            <span class="text-danger error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- BAGIAN 4: DATA PRECAL -->
                    <div class="col-md-12 pt-2">
                        <h6 class="fw-bold text-decoration-underline">DATA PRECAL</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered text-center align-middle table-sm mt-2">
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
                                            <input type="number" step="0.5" placeholder="0.0" wire:model.lazy="pdr" class="form-control form-control-sm text-center @error('pdr') is-invalid @enderror" @if($isReadOnly) readonly style="background-color: #e9ecef;" @endif>
                                            @error('pdr') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </td>
                                        <td>
                                            <input type="number" step="0.5" placeholder="0.0" wire:model.lazy="pdl" class="form-control form-control-sm text-center @error('pdl') is-invalid @enderror" @if($isReadOnly) readonly style="background-color: #e9ecef;" @endif>
                                            @error('pdl') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </td>
                                        <td>
                                            <input type="number" step="0.1" placeholder="0" wire:model.lazy="pv" class="form-control form-control-sm text-center @error('pv') is-invalid @enderror" @if($isReadOnly) readonly style="background-color: #e9ecef;" @endif>
                                            @error('pv') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </td>
                                        <td>
                                            <input type="number" step="0.1" placeholder="0" wire:model.lazy="frame_a" class="form-control form-control-sm text-center @error('frame_a') is-invalid @enderror" @if($isReadOnly) readonly style="background-color: #e9ecef;" @endif>
                                            @error('frame_a') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </td>
                                        <td>
                                            <input type="number" step="0.1" placeholder="0" wire:model.lazy="frame_b" class="form-control form-control-sm text-center @error('frame_b') is-invalid @enderror" @if($isReadOnly) readonly style="background-color: #e9ecef;" @endif>
                                            @error('frame_b') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </td>
                                        <td>
                                            <input type="number" step="0.1" placeholder="0" wire:model.lazy="frame_d" class="form-control form-control-sm text-center @error('frame_d') is-invalid @enderror" @if($isReadOnly) readonly style="background-color: #e9ecef;" @endif>
                                            @error('frame_d') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </td>
                                        <td>
                                            <input type="number" step="0.1" placeholder="0" wire:model.lazy="frame_diag" class="form-control form-control-sm text-center @error('frame_diag') is-invalid @enderror" @if($isReadOnly) readonly style="background-color: #e9ecef;" @endif>
                                            @error('frame_diag') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </td>
                                        <td>
                                            <select wire:model.lazy="frame" class="form-select form-select-sm @error('frame') is-invalid @enderror" @if($isReadOnly) disabled style="background-color: #e9ecef;" @endif>
                                                <option value="">-- Pilih --</option>
                                                <option value="Full Metal">Full Metal</option>
                                                <option value="Full Plastik">Full Plastik</option>
                                                <option value="Nylon">Nylon</option>
                                                <option value="Rimless">Rimless</option>
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
        @endif <!-- End if Form Resep -->

    </div> <!-- end card-body -->
</div>
</div>
