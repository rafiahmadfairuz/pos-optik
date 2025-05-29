<x-app>
    @section('title', 'Dashboard')

    <div class="container-fluid">
        <div class="row">
            <!-- Diagram Penjualan Harian -->
            <div class="col-lg-9 col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Diagram Penjualan Harian</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="chartBar1" class="h-300"></canvas>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Diagram Produk Terlaris</h4>
                    </div>
                    <div class="card-body">
                        <div class="chartjs-wrapper-demo">
                            <canvas id="chartDonut" class="h-300"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-12 d-flex flex-column">
                <div class="card flex-fill h-100">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Data Stok Produk</h4>
                        <div class="dropdown">
                            <a href="javascript:void(0);" data-bs-toggle="dropdown" class="dropset">
                                <i class="fa fa-ellipsis-v"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="productlist.html" class="dropdown-item">Daftar Produk</a></li>
                                <li><a href="addproduct.html" class="dropdown-item">Tambah Produk</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive dataview" style="max-height: 638px; overflow-y: auto;">
                            <table class="table table-striped mb-0">
                                <thead class="table-light sticky-top" style="top: 0; z-index: 1;">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Produk</th>
                                        <th>Stok</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Kacamata Pria Elegan</td>
                                        <td>15</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Lensa Anti Radiasi</td>
                                        <td>8</td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Frame Titanium Wanita</td>
                                        <td>12</td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>Softlens Natural Look</td>
                                        <td>20</td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>Kacamata Hitam Fashion</td>
                                        <td>10</td>
                                    </tr>
                                    <tr>
                                        <td>6</td>
                                        <td>Lensa Minus Tipis</td>
                                        <td>30</td>
                                    </tr>
                                    <tr>
                                        <td>7</td>
                                        <td>Kacamata Anak Karakter</td>
                                        <td>25</td>
                                    </tr>
                                    <tr>
                                        <td>8</td>
                                        <td>Frame Retro Bulat</td>
                                        <td>17</td>
                                    </tr>
                                    <tr>
                                        <td>9</td>
                                        <td>Softlens Warna Abu</td>
                                        <td>22</td>
                                    </tr>
                                    <tr>
                                        <td>10</td>
                                        <td>Kacamata Baca Anti UV</td>
                                        <td>13</td>
                                    </tr>
                                    <tr>
                                        <td>11</td>
                                        <td>Lensa Photochromic</td>
                                        <td>9</td>
                                    </tr>
                                    <tr>
                                        <td>12</td>
                                        <td>Frame Klasik Logam</td>
                                        <td>6</td>
                                    </tr>
                                    <tr>
                                        <td>13</td>
                                        <td>Softlens Pink Natural</td>
                                        <td>18</td>
                                    </tr>
                                    <tr>
                                        <td>14</td>
                                        <td>Frame Kacamata Kayu</td>
                                        <td>4</td>
                                    </tr>
                                    <tr>
                                        <td>15</td>
                                        <td>Lensa Anti Silau</td>
                                        <td>14</td>
                                    </tr>
                                    <tr>
                                        <td>16</td>
                                        <td>Kacamata Outdoor</td>
                                        <td>11</td>
                                    </tr>
                                    <tr>
                                        <td>17</td>
                                        <td>Frame Anak Anti Patah</td>
                                        <td>16</td>
                                    </tr>
                                    <tr>
                                        <td>18</td>
                                        <td>Kacamata Gaming</td>
                                        <td>7</td>
                                    </tr>
                                    <tr>
                                        <td>19</td>
                                        <td>Lensa Silinder</td>
                                        <td>19</td>
                                    </tr>
                                    <tr>
                                        <td>20</td>
                                        <td>Frame Minimalis</td>
                                        <td>23</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-12 mt-4">
                <div class="card mb-0">
                    <div class="card-body">
                        <h4 class="card-title">Orderan Pending (Tanggal Terlama)</h4>
                        <div class="table-responsive dataview">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Pelanggan</th>
                                        <th>Tanggal Transaksi</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>INV-001</td>
                                        <td><a href="#" class="text-decoration-none">John Doe</a></td>
                                        <td>01-04-2024</td>
                                        <td>Rp.1.500.000</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <div class="px-2 rounded-3 bg-warning text-white">Ongoing</div>
                                                <div class="px-2 rounded-3 bg-success-subtle text-success">Paid</div>
                                            </div>
                                        </td>

                                        <td>
                                            <a href="#" class="btn-sm text-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>INV-002</td>
                                        <td><a href="#" class="text-decoration-none">Putri Maharani</a></td>
                                        <td>02-04-2024</td>
                                        <td>Rp.850.000</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <div class="px-2 rounded-3 bg-success text-white me-1">Complete</div>
                                                <div class="px-2 rounded-3 bg-success-subtle text-success">Paid</div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="#" class="btn-sm text-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>INV-003</td>
                                        <td><a href="#" class="text-decoration-none">Rafi Hidayat</a></td>
                                        <td>04-04-2024</td>
                                        <td>Rp.700.000</td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <div class="px-2 rounded-3 bg-warning text-white me-1">Ongoing</div>
                                                <div class="px-2 rounded-3 bg-danger-subtle text-danger">Unpaid</div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="#" class="btn-sm text-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>INV-004</td>
                                        <td><a href="#" class="text-decoration-none">Faris Afrizal</a></td>
                                        <td>05-04-2024</td>
                                        <td>Rp.980.000</td>
                                        <td>
                                            <div class="d-flex gap-1">

                                                <div class="px-2 rounded-3 bg-success text-white me-1">Complete</div>
                                                <div class="px-2 rounded-3 bg-success-subtle text-success">Paid</div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="#" class="btn-sm text-primary">
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
