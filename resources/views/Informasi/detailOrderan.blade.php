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

    <div class="container-fluid py-3">
        <h3>Cashier</h3>

        <div class="row g-3">
            <!-- Shopping Cart -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header fw-bold text-decoration-underline">Shopping Cart</div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Frame Kacamata A</td>
                                    <td>1</td>
                                    <td>Rp 350.000</td>
                                    <td>Rp 350.000</td>
                                </tr>
                                <tr>
                                    <td>Lensa Minus</td>
                                    <td>2</td>
                                    <td>Rp 250.000</td>
                                    <td>Rp 500.000</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Choose Product -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header fw-bold text-decoration-underline">Choose Product</div>
                    <div class="card-body p-2">
                        <input type="text" class="form-control mb-2" placeholder="Search products by name or SKU...">
                        <div class="table-responsive card-scroll">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Frame Kacamata A</td>
                                        <td>Rp. 350.000</td>
                                        <td><span class="badge bg-success">Ready</span></td>
                                        <td><a href="#">+</a></td>
                                    </tr>
                                    <tr>
                                        <td>Lensa Anti Radiasi</td>
                                        <td>Rp. 500.000</td>
                                        <td><span class="badge bg-warning text-dark">Low Stock</span></td>
                                        <td><a href="#">+</a></td>
                                    </tr>
                                    <tr>
                                        <td>Lensa Minus</td>
                                        <td>Rp. 250.000</td>
                                        <td><span class="badge bg-danger">Out</span></td>
                                        <td><a href="#">+</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resep Kacamata -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header fw-bold text-decoration-underline">Resep Kacamata</div>
                    <div class="card-body">
                        <form class="row g-2">
                            <div class="col-md-12">
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
                                <label class="form-label">Extra Notes</label>
                                <textarea class="form-control">Pasien mengeluh silau saat malam dan ingin kacamata anti radiasi.</textarea>
                            </div>
                        </form>
                    </div>

                    <hr>

                    <!-- Transaction Details -->
                    <div class="card-header fw-bold text-decoration-underline">Transaction Details</div>
                    <div class="card-body">
                        <form class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label">Order Status</label>
                                <select class="form-select">
                                    <option selected>Proses</option>
                                    <option>Selesai</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Order Date</label>
                                <input type="date" class="form-control" value="2025-05-25">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Order Completed Date</label>
                                <input type="date" class="form-control" value="2025-05-28">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Pembayaran</label>
                                <select class="form-select">
                                    <option>DP</option>
                                    <option selected>Pelunasan</option>
                                    <option>Asuransi</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nama Optometrist</label>
                                <select class="form-select">
                                    <option selected>Ika</option>
                                    <option>Joko</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Payment Method</label>
                                <select class="form-select">
                                    <option selected>Cash</option>
                                    <option>Card</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Payment Status</label>
                                <select class="form-select">
                                    <option selected>Paid</option>
                                    <option>Unpaid</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Customer Paying</label>
                                <input type="number" class="form-control" value="850000">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Bill Summary -->
            <div class="card mt-3 col-md-4">
                <div class="card-header d-flex justify-content-between">
                    <span class="fw-bold text-decoration-underline">Bill Summary</span>
                    <small class="text-muted">2025/05/26</small>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Subtotal</span>
                            <strong>Rp 850.000</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Discount</span>
                            <strong>Rp 0</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Total</span>
                            <strong>Rp 850.000</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Paid</span>
                            <strong>Rp 850.000</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Change</span>
                            <strong>Rp 0</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app>
