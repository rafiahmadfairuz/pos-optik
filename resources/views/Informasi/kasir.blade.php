<x-app>
    @section('title', 'Kasir')
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
         <h2 class="fw-bold "><i class="bi bi-cash-stack me-2"></i>Kasir</h2>
        <div class="row g-3">
           <div class="row g-3 align-items-stretch">
    <!-- Customer Info (40%) -->
    <div class="col-lg-5 col-md-12">
        <div class="card shadow-sm h-100 border-0">
            <div class="card-header bg-white fw-semibold border-bottom">
                <i class="bi bi-person-circle me-2"></i>Customer Information
            </div>
            <div class="card-body" style="min-height: 150px;">
                <!-- STATE 1: Belum ada customer -->
                <div class="text-center text-muted d-none" id="customer-empty">
                    <i class="bi bi-info-circle display-6 d-block mb-2"></i>
                    <small>Please select customer first</small>
                </div>

                <!-- STATE 2: Setelah customer dipilih -->
                <div id="customer-info" class="">
                    <div class="mb-2 text-truncate" title="Germaine Nitzsche">
                        <i class="bi bi-person-fill me-2 text-primary"></i>
                        <strong id="customer-name">Germaine Nitzsche</strong>
                    </div>
                    <div class="mb-2 text-truncate" title="861-986-2792">
                        <i class="bi bi-telephone-fill me-2 text-success"></i>
                        <span id="customer-phone">861-986-2792</span>
                    </div>
                    <div class="text-truncate" title="germaine@example.com">
                        <i class="bi bi-envelope-fill me-2 text-warning"></i>
                        <span id="customer-email">germaine@example.com</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Choose Customer (60%) -->
    <div class="col-lg-7 col-md-12">
        <div class="card shadow-sm h-100">
            <div class="card-header fw-semibold bg-white border-bottom">
                <i class="bi bi-search me-2"></i>Choose Customer
            </div>
            <div class="card-body p-3">
                <input type="text" class="form-control mb-3" placeholder="Search by name, NIK, or phone...">
                <div class="table-responsive card-scroll">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>NIK</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Latest Medical Record</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>54229828427649</td>
                                <td>Germaine Nitzsche</td>
                                <td>861-986-2792</td>
                                <td>Apr 23, 2025</td>
                            </tr>
                            <tr>
                                <td>54229828427649</td>
                                <td>Germaine Nitzsche</td>
                                <td>861-986-2792</td>
                                <td>Apr 23, 2025</td>
                            </tr>
                            <tr>
                                <td>54229828427649</td>
                                <td>Germaine Nitzsche</td>
                                <td>861-986-2792</td>
                                <td>Apr 23, 2025</td>
                            </tr>
                            <!-- More rows -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

            <!-- Shopping Cart (70%) dan Choose Product (30%) -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header fw-bold text-decoration-underline">Shopping Cart</div>
                    <div class="card-body text-muted text-center">Your cart is empty</div>
                </div>
            </div>
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
                                        <td>10 Tablets Pack</td>
                                        <td>Rp. 75.000</td>
                                        <td>43</td>
                                        <td><a href="#">+</a></td>
                                    </tr>
                                    <tr>
                                        <td>10 Tablets Pack</td>
                                        <td>Rp. 75.000</td>
                                        <td>12</td>
                                        <td><a href="#">+</a></td>
                                    </tr>
                                    <tr>
                                        <td>10 Tablets Pack</td>
                                        <td>Rp. 75.000</td>
                                        <td>87</td>
                                        <td><a href="#">+</a></td>
                                    </tr>
                                    <tr>
                                        <td>10 Tablets Pack</td>
                                        <td>Rp. 75.000</td>
                                        <td>32</td>
                                        <td><a href="#">+</a></td>
                                    </tr>
                                    <tr>
                                        <td>10 Tablets Pack</td>
                                        <td>Rp. 75.000</td>
                                        <td>324</td>
                                        <td><a href="#">+</a></td>
                                    </tr>
                                    <tr>
                                        <td>10 Tablets Pack</td>
                                        <td>Rp. 75.000</td>
                                        <td>324</td>
                                        <td><a href="#">+</a></td>
                                    </tr>
                                    <tr>
                                        <td>10 Tablets Pack</td>
                                        <td>Rp. 75.000</td>
                                        <td>324</td>
                                        <td><a href="#">+</a></td>
                                    </tr>
                                    <tr>
                                        <td>10 Tablets Pack</td>
                                        <td>Rp. 75.000</td>
                                        <td>324</td>
                                        <td><a href="#">+</a></td>
                                    </tr>
                                    <!-- More products -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <!-- Transaction Details (70%) -->
            <div class="col-md-8">

                <div class="card">
                    <div class="card-header fw-bold text-decoration-underline">Resep Kacamata</div>
                    <div class="card-body">
                        <form class="row g-2">
                            <!-- Form bawaan -->
                            <!-- ... (form awal kamu tetap dipertahankan di atas) ... -->

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
                                                <td><input type="text" name="right_d_sph" class="form-control"
                                                        value="-01.00"></td>
                                                <td><input type="text" name="right_d_cyl" class="form-control"></td>
                                                <td><input type="text" name="right_d_axis" class="form-control"></td>
                                                <td><input type="text" name="right_d_va" class="form-control"></td>
                                                <td><input type="text" name="left_d_sph" class="form-control"
                                                        value="+01.25"></td>
                                                <td><input type="text" name="left_d_cyl" class="form-control"></td>
                                                <td><input type="text" name="left_d_axis" class="form-control"></td>
                                                <td><input type="text" name="left_d_va" class="form-control"></td>
                                            </tr>
                                            <tr>
                                                <td>N</td>
                                                <td><input type="text" name="right_n_sph" class="form-control"
                                                        value="-01.00"></td>
                                                <td><input type="text" name="right_n_cyl" class="form-control"></td>
                                                <td><input type="text" name="right_n_axis" class="form-control"></td>
                                                <td><input type="text" name="right_n_va" class="form-control">
                                                </td>
                                                <td><input type="text" name="left_n_sph" class="form-control"
                                                        value="+01.25"></td>
                                                <td><input type="text" name="left_n_cyl" class="form-control">
                                                </td>
                                                <td><input type="text" name="left_n_axis" class="form-control">
                                                </td>
                                                <td><input type="text" name="left_n_va" class="form-control"></td>
                                            </tr>
                                            <tr>
                                                <td>ADD</td>
                                                <td colspan="4">
                                                    <input type="text" name="right_add" class="form-control"
                                                        value="+00.00">
                                                </td>
                                                <td colspan="4">
                                                    <input type="text" name="left_add" class="form-control"
                                                        value="+00.00">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>PD</td>
                                                <td colspan="4">
                                                    <input type="text" name="right_pd" class="form-control"
                                                        value="32.0">
                                                </td>
                                                <td colspan="4">
                                                    <input type="text" name="left_pd" class="form-control"
                                                        value="32.0">
                                                </td>
                                            </tr>

                                        </tbody>

                                    </table>

                                </div>

                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Extra Notes</label>
                                <textarea class="form-control"></textarea>
                            </div>
                        </form>
                    </div>

                    <hr>
                    <div class="card-header fw-bold text-decoration-underline">Transaction Details</div>
                    <div class="card-body">
                        <form class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label">Order Status</label>
                                <select class="form-select">
                                    <option>Proses</option>
                                    <option>Selesai</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Order Date</label>
                                <input type="date" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Order Completed Date</label>
                                <input type="date" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Pembayaran</label>
                                <select class="form-select">
                                    <option>DP</option>
                                    <option>Asuransi</option>
                                    <option>Pelunasan</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nama Optometrist</label>
                                <select class="form-select">
                                    <option>Joko</option>
                                    <option>Ika</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Payment Method</label>
                                <select class="form-select">
                                    <option>Cash</option>
                                    <option>Card</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Payment Status</label>
                                <select class="form-select">
                                    <option>Paid</option>
                                    <option>Unpaid</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Customer Paying</label>
                                <input type="number" class="form-control" value="0">
                            </div>


                        </form>
                    </div>
                </div>
            </div>
            <!-- Bill Summary (30%) dibawah Choose Product -->
            <div class="card mt-3 col-md-4">
                <div class="card-header d-flex justify-content-between">
                    <span class="fw-bold text-decoration-underline">Bill Summary</span>
                    <small class="text-muted">2025/05/26</small>
                </div>
                <div class="card-body">
                    <p>Total: <strong>0</strong></p>
                    <p>Final Amount: <strong>Rp. 0</strong></p>
                </div>
            </div>



        </div>

        <!-- Checkout Button -->
      <div class="text-end mt-4">
    <div class="d-flex flex-wrap gap-2 justify-content-start">
        <button type="submit" class="btn btn-secondary px-4">
            <i class="bi bi-save me-1"></i> Save
        </button>
        <button type="button" class="btn btn-primary px-4">
            <i class="bi bi-check-circle me-1"></i> Selesai
        </button>
    </div>
</div>


    </div>
</x-app>
