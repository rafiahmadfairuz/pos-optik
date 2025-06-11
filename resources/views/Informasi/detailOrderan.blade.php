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

    <div class="container-fluid py-3">
        <h2 class="fw-bold"><i class="bi bi-card-list me-2"></i>Detail Orderan</h2>

        <div class="row g-3">

            <!-- Shopping Cart -->
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header fw-bold text-decoration-underline d-flex align-items-center gap-2">
                        <i class="bi bi-cart4"></i> Shopping Cart
                    </div>
                    <table class="table table-hover align-middle p-1">
                        <thead class="table-light">
                            <tr>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td>
                                        @php
                                            $type = class_basename($item->itemable_type); // contoh: 'Frame', 'Lensa'
                                            $merk = $item->itemable->merk ?? 'Unknown Merk';
                                            $tipeProduk = $item->itemable->type ?? null; // optional, kalau ada field type di model
                                        @endphp

                                      

                                        {{ $merk }}
                                        @if ($tipeProduk)
                                            <small class="text-muted">({{ $type }} -
                                                {{ $tipeProduk }})</small>
                                        @else
                                            <small class="text-muted">({{ $type }})</small>
                                        @endif
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Resep Kacamata -->
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header fw-bold text-decoration-underline d-flex align-items-center gap-2">
                        <i class="bi bi-journal-text"></i> Resep Kacamata
                    </div>
                    <div class="card-body">
                        <form class="row g-2">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th></th>
                                                <th colspan="4" class="">Left Side</th>
                                                <th colspan="4" class="">Right Side</th>
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
                                                <td><input type="text" class="form-control"
                                                        value="{{ $order->resep->left_sph_d }}"></td>
                                                <td><input type="text" class="form-control"
                                                        value="{{ $order->resep->left_cyl_d }}"></td>
                                                <td><input type="text" class="form-control"
                                                        value="{{ $order->resep->left_axis_d }}"></td>
                                                <td><input type="text" class="form-control"
                                                        value="{{ $order->resep->left_va_d }}"></td>
                                                <td><input type="text" class="form-control"
                                                        value="{{ $order->resep->right_sph_d }}"></td>
                                                <td><input type="text" class="form-control"
                                                        value="{{ $order->resep->right_cyl_d }}"></td>
                                                <td><input type="text" class="form-control"
                                                        value="{{ $order->resep->right_axis_d }}"></td>
                                                <td><input type="text" class="form-control"
                                                        value="{{ $order->resep->right_va_d }}"></td>
                                            </tr>

                                            <tr>
                                                <td>ADD</td>
                                                <td colspan="4"><input type="text" class="form-control"
                                                        value="{{ $order->resep->add_left }}"></td>
                                                <td colspan="4"><input type="text" class="form-control"
                                                        value="{{ $order->resep->add_right }}"></td>
                                            </tr>
                                            <tr>
                                                <td>PD</td>
                                                <td colspan="4"><input type="text" class="form-control"
                                                        value="{{ $order->resep->pd_left }}"></td>
                                                <td colspan="4"><input type="text" class="form-control"
                                                        value="{{ $order->resep->pd_right }}"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label"><i class="bi bi-pencil-square me-1"></i>Extra Notes</label>
                                <textarea class="form-control" style="background-color: #f8f9fa; color: #495057;">{{ $order->resep->notes }}</textarea>
                            </div>
                        </form>
                    </div>

                    <hr>

                    <!-- Transaction Details -->
                    <div class="card-header fw-bold text-decoration-underline d-flex align-items-center gap-2">
                        <i class="bi bi-receipt-cutoff"></i> Transaction Details
                    </div>
                    <div class="card-body">
                        <form class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-info-circle me-1"></i>Order Status</label>
                                <select class="form-select" disabled>
                                    <option {{ $order->status == 'Proses' ? 'selected' : '' }}>Proses</option>
                                    <option {{ $order->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-calendar-event me-1"></i>Order Date</label>
                                <input type="date" class="form-control" value="{{ $order->order_date }}" disabled>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-calendar-check me-1"></i>Order Completed
                                    Date</label>
                                <input type="date" class="form-control" value="{{ $order->completed_date }}"
                                    disabled>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i
                                        class="bi bi-credit-card-2-front me-1"></i>Pembayaran</label>
                                <select class="form-select" disabled>
                                    <option {{ $order->payment_type == 'DP' ? 'selected' : '' }}>DP</option>
                                    <option {{ $order->payment_type == 'Pelunasan' ? 'selected' : '' }}>Pelunasan
                                    </option>
                                    <option {{ $order->payment_type == 'Asuransi' ? 'selected' : '' }}>Asuransi
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-person-badge me-1"></i>Nama
                                    Optometrist</label>
                                <input type="text" class="form-control"
                                    value="{{ $order->optometrist->name ?? '-' }}" disabled>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-wallet2 me-1"></i>Payment Method</label>
                                <select class="form-select" disabled>
                                    <option {{ $order->payment_method == 'Cash' ? 'selected' : '' }}>Cash</option>
                                    <option {{ $order->payment_method == 'Card' ? 'selected' : '' }}>Card</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-cash-stack me-1"></i>Payment Status</label>
                                <select class="form-select" disabled>
                                    <option {{ $order->payment_status == 'Paid' ? 'selected' : '' }}>Paid</option>
                                    <option {{ $order->payment_status == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><i class="bi bi-cash me-1"></i>Customer Paying</label>
                                <input type="number" class="form-control" value="{{ $order->customer_paid }}"
                                    disabled>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <div class="card mt-3 col-lg-4 col-md-6 col-sm-12 shadow rounded-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-decoration-underline">
                        <i class="bi bi-receipt-cutoff me-2"></i>Bill Summary
                    </span>
                    <small class="text-muted">{{ $order->order_date }}</small>
                </div>
                <div class="card-body fs-5">
                    <p>
                        <i class="bi bi-cash-stack me-2"></i>
                        Total:
                        <strong class="float-end">Rp. {{ number_format($order->total, 0, ',', '.') }}</strong>
                    </p>
                    <p>
                        <i class="bi bi-shield-check me-2"></i>
                        Asuransi:
                        <strong class="float-end">Rp.
                            {{ number_format($order->asuransi->nominal, 0, ',', '.') }}</strong>
                    </p>
                    <hr>
                    <p>
                        <i class="bi bi-calculator me-2"></i>
                        Total Final:
                        <strong class="float-end">Rp. {{ number_format($order->perlu_dibayar, 0, ',', '.') }}</strong>
                    </p>
                    <p>
                        <i class="bi bi-wallet2 me-2"></i>
                        Dibayar Customer:
                        <strong class="float-end">
                            Rp.
                            {{ number_format((int) preg_replace('/[^\d]/', '', $order->customer_paying), 0, ',', '.') }}
                        </strong>
                    </p>
                    <hr>
                    <p>
                        <i class="bi bi-arrow-repeat me-2"></i>
                        Kembalian:
                        <strong class="float-end">Rp. {{ number_format($order->kembalian, 0, ',', '.') }}</strong>
                    </p>
                </div>
            </div>




        </div>
    </div>
</x-app>
