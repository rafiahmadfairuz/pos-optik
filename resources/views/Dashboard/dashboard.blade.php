<x-app>
    @section('title', 'Dashboard')

    <div class="container-fluid">
        <div class="row">
            <!-- Bagian Diagram: 80% -->
            <div class="col-lg-9 col-md-12">
                <!-- Diagram Penjualan Harian -->
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="card-title">Diagram Penjualan Harian</div>
                    </div>
                    <div class="card-body">
                        <canvas id="chartBar1" class="h-300"></canvas>
                    </div>
                </div>

                <!-- Diagram Produk Terlaris -->
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="card-title">Diagram Penjualan Produk Terlaris</div>
                    </div>
                    <div class="card-body">
                        <div class="chartjs-wrapper-demo">
                            <canvas id="chartPie" class="h-300"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bagian Stok Produk: 20% -->
            <div class="col-lg-3 col-md-12 d-flex flex-column">
                <div class="card flex-fill h-100">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Data Stok Produk</h4>
                        <div class="dropdown">
                            <a href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false"
                                class="dropset">
                                <i class="fa fa-ellipsis-v"></i>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a href="productlist.html" class="dropdown-item">Product List</a></li>
                                <li><a href="addproduct.html" class="dropdown-item">Product Add</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive dataview">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Sno</th>
                                        <th>Products</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td class="productimgname">
                                            <a href="productlist.html" class="product-img">
                                                <img src="assets/img/product/product22.jpg" alt="product">
                                            </a>
                                            <a href="productlist.html">Apple Earpods</a>
                                        </td>
                                        <td>$891.2</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td class="productimgname">
                                            <a href="productlist.html" class="product-img">
                                                <img src="assets/img/product/product23.jpg" alt="product">
                                            </a>
                                            <a href="productlist.html">iPhone 11</a>
                                        </td>
                                        <td>$668.51</td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td class="productimgname">
                                            <a href="productlist.html" class="product-img">
                                                <img src="assets/img/product/product24.jpg" alt="product">
                                            </a>
                                            <a href="productlist.html">Samsung</a>
                                        </td>
                                        <td>$522.29</td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td class="productimgname">
                                            <a href="productlist.html" class="product-img">
                                                <img src="assets/img/product/product6.jpg" alt="product">
                                            </a>
                                            <a href="productlist.html">Macbook Pro</a>
                                        </td>
                                        <td>$291.01</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- List Orderan Pending -->
            <div class="col-12 mt-4">
                <div class="card mb-0">
                    <div class="card-body">
                        <h4 class="card-title">List Orderan Pending (Berdasarkan Tanggal Terlama)</h4>
                        <div class="table-responsive dataview">
                            <table class="table table-bordered table-hover align-middle">
    <thead class="table-light">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Customer</th>
            <th scope="col">Tanggal Transaksi</th>
            <th scope="col">Total</th>
            <th scope="col">Status</th>
            <th scope="col">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td><a href="productlist.html" class="text-decoration-none">Jonh Doe</a></td>
            <td>25-11-2022</td>
            <td>Rp.2,989,888</td>
            <td>
                <span class="badge bg-warning text-dark me-1">Ongoing</span>
                <span class="badge bg-success-subtle text-success">Paid</span>
            </td>
            <td>
                <a href="#" class=" btn-sm">
                    <i class="bi bi-eye"></i>
                </a>
            </td>
        </tr>
        <tr>
            <td>2</td>
            <td><a href="productlist.html" class="text-decoration-none">Faris Afrizal</a></td>
            <td>25-11-2022</td>
            <td>Rp.2,989,888</td>
            <td>
                <span class="badge bg-success me-1">Complete</span>
                <span class="badge bg-success-subtle text-success">Paid</span>
            </td>
            <td>
                <a href="#" class=" btn-sm">
                    <i class="bi bi-eye"></i>
                </a>
            </td>
        </tr>
        <tr>
            <td>3</td>
            <td><a href="productlist.html" class="text-decoration-none">Rafi Hidayat</a></td>
            <td>25-11-2022</td>
            <td>Rp.1,000,000</td>
            <td>
                <span class="badge bg-success me-1">Complete</span>
                <span class="badge bg-success-subtle text-success">Paid</span>
            </td>
            <td>
                <a href="#" class=" btn-sm">
                    <i class="bi bi-eye"></i>
                </a>
            </td>
        </tr>
        <tr>
            <td>4</td>
            <td><a href="productlist.html" class="text-decoration-none">Putri Maharani</a></td>
            <td>25-11-2022</td>
            <td>Rp.2,000,000</td>
            <td>
                <span class="badge bg-warning text-dark me-1">Ongoing</span>
                <span class="badge bg-danger-subtle text-danger">Unpaid</span>
            </td>
            <td>
                <a href="#" class=" btn-sm">
                    <i class="bi bi-eye"></i>
                </a>
            </td>
        </tr>
    </tbody>
</table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app>
