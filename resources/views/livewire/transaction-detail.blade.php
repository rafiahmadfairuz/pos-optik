<div class="col-md-8">
    <form wire:submit.prevent="submit">

        <div class="card mb-4">
            <div class="card-header fw-bold text-decoration-underline">Resep Kacamata</div>
            <div class="card-body">
                <div class="row g-2">

                    <div class="col-md-12">
                        <label class="form-label">Prescription</label>
                        <div class="table-responsive">
                            <table class="table table-bordered text-center align-middle">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th colspan="4">Right Side</th>
                                        <th colspan="4">Left Side</th>
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
                                        <td><input type="text" wire:model.lazy="right_sph_d"
                                                class="form-control @error('right_sph_d') is-invalid @enderror"></td>
                                        <td><input type="text" wire:model.lazy="right_cyl_d"
                                                class="form-control @error('right_cyl_d') is-invalid @enderror"></td>
                                        <td><input type="text" wire:model.lazy="right_axis_d"
                                                class="form-control @error('right_axis_d') is-invalid @enderror"></td>
                                        <td><input type="text" wire:model.lazy="right_va_d"
                                                class="form-control @error('right_va_d') is-invalid @enderror"></td>
                                        <td><input type="text" wire:model.lazy="left_sph_d"
                                                class="form-control @error('left_sph_d') is-invalid @enderror"></td>
                                        <td><input type="text" wire:model.lazy="left_cyl_d"
                                                class="form-control @error('left_cyl_d') is-invalid @enderror"></td>
                                        <td><input type="text" wire:model.lazy="left_axis_d"
                                                class="form-control @error('left_axis_d') is-invalid @enderror"></td>
                                        <td><input type="text" wire:model.lazy="left_va_d"
                                                class="form-control @error('left_va_d') is-invalid @enderror"></td>
                                    </tr>
                                    <tr>
                                        <td>ADD</td>
                                        <td colspan="4"><input type="text" wire:model.lazy="add_right"
                                                class="form-control @error('add_right') is-invalid @enderror"></td>
                                        <td colspan="4"><input type="text" wire:model.lazy="add_left"
                                                class="form-control @error('add_left') is-invalid @enderror"></td>
                                    </tr>
                                    <tr>
                                        <td>PD</td>
                                        <td colspan="4"><input type="text" wire:model.lazy="pd_right"
                                                class="form-control @error('pd_right') is-invalid @enderror"></td>
                                        <td colspan="4"><input type="text" wire:model.lazy="pd_left"
                                                class="form-control @error('pd_left') is-invalid @enderror"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Extra Notes</label>
                        <textarea wire:model.lazy="notes" class="form-control @error('notes') is-invalid @enderror"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header fw-bold text-decoration-underline">Transaction Details</div>
            <div class="card-body">
                <div class="row g-2">

                    <input type="hidden" id="cabang_id_input" wire:model="cabang_id">

                    <div class="col-md-4">
                        <label class="form-label">Order Status</label>
                        <select wire:model="order_status"
                            class="form-select @error('order_status') is-invalid @enderror">
                            <option value="">-- Pilih Status --</option>
                            <option value="pending">Pending</option>
                            <option value="complete">Complete</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Order Date</label>
                        <input type="date" wire:model="order_date"
                            class="form-control @error('order_date') is-invalid @enderror">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Order Completed Date</label>
                        <input type="date" wire:model="complete_date"
                            class="form-control @error('complete_date') is-invalid @enderror">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Pembayaran</label>
                        <select id="paymentType" wire:model="payment_type"
                            class="form-select @error('payment_type') is-invalid @enderror">
                            <option value="">-- Pilih --</option>
                            <option value="DP">DP</option>
                            <option value="pelunasan">Pelunasan</option>
                            <option value="asuransi">Asuransi</option>
                        </select>
                    </div>

                    <div class="col-md-4" id="asuransiDetail" >
                        <label class="form-label">Detail Asuransi</label>
                        <select wire:model="asuransi" class="form-select @error('asuransi') is-invalid @enderror">
                            <option value="">-- Pilih --</option>
                            @foreach ($asuransiList as $asuransiItem)
                                <option value="{{ $asuransiItem->id }}">{{ $asuransiItem->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Nama Optometrist {{ session("cabang_id") }}</label>
                        <select wire:model="optometrist_name"
                            class="form-select @error('optometrist_name') is-invalid @enderror">
                            <option value="">-- Pilih --</option>
                            @foreach ($optometristList as $staff)
                                <option value="{{ $staff->name }}">{{ $staff->name }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="col-md-4">
                        <label class="form-label">Payment Method</label>
                        <select wire:model="payment_method"
                            class="form-select @error('payment_method') is-invalid @enderror">
                            <option value="">-- Pilih --</option>
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Payment Status</label>
                        <select wire:model="payment_status"
                            class="form-select @error('payment_status') is-invalid @enderror">
                            <option value="">-- Pilih --</option>
                            <option value="paid">Paid</option>
                            <option value="unpaid">Unpaid</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Customer Paying</label>
                        <input type="number" wire:model="customer_paying"
                            class="form-control @error('customer_paying') is-invalid @enderror">
                    </div>



                </div>
            </div>
        </div>


    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentSelect = document.getElementById('paymentType');
        const asuransiDetail = document.getElementById('asuransiDetail');
        const cabangId = localStorage.getItem('cabang_id');

        function toggleAsuransiDetail() {
            if (paymentSelect.value == 'asuransi') {
                asuransiDetail.style.display = 'block';
            } else {
                asuransiDetail.style.display = 'none';
            }
        }

        toggleAsuransiDetail();
        paymentSelect.addEventListener('change', toggleAsuransiDetail);
    });
</script>
