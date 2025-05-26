<x-app>
    @section('Customer', 'Dashboard')
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
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cashier</li>
            </ol>
        </nav>

        <h3>Cashier</h3>

        <div class="row g-3">
            <!-- Customer Info (30%) dan Choose Customer (70%) -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Customer Information</div>
                    <div class="card-body">Please select customer first</div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Choose Customer</div>
                    <div class="card-body p-2">
                        <input type="text" class="form-control mb-2" placeholder="Search...">
                        <div class="table-responsive card-scroll">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>NIK</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Latest Medical Record</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>54229828427649</td>
                                        <td>Germaine Nitzsche</td>
                                        <td>861-986-2792</td>
                                        <td><span class="badge bg-secondary">Non_member</span></td>
                                        <td>Apr 23, 2025</td>
                                    </tr>
                                    <tr>
                                        <td>54229828427649</td>
                                        <td>Germaine Nitzsche</td>
                                        <td>861-986-2792</td>
                                        <td><span class="badge bg-secondary">Non_member</span></td>
                                        <td>Apr 23, 2025</td>
                                    </tr>
                                    <tr>
                                        <td>54229828427649</td>
                                        <td>Germaine Nitzsche</td>
                                        <td>861-986-2792</td>
                                        <td><span class="badge bg-secondary">Non_member</span></td>
                                        <td>Apr 23, 2025</td>
                                    </tr>
                                    <!-- More rows -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shopping Cart (70%) dan Choose Product (30%) -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Shopping Cart</div>
                    <div class="card-body text-muted text-center">Your cart is empty</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Choose Product</div>
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
                                        <td><span class="badge bg-warning text-dark">Low Stock</span></td>
                                        <td><a href="#">+</a></td>
                                    </tr>
                                    <tr>
                                        <td>10 Tablets Pack</td>
                                        <td>Rp. 75.000</td>
                                        <td><span class="badge bg-warning text-dark">Low Stock</span></td>
                                        <td><a href="#">+</a></td>
                                    </tr>
                                    <tr>
                                        <td>10 Tablets Pack</td>
                                        <td>Rp. 75.000</td>
                                        <td><span class="badge bg-warning text-dark">Low Stock</span></td>
                                        <td><a href="#">+</a></td>
                                    </tr>
                                    <tr>
                                        <td>10 Tablets Pack</td>
                                        <td>Rp. 75.000</td>
                                        <td><span class="badge bg-warning text-dark">Low Stock</span></td>
                                        <td><a href="#">+</a></td>
                                    </tr>
                                    <tr>
                                        <td>10 Tablets Pack</td>
                                        <td>Rp. 75.000</td>
                                        <td><span class="badge bg-warning text-dark">Low Stock</span></td>
                                        <td><a href="#">+</a></td>
                                    </tr>
                                    <tr>
                                        <td>10 Tablets Pack</td>
                                        <td>Rp. 75.000</td>
                                        <td><span class="badge bg-warning text-dark">Low Stock</span></td>
                                        <td><a href="#">+</a></td>
                                    </tr>
                                    <tr>
                                        <td>10 Tablets Pack</td>
                                        <td>Rp. 75.000</td>
                                        <td><span class="badge bg-warning text-dark">Low Stock</span></td>
                                        <td><a href="#">+</a></td>
                                    </tr>
                                    <tr>
                                        <td>10 Tablets Pack</td>
                                        <td>Rp. 75.000</td>
                                        <td><span class="badge bg-warning text-dark">Low Stock</span></td>
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
                    <div class="card-header">Transaction Details</div>
                    <div class="card-body">
                        <form class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label">Order Status</label>
                                <select class="form-select">
                                    <option>Complete</option>
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
                                <label class="form-label">Insurance</label>
                                <select class="form-select">
                                    <option>No Insurance</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Discount</label>
                                <input type="number" class="form-control" value="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Payment Method</label>
                                <select class="form-select">
                                    <option>Cash</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Payment Status</label>
                                <select class="form-select">
                                    <option>Paid</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Transaction Status</label>
                                <select class="form-select">
                                    <option>Complete</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Customer Paying</label>
                                <input type="number" class="form-control" value="0">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Extra Notes</label>
                                <textarea class="form-control"></textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Bill Summary (30%) dibawah Choose Product -->
            <div class="card mt-3 col-md-4">
                <div class="card-header d-flex justify-content-between">
                    <span>Bill Summary</span>
                    <small class="text-muted">2025/05/26</small>
                </div>
                <div class="card-body">
                    <p>Total: <strong>0</strong></p>
                    <p>Final Amount: <strong>Rp. 0</strong></p>
                </div>
            </div>



        </div>

        <!-- Checkout Button -->
        <div class="mt-4 text-center">
            <button class="btn btn-primary w-100">Checkout</button>
        </div>
    </div>
</x-app>
