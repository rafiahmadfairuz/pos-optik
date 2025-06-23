<div class="col-md-8">
    <form wire:submit.prevent="submit">

        <div class="card mb-4">
            <div class="card-header fw-bold text-decoration-underline">Resep Kacamata</div>
            <div class="card-body">
                <div class="row g-2">

                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center align-middle">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th colspan="4">Left Side</th>
                                        <th colspan="4">Right Side</th>
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
                                            <input type="text" wire:model.lazy="left_sph_d"
                                                class="form-control @error('left_sph_d') is-invalid @enderror">
                                            @error('left_sph_d')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="text" wire:model.lazy="left_cyl_d"
                                                class="form-control @error('left_cyl_d') is-invalid @enderror">
                                            @error('left_cyl_d')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="text" wire:model.lazy="left_axis_d"
                                                class="form-control @error('left_axis_d') is-invalid @enderror">
                                            @error('left_axis_d')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="text" wire:model.lazy="left_va_d"
                                                class="form-control @error('left_va_d') is-invalid @enderror">
                                            @error('left_va_d')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <!-- RIGHT SIDE -->
                                        <td>
                                            <input type="text" wire:model.lazy="right_sph_d"
                                                class="form-control @error('right_sph_d') is-invalid @enderror">
                                            @error('right_sph_d')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="text" wire:model.lazy="right_cyl_d"
                                                class="form-control @error('right_cyl_d') is-invalid @enderror">
                                            @error('right_cyl_d')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="text" wire:model.lazy="right_axis_d"
                                                class="form-control @error('right_axis_d') is-invalid @enderror">
                                            @error('right_axis_d')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="text" wire:model.lazy="right_va_d"
                                                class="form-control @error('right_va_d') is-invalid @enderror">
                                            @error('right_va_d')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>ADD</td>
                                        <!-- LEFT THEN RIGHT -->
                                        <td colspan="4">
                                            <input type="text" wire:model.lazy="add_left"
                                                class="form-control @error('add_left') is-invalid @enderror">
                                            @error('add_left')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td colspan="4">
                                            <input type="text" wire:model.lazy="add_right"
                                                class="form-control @error('add_right') is-invalid @enderror">
                                            @error('add_right')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>PD</td>
                                        <!-- LEFT THEN RIGHT -->
                                        <td colspan="4">
                                            <input type="text" wire:model.lazy="pd_left"
                                                class="form-control @error('pd_left') is-invalid @enderror">
                                            @error('pd_left')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td colspan="4">
                                            <input type="text" wire:model.lazy="pd_right"
                                                class="form-control @error('pd_right') is-invalid @enderror">
                                            @error('pd_right')
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
                            <label for="umur" class="form-label">Umur</label>
                            <input type="number" wire:model.lazy="umur" id="umur"
                                class="form-control @error('umur') is-invalid @enderror">
                            @error('umur')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Gender --}}
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select wire:model.lazy="gender" id="gender"
                                class="form-select @error('gender') is-invalid @enderror">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="male">Laki-laki</option>
                                <option value="female">Perempuan</option>
                                <option value="other">Lainnya</option>
                            </select>
                            @error('gender')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Alamat --}}
                        <div class="col-12 mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea wire:model.lazy="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror"
                                rows="3"></textarea>
                            @error('alamat')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                    <div class="col-md-12">
                        <label class="form-label">Extra Notes</label>
                        <textarea wire:model.lazy="notes" class="form-control @error('notes') is-invalid @enderror"></textarea>
                        @error('notes')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header fw-bold text-decoration-underline">Transaction Details</div>
            <div class="card-body">
                <div class="row g-2">

                    <div class="col-md-4">
                        <label class="form-label">Order Date</label>
                        <input type="date" wire:model="order_date"
                            class="form-control @error('order_date') is-invalid @enderror">
                        @error('order_date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Order Completed Date</label>
                        <input type="date" wire:model="complete_date"
                            class="form-control @error('complete_date') is-invalid @enderror">
                        @error('complete_date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Nama Optometrist</label>
                        <select wire:model="optometrist_id"
                            class="form-select @error('optometrist_id') is-invalid @enderror">
                            <option value="">-- Pilih --</option>
                            @foreach ($optometristList as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                            @endforeach
                        </select>
                        @error('optometrist_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Pembayaran</label>
                        <select id="paymentType" wire:model.lazy="payment_type"
                            class="form-select @error('payment_type') is-invalid @enderror">
                            <option value="" default>-- Pilih --</option>
                            <option value="pelunasan">Pelunasan</option>
                            <option value="asuransi">Asuransi</option>
                        </select>
                        @error('payment_type')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    @if ($payment_type == 'asuransi')
                        <div class="col-md-4">
                            <label class="form-label">Detail Asuransi</label>
                            <select wire:model.lazy="asuransi"
                                class="form-select @error('asuransi') is-invalid @enderror">
                                <option value="">-- Pilih --</option>
                                @foreach ($asuransiList as $asuransiItem)
                                    <option value="{{ $asuransiItem->id }}">{{ $asuransiItem->nama }}</option>
                                @endforeach
                            </select>
                            @error('asuransi')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif


                    <div class="col-md-4">
                        <label class="form-label">Order Status</label>
                        <select wire:model="order_status"
                            class="form-select @error('order_status') is-invalid @enderror"
                            @if ($forcePendingStatus) disabled @endif>
                            <option value="">-- Pilih Status --</option>
                            <option value="pending">Pending</option>
                            <option value="complete">Complete</option>
                        </select>
                        @if ($forcePendingStatus)
                            <small class="text-muted fst-italic">Status dikunci ke Pending karena ada barang yang
                                kurang stok.</small>
                        @endif
                        @error('order_status')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>



                    <div class="col-md-4">
                        <label class="form-label">Payment Method</label>
                        <select wire:model="payment_method"
                            class="form-select @error('payment_method') is-invalid @enderror">
                            <option value="">-- Pilih --</option>
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                        </select>
                        @error('payment_method')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Payment Status</label>
                        <select wire:model="payment_status"
                            class="form-select @error('payment_status') is-invalid @enderror">
                            <option value="">-- Pilih --</option>
                            <option value="paid">Paid</option>
                            <option value="DP">DP</option>
                            <option value="unpaid">Unpaid</option>
                        </select>
                        @error('payment_status')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Diskon</label>
                        <input type="text" wire:model.lazy="diskon" oninput="formatRupiah(this)"
                            class="form-control
                            @error('diskon') is-invalid @enderror">
                        @error('diskon')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Customer Paying</label>
                        <input type="text" wire:model.lazy="customer_paying" oninput="formatRupiah(this)"
                            class="form-control
                            @error('customer_paying') is-invalid @enderror">
                        @error('customer_paying')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end mt-4">
            <div class="d-flex flex-wrap gap-2 justify-content-start">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-circle me-1"></i> Selesai
                </button>
            </div>
        </div>

    </form>
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
        const asuransiDetail = document.getElementById('asuransiDetail');

        function toggleAsuransiDetail() {
            if (!paymentSelect || !asuransiDetail) return;

            const selectedValue = paymentSelect.value?.trim();

            const isAsuransiSelected = selectedValue === 'asuransi';

            if (isAsuransiSelected) {
                asuransiDetail.classList.remove('d-none');
            } else {
                asuransiDetail.classList.add('d-none');
            }
        }

        // Jalankan saat pertama kali load
        toggleAsuransiDetail();
        paymentSelect.addEventListener('change', toggleAsuransiDetail);

        // Tambahan: tangani render ulang oleh Livewire
        document.addEventListener('livewire:load', toggleAsuransiDetail);
        document.addEventListener('livewire:updated', toggleAsuransiDetail);
    });
</script>
