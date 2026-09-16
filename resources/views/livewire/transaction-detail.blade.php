<div class="col-md-12">
    <form wire:submit.prevent="submit">



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
                        <label class="form-label">Nama Kasir</label>
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

        <div class="text-end mt-4" style="position: fixed; bottom: 20px; left: 300px; z-index: 999;">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-circle me-1"></i> Selesai woi
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

        toggleAsuransiDetail();
        paymentSelect.addEventListener('change', toggleAsuransiDetail);

        document.addEventListener('livewire:load', toggleAsuransiDetail);
        document.addEventListener('livewire:updated', toggleAsuransiDetail);
    });
</script>
