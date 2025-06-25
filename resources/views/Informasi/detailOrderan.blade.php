<x-app>
    @section('title', 'Detail Orderan')

    <style>
        .card-scroll {
            max-height: 200px;
            overflow-y: auto;
        }

        .table thead th {
            position: sticky;
            top: 0;
            background: #fff;
        }
    </style>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    @php
        $isDisabled = $order->order_status == 'complete' && (Auth::user()->role ?? '') != 'admin';
    @endphp

    <div class="container-fluid py-3">
        <h2 class="fw-bold"><i class="bi bi-card-list me-2"></i>Detail Orderan</h2>

        <div class="row g-3">
            {{-- Formulir utama untuk semua input yang dapat diupdate --}}
            <form action="{{ route('orderan.update', $order->id) }}" method="POST" class="col-12 row g-3">
                @csrf
                @method('PUT')

                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header fw-bold text-decoration-underline d-flex align-items-center gap-2">
                            <i class="bi bi-cart4"></i> Keranjang Belanja
                        </div>
                        <table class="table table-hover align-middle p-1">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Harga</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td>
                                            @php
                                                $type = class_basename($item->itemable_type ?? ''); // Safely get class_basename
                                                $merk = $item->itemable->merk ?? 'Merk Tidak Diketahui';
                                                $tipeProduk = $item->itemable->type ?? null;
                                            @endphp

                                            {{ $merk }}
                                            @if ($tipeProduk)
                                                <small class="text-muted">({{ $type }} -
                                                    {{ $tipeProduk }})</small>
                                            @else
                                                <small class="text-muted">({{ $type }})</small>
                                            @endif
                                        </td>
                                        <td>{{ $item->quantity ?? 0 }}</td>
                                        <td>Rp {{ number_format($item->price ?? 0, 0, ',', '.') }}</td>
                                        <td>Rp
                                            {{ number_format(($item->price ?? 0) * ($item->quantity ?? 0), 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-header fw-bold text-decoration-underline d-flex align-items-center gap-2">
                            <i class="bi bi-journal-text"></i> Resep Kacamata
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered text-center align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th></th>
                                                    <th colspan="4" class="">Sisi Kiri</th>
                                                    <th colspan="4" class="">Sisi Kanan</th>
                                                </tr>
                                                <tr>
                                                    <th></th>
                                                    <th>SPH</th>
                                                    <th>CYL</th>
                                                    <th>AXIS</th>
                                                    <th>VA</th>
                                                    <th>SPH</th>
                                                    <th>CYL</th>
                                                    <th>AXIS</th>
                                                    <th>VA</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>D</td>
                                                    <!-- LEFT SIDE -->
                                                    <td>
                                                        <input type="number" step="0.25" min="-20"
                                                            max="20" name="resep_left_sph_d"
                                                            class="form-control @error('resep_left_sph_d') is-invalid @enderror"
                                                            placeholder="SPH Contoh: -2.00"
                                                            value="{{ old('resep_left_sph_d', $order->resep?->left_sph_d) }}"
                                                            {{ $isDisabled ? 'disabled' : '' }}>
                                                        @error('resep_left_sph_d')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </td>

                                                    <td>
                                                        <input type="number" step="0.25" min="-6"
                                                            max="6" name="resep_left_cyl_d"
                                                            class="form-control @error('resep_left_cyl_d') is-invalid @enderror"
                                                            placeholder="CYL Contoh: -1.25"
                                                            value="{{ old('resep_left_cyl_d', $order->resep?->left_cyl_d) }}"
                                                            {{ $isDisabled ? 'disabled' : '' }}>
                                                        @error('resep_left_cyl_d')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </td>

                                                    <td>
                                                        <input type="number" step="1" min="0"
                                                            max="180" name="resep_left_axis_d"
                                                            class="form-control @error('resep_left_axis_d') is-invalid @enderror"
                                                            placeholder="Axis Contoh: 90"
                                                            value="{{ old('resep_left_axis_d', $order->resep?->left_axis_d) }}"
                                                            {{ $isDisabled ? 'disabled' : '' }}>
                                                        @error('resep_left_axis_d')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </td>

                                                    <td>
                                                        <input type="text" name="resep_left_va_d"
                                                            class="form-control @error('resep_left_va_d') is-invalid @enderror"
                                                            placeholder="VA Contoh: 6/6 atau 20/20"
                                                            value="{{ old('resep_left_va_d', $order->resep?->left_va_d) }}"
                                                            {{ $isDisabled ? 'disabled' : '' }}>
                                                        @error('resep_left_va_d')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </td>

                                                    <!-- RIGHT SIDE -->
                                                    <td>
                                                        <input type="number" step="0.25" min="-20"
                                                            max="20" name="resep_right_sph_d"
                                                            class="form-control @error('resep_right_sph_d') is-invalid @enderror"
                                                            placeholder="SPH Contoh: -2.00"
                                                            value="{{ old('resep_right_sph_d', $order->resep?->right_sph_d) }}"
                                                            {{ $isDisabled ? 'disabled' : '' }}>
                                                        @error('resep_right_sph_d')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </td>

                                                    <td>
                                                        <input type="number" step="0.25" min="-6"
                                                            max="6" name="resep_right_cyl_d"
                                                            class="form-control @error('resep_right_cyl_d') is-invalid @enderror"
                                                            placeholder="CYL Contoh: -1.25"
                                                            value="{{ old('resep_right_cyl_d', $order->resep?->right_cyl_d) }}"
                                                            {{ $isDisabled ? 'disabled' : '' }}>
                                                        @error('resep_right_cyl_d')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </td>

                                                    <td>
                                                        <input type="number" step="1" min="0"
                                                            max="180" name="resep_right_axis_d"
                                                            class="form-control @error('resep_right_axis_d') is-invalid @enderror"
                                                            placeholder="Axis Contoh: 90"
                                                            value="{{ old('resep_right_axis_d', $order->resep?->right_axis_d) }}"
                                                            {{ $isDisabled ? 'disabled' : '' }}>
                                                        @error('resep_right_axis_d')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </td>

                                                    <td>
                                                        <input type="text" name="resep_right_va_d"
                                                            class="form-control @error('resep_right_va_d') is-invalid @enderror"
                                                            placeholder="VA Contoh: 6/6 atau 20/20"
                                                            value="{{ old('resep_right_va_d', $order->resep?->right_va_d) }}"
                                                            {{ $isDisabled ? 'disabled' : '' }}>
                                                        @error('resep_right_va_d')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                </tr>

                                                <!-- ADD -->
                                                <tr>
                                                    <td>ADD</td>
                                                    <td colspan="4">
                                                        <input type="number" step="0.25" min="0.75"
                                                            max="3.5" name="resep_add_left"
                                                            class="form-control @error('resep_add_left') is-invalid @enderror"
                                                            placeholder="ADD Contoh: +1.00"
                                                            value="{{ old('resep_add_left', $order->resep?->add_left) }}"
                                                            {{ $isDisabled ? 'disabled' : '' }}>
                                                        @error('resep_add_left')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </td>

                                                    <td colspan="4">
                                                        <input type="number" step="0.25" min="0.75"
                                                            max="3.5" name="resep_add_right"
                                                            class="form-control @error('resep_add_right') is-invalid @enderror"
                                                            placeholder="ADD Contoh: +1.00"
                                                            value="{{ old('resep_add_right', $order->resep?->add_right) }}"
                                                            {{ $isDisabled ? 'disabled' : '' }}>
                                                        @error('resep_add_right')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                </tr>

                                                <!-- PD -->
                                                <tr>
                                                    <td>PD</td>
                                                    <td colspan="4">
                                                        <input type="number" step="0.5" min="25"
                                                            max="40" name="resep_pd_left"
                                                            class="form-control @error('resep_pd_left') is-invalid @enderror"
                                                            placeholder="PD Left Contoh: 32"
                                                            value="{{ old('resep_pd_left', $order->resep?->pd_left) }}"
                                                            {{ $isDisabled ? 'disabled' : '' }}>
                                                        @error('resep_pd_left')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </td>

                                                    <td colspan="4">
                                                        <input type="number" step="0.5" min="25"
                                                            max="40" name="resep_pd_right"
                                                            class="form-control @error('resep_pd_right') is-invalid @enderror"
                                                            placeholder="PD Right Contoh: 32"
                                                            value="{{ old('resep_pd_right', $order->resep?->pd_right) }}"
                                                            {{ $isDisabled ? 'disabled' : '' }}>
                                                        @error('resep_pd_right')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                {{-- Umur --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Umur</label>
                                    <input type="number" name="umur"
                                        class="form-control @error('umur') is-invalid @enderror"
                                        value="{{ old('umur', $order->resep?->umur) }}"
                                        {{ $isDisabled ? 'disabled' : '' }}>
                                    @error('umur')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Gender --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-select @error('gender') is-invalid @enderror"
                                        {{ $isDisabled ? 'disabled' : '' }}>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="male"
                                            {{ old('gender', $order->resep?->gender) == 'male' ? 'selected' : '' }}>
                                            Laki-laki</option>
                                        <option value="female"
                                            {{ old('gender', $order->resep?->gender) == 'female' ? 'selected' : '' }}>
                                            Perempuan</option>
                                        <option value="other"
                                            {{ old('gender', $order->resep?->gender) == 'other' ? 'selected' : '' }}>
                                            Lainnya</option>
                                    </select>
                                    @error('gender')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Alamat --}}
                                <div class="col-12 mb-3">
                                    <label class="form-label">Alamat</label>
                                    <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3"
                                        {{ $isDisabled ? 'disabled' : '' }}>{{ old('alamat', $order->resep?->alamat) }}</textarea>
                                    @error('alamat')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label"><i class="bi bi-pencil-square me-1"></i>Catatan
                                        Tambahan</label>
                                    <textarea name="resep_notes" class="form-control" {{ $isDisabled ? 'disabled' : '' }}>{{ $order->resep?->notes ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4 shadow-sm border-0">
                        <div class="card-header fw-bold text-decoration-underline">Detail Transaksi</div>
                        <div class="card-body">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="form-label">Tanggal Order</label>
                                    <input type="date" name="order_date"
                                        value="{{ old('order_date', $order->order_date ?? '') }}"
                                        class="form-control @error('order_date') is-invalid @enderror"
                                        {{ $isDisabled ? 'disabled' : '' }}>
                                    @error('order_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Tanggal Selesai Order</label>
                                    <input type="date" name="complete_date"
                                        value="{{ old('complete_date', $order->complete_date ?? '') }}"
                                        class="form-control @error('complete_date') is-invalid @enderror"
                                        {{ $isDisabled ? 'disabled' : '' }}>
                                    @error('complete_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Nama Optometris</label>
                                    <select name="staff_id"
                                        class="form-select @error('staff_id') is-invalid @enderror"
                                        {{ $isDisabled ? 'disabled' : '' }}>
                                        <option value="">-- Pilih --</option>
                                        @foreach ($optometristList as $staff)
                                            <option value="{{ $staff->id }}"
                                                {{ old('staff_id', $order->staff_id) == $staff->id ? 'selected' : '' }}>
                                                {{ $staff->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('staff_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Pembayaran</label>
                                    <select name="payment_type" id="paymentType"
                                        class="form-select @error('payment_type') is-invalid @enderror"
                                        {{ $isDisabled ? 'disabled' : '' }}>
                                        <option value="">-- Pilih --</option>
                                        <option value="pelunasan"
                                            {{ old('payment_type', $order->payment_type) == 'pelunasan' ? 'selected' : '' }}>
                                            Pelunasan</option>
                                        <option value="asuransi"
                                            {{ old('payment_type', $order->payment_type) == 'asuransi' ? 'selected' : '' }}>
                                            Asuransi</option>
                                    </select>
                                    @error('payment_type')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-4" id="conAsuransi"
                                    style="{{ old('payment_type', $order->payment_type) == 'asuransi' ? '' : 'display: none;' }}">
                                    <label class="form-label">Detail Asuransi</label>
                                    <select name="asuransi_id" id="asuransiDetail"
                                        class="form-select @error('asuransi_id') is-invalid @enderror"
                                        {{ $isDisabled ? 'disabled' : '' }}>
                                        <option value="">-- Pilih --</option>
                                        @foreach ($asuransiList as $asuransiItem)
                                            <option value="{{ $asuransiItem->id }}"
                                                {{ old('asuransi_id', $order->asuransi_id) == $asuransiItem->id ? 'selected' : '' }}>
                                                {{ $asuransiItem->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('asuransi_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Status Order</label>
                                    <select name="order_status"
                                        class="form-select @error('order_status') is-invalid @enderror"
                                        {{ $isDisabled ? 'disabled' : '' }}>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="pending"
                                            {{ old('order_status', $order->order_status) == 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="complete"
                                            {{ old('order_status', $order->order_status) == 'complete' ? 'selected' : '' }}>
                                            Complete</option>
                                    </select>
                                    @error('order_status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Metode Pembayaran</label>
                                    <select name="payment_method"
                                        class="form-select @error('payment_method') is-invalid @enderror"
                                        {{ $isDisabled ? 'disabled' : '' }}>
                                        <option value="">-- Pilih --</option>
                                        <option value="cash"
                                            {{ old('payment_method', $order->payment_method) == 'cash' ? 'selected' : '' }}>
                                            Tunai</option>
                                        <option value="card"
                                            {{ old('payment_method', $order->payment_method) == 'card' ? 'selected' : '' }}>
                                            Kartu</option>
                                    </select>
                                    @error('payment_method')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Status Pembayaran</label>
                                    <select name="payment_status"
                                        class="form-select @error('payment_status') is-invalid @enderror"
                                        {{ $isDisabled ? 'disabled' : '' }}>
                                        <option value="">-- Pilih --</option>
                                        <option value="paid"
                                            {{ old('payment_status', $order->payment_status) == 'paid' ? 'selected' : '' }}>
                                            Sudah Dibayar</option>
                                        <option value="DP"
                                            {{ old('payment_status', $order->payment_status) == 'DP' ? 'selected' : '' }}>
                                            DP</option>
                                        <option value="unpaid"
                                            {{ old('payment_status', $order->payment_status) == 'unpaid' ? 'selected' : '' }}>
                                            Belum Dibayar</option>
                                    </select>
                                    @error('payment_status')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Diskon</label>
                                    <input type="text" name="diskon"
                                        value="{{ old('diskon', number_format(floatval(str_replace('.', '', $order->diskon ?? 0)), 0, ',', '.')) }}"
                                        oninput="formatRupiah(this)"
                                        class="form-control @error('diskon') is-invalid @enderror"
                                        {{ $isDisabled ? 'disabled' : '' }}>
                                    @error('diskon')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Pelanggan Membayar</label>
                                    <input type="text" name="customer_paying"
                                        value="{{ old('customer_paying', number_format($order->customer_paying ?? 0, 0, ',', '.')) }}"
                                        oninput="formatRupiah(this)"
                                        class="form-control @error('customer_paying') is-invalid @enderror"
                                        {{ $isDisabled ? 'disabled' : '' }}>
                                    @error('customer_paying')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Ringkasan Tagihan (tetap di luar formulir karena hanya tampilan) --}}
                <div class="card mt-3 col-lg-4 col-md-6 col-sm-12 shadow rounded-4 position-relative overflow-hidden">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-decoration-underline">
                            <i class="bi bi-receipt-cutoff me-2"></i>Ringkasan Tagihan
                        </span>
                        <small class="text-muted">{{ $order->order_date ?? 'N/A' }}</small>
                    </div>

                    <div class="card-body fs-5">
                        {{-- Total --}}
                        <p>
                            <i class="bi bi-cash-stack me-2"></i>
                            Total:
                            <strong class="float-end">Rp.
                                {{ number_format((int) ($order->total ?? 0), 0, ',', '.') }}
                            </strong>
                        </p>

                        {{-- Diskon --}}
                        <p>
                            <i class="bi bi-tag me-2"></i>
                            Diskon:
                            <strong class="float-end">Rp.
                                {{ number_format((int) ($order->diskon ?? 0), 0, ',', '.') }}
                            </strong>
                        </p>

                        {{-- Asuransi --}}
                        <p>
                            <i class="bi bi-shield-check me-2"></i>
                            Asuransi:
                            <strong class="float-end">Rp.
                                {{ number_format((int) ($order->asuransi?->nominal ?? 0), 0, ',', '.') }}
                            </strong>
                        </p>

                        <hr>

                        {{-- Total Final --}}
                        <p>
                            <i class="bi bi-calculator me-2"></i>
                            Total Final:
                            <strong class="float-end">Rp.
                                {{ number_format((int) ($order->perlu_dibayar ?? 0), 0, ',', '.') }}
                            </strong>
                        </p>

                        {{-- Dibayar --}}
                        <p>
                            <i class="bi bi-wallet2 me-2"></i>
                            Dibayar:
                            <strong class="float-end">Rp.
                                {{ number_format((int) ($order->customer_paying ?? 0), 0, ',', '.') }}
                            </strong>
                        </p>

                        <hr>

                        {{-- Kurang Bayar --}}
                        <p>
                            <i class="bi bi-dash-circle me-2"></i>
                            Kurang Bayar:
                            <strong class="float-end">Rp.
                                {{ number_format((int) ($order->kurang_bayar ?? 0), 0, ',', '.') }}
                            </strong>
                        </p>

                        {{-- Kembalian --}}
                        <p>
                            <i class="bi bi-arrow-repeat me-2"></i>
                            Kembalian:
                            <strong class="float-end">Rp.
                                {{ number_format((int) ($order->kembalian ?? 0), 0, ',', '.') }}
                            </strong>
                        </p>

                        {{-- Tombol Cetak Nota --}}
                        <div class="d-flex justify-content-center mt-4">
                            <a href="{{ route('cetak.nota', $order->id) }}" target="_blank"
                                class="btn btn-success rounded-pill shadow-sm">
                                <i class="bi bi-printer me-1"></i> Cetak Nota
                            </a>
                        </div>
                    </div>

                    {{-- Badge "LUNAS" --}}
                    @if ($order->payment_status === 'paid')
                        <div class="position-absolute bottom-0 start-0 w-100 text-center py-3 bg-success text-white fw-bold fs-4 rounded-bottom-4 shadow-sm"
                            style="letter-spacing: 2px;">
                            <i class="bi bi-patch-check-fill me-2"></i>LUNAS
                        </div>
                    @endif
                </div>




                {{-- Tombol submit tunggal di akhir formulir --}}
                <div class="col-md-4 d-flex justify-content-start  align-items-start mt-3">
                    @if (($order->order_status ?? 'pending') == 'pending' || (Auth::user()->role ?? '') == 'admin')
                        <button type="submit" class="btn btn-primary px-4" {{ $isDisabled ? 'disabled' : '' }}>
                            <i class="bi bi-check-circle me-1"></i>
                            {{ ($order->order_status ?? 'pending') == 'pending' ? 'Selesai' : 'Simpan Perubahan' }}
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>


    <script>
        function formatRupiah(el) {
            let value = el.value.replace(/[^\d,]/g, '');
            let parts = value.split(',');
            let number = parts[0];
            let formatted = number.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            el.value = parts.length > 1 ? formatted + ',' + parts[1] : formatted;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const paymentSelect = document.getElementById('paymentType');
            const asuransiDetailContainer = document.getElementById('conAsuransi');

            function toggleAsuransiDetail() {
                if (!paymentSelect || !asuransiDetailContainer) return;

                const isAsuransiSelected = paymentSelect.value === 'asuransi';

                if (isAsuransiSelected) {
                    asuransiDetailContainer.style.display = '';
                } else {
                    asuransiDetailContainer.style.display = 'none';
                }
            }

            toggleAsuransiDetail();

            if (paymentSelect) {
                paymentSelect.addEventListener('change', toggleAsuransiDetail);
            }

            @if ($isDisabled)
                const inputs = document.querySelectorAll('input, select, textarea');
                inputs.forEach(input => {
                    input.disabled = true;
                });
            @endif
        });
    </script>
</x-app>
