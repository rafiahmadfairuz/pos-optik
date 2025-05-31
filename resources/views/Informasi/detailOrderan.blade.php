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

    <!-- Pastikan Bootstrap Icons sudah include di head kamu -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<div class="container-fluid py-3">
         <h2 class="fw-bold "><i class="bi bi-card-list me-2 "></i>Detail Orderan</h2>


    <div class="row g-3">
        <!-- Shopping Cart -->
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header fw-bold text-decoration-underline d-flex align-items-center gap-2 ">
                    <i class="bi bi-cart4"></i> Shopping Cart
                </div>
                    <table class="table table-hover align-middle p-1">
                        <thead class="table-light">
                            <tr>
                                <th><i class="bi  me-1"></i>Item</th>
                                <th><i class="bi  me-1"></i>Qty</th>
                                <th><i class=" me-1"></i>Price</th>
                                <th><i class="bi  me-1"></i>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><i class="bi bi-eye me-1 text-info"></i>Frame Kacamata A</td>
                                <td>1</td>
                                <td>Rp 350.000</td>
                                <td>Rp 350.000</td>
                            </tr>
                            <tr>
                                <td><i class="bi bi-layers me-1 text-primary"></i>Lensa Minus</td>
                                <td>2</td>
                                <td>Rp 250.000</td>
                                <td>Rp 500.000</td>
                            </tr>
                        </tbody>
                    </table>
            </div>
        </div>

        <!-- Resep Kacamata -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header fw-bold text-decoration-underline d-flex align-items-center gap-2 ">
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
                                            <th colspan="4" class="text-primary">Right Side</th>
                                            <th colspan="4" class="text-primary">Left Side</th>
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
                                            <td><input type="text" class="form-control" value="-1.25"></td>
                                            <td><input type="text" class="form-control" value="-0.50"></td>
                                            <td><input type="text" class="form-control" value="180"></td>
                                            <td><input type="text" class="form-control" value="6/6"></td>
                                            <td><input type="text" class="form-control" value="-1.00"></td>
                                            <td><input type="text" class="form-control" value="-0.75"></td>
                                            <td><input type="text" class="form-control" value="170"></td>
                                            <td><input type="text" class="form-control" value="6/7.5"></td>
                                        </tr>
                                        <tr>
                                            <td>N</td>
                                            <td><input type="text" class="form-control" value="-1.00"></td>
                                            <td><input type="text" class="form-control" value="-0.25"></td>
                                            <td><input type="text" class="form-control" value="90"></td>
                                            <td><input type="text" class="form-control" value="6/6"></td>
                                            <td><input type="text" class="form-control" value="-0.75"></td>
                                            <td><input type="text" class="form-control" value="-0.50"></td>
                                            <td><input type="text" class="form-control" value="95"></td>
                                            <td><input type="text" class="form-control" value="6/7.5"></td>
                                        </tr>
                                        <tr>
                                            <td>ADD</td>
                                            <td colspan="4"><input type="text" class="form-control" value="+1.00"></td>
                                            <td colspan="4"><input type="text" class="form-control" value="+1.00"></td>
                                        </tr>
                                        <tr>
                                            <td>PD</td>
                                            <td colspan="4"><input type="text" class="form-control" value="31.5"></td>
                                            <td colspan="4"><input type="text" class="form-control" value="31.0"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label"><i class="bi bi-pencil-square me-1"></i>Extra Notes</label>
                            <textarea class="form-control" style="background-color: #f8f9fa; color: #495057;">Pasien mengeluh silau saat malam dan ingin kacamata anti radiasi.</textarea>
                        </div>
                    </form>
                </div>

                <hr>

                <!-- Transaction Details -->
                <div class="card-header fw-bold text-decoration-underline d-flex align-items-center gap-2 ">
                    <i class="bi bi-receipt-cutoff"></i> Transaction Details
                </div>
                <div class="card-body">
                    <form class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label"><i class="bi bi-info-circle me-1"></i>Order Status</label>
                            <select class="form-select">
                                <option selected>Proses</option>
                                <option>Selesai</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="bi bi-calendar-event me-1"></i>Order Date</label>
                            <input type="date" class="form-control" value="2025-05-25">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="bi bi-calendar-check me-1"></i>Order Completed Date</label>
                            <input type="date" class="form-control" value="2025-05-28">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="bi bi-credit-card-2-front me-1"></i>Pembayaran</label>
                            <select class="form-select">
                                <option>DP</option>
                                <option selected>Pelunasan</option>
                                <option>Asuransi</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="bi bi-person-badge me-1"></i>Nama Optometrist</label>
                            <select class="form-select">
                                <option selected>Ika</option>
                                <option>Joko</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="bi bi-wallet2 me-1"></i>Payment Method</label>
                            <select class="form-select">
                                <option selected>Cash</option>
                                <option>Card</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="bi bi-cash-stack me-1"></i>Payment Status</label>
                            <select class="form-select">
                                <option selected>Paid</option>
                                <option>Unpaid</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="bi bi-cash me-1"></i>Customer Paying</label>
                            <input type="number" class="form-control" value="850000">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bill Summary -->
        <div class="card mt-3 col-md-4 shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center ">
                <span class="fw-bold text-decoration-underline"><i class="bi bi-receipt"></i> Bill Summary</span>
                <small>2025/05/26</small>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between border-0 px-0">
                        <span><i class="bi bi-calculator me-2 "></i>Subtotal</span>
                        <strong>Rp 850.000</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between border-0 px-0">
                        <span><i class="bi bi-percent me-2 "></i>Discount</span>
                        <strong>Rp 0</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between border-0 px-0">
                        <span><i class="bi bi-coin me-2 "></i>Total</span>
                        <strong>Rp 850.000</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between border-0 px-0">
                        <span><i class="bi bi-cash-stack me-2 "></i>Paid</span>
                        <strong>Rp 850.000</strong>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</div>

</x-app>
