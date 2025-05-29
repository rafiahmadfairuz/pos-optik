<x-app>
    @section('title', 'Laporan Penjualan & Klaim Asuransi')

    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold "><i class="bi bi-file-earmark-bar-graph me-2"></i> Laporan Penjualan & Klaim
                Asuransi</h2>
            <div>
                <button class="btn btn-outline-success me-2">
                    <i class="bi bi-download me-1"></i> Export Excel
                </button>
                <button class="btn btn-outline-secondary">
                    <i class="bi bi-funnel me-1"></i> Filter Data
                </button>
            </div>
        </div>

        <div class="row">
          <!-- 1. Data Penjualan Global Per Bulan -->
<div class="col-12 mb-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0">
            <h5 class="mb-0 text-dark fw-semibold">
                <i class="bi bi-bar-chart-fill me-2 text-secondary"></i>Data Penjualan Global Per Bulan
            </h5>
            <small class="text-muted">Periode Januari – Desember 2024</small>
        </div>
        <div class="card-body">
            <div class="table-responsive dataview">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light text-center">
                        <tr class="align-middle">
                            <th>Bulan</th>
                            <th>Total Penjualan</th>
                            <th>Jumlah Transaksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <tr>
                            <td>Januari</td>
                            <td>Rp 1.200.000.000</td>
                            <td>1.200</td>
                        </tr>
                        <tr>
                            <td>Februari</td>
                            <td>Rp 980.000.000</td>
                            <td>980</td>
                        </tr>
                        <tr>
                            <td>Maret</td>
                            <td>Rp 1.350.000.000</td>
                            <td>1.100</td>
                        </tr>
                        <tr>
                            <td>April</td>
                            <td>Rp 1.500.000.000</td>
                            <td>1.300</td>
                        </tr>
                        <tr>
                            <td>Mei</td>
                            <td>Rp 1.750.000.000</td>
                            <td>1.550</td>
                        </tr>
                        <tr>
                            <td>Juni</td>
                            <td>Rp 1.300.000.000</td>
                            <td>1.150</td>
                        </tr>
                        <tr>
                            <td>Juli</td>
                            <td>Rp 1.700.000.000</td>
                            <td>1.400</td>
                        </tr>
                        <tr>
                            <td>Agustus</td>
                            <td>Rp 1.680.000.000</td>
                            <td>1.370</td>
                        </tr>
                        <tr>
                            <td>September</td>
                            <td>Rp 1.780.000.000</td>
                            <td>1.490</td>
                        </tr>
                        <tr>
                            <td>Oktober</td>
                            <td>Rp 1.820.000.000</td>
                            <td>1.520</td>
                        </tr>
                        <tr>
                            <td>November</td>
                            <td>Rp 2.000.000.000</td>
                            <td>1.750</td>
                        </tr>
                        <tr>
                            <td>Desember</td>
                            <td>Rp 2.500.000.000</td>
                            <td>2.200</td>
                        </tr>
                    </tbody>
                    <tfoot class="table-light text-center fw-semibold">
                        <tr>
                            <td>Total</td>
                            <td>Rp 19.560.000.000</td>
                            <td>17.010</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>


            <!-- 2. Laporan Claim Asuransi -->
            <div class="col-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header border-0 bg-white">
                        <h5 class="mb-0 text-dark fw-semibold"><i class="bi bi-file-medical-fill me-2"></i> Laporan
                            Klaim Asuransi</h5>
                        <small class="text-muted">Sudah Cair & Belum Cair</small>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive dataview">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID Claim</th>
                                        <th>Nasabah</th>
                                        <th>Status</th>
                                        <th>Jumlah Cair</th>
                                        <th>Tanggal Cair</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>00123</td>
                                        <td>Budi Santoso</td>
                                        <td><span class="badge bg-success">Sudah Cair</span></td>
                                        <td>Rp. 5.000.000</td>
                                        <td>10-05-2025</td>
                                    </tr>
                                    <tr>
                                        <td>00124</td>
                                        <td>Sari Dewi</td>
                                        <td><span class="badge bg-warning text-dark">Belum Cair</span></td>
                                        <td>-</td>
                                        <td>-</td>
                                    </tr>
                                    <!-- contoh isi -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Grafik Penjualan Harian (full width) -->
            <div class="col-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header border-0 bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-dark fw-semibold"><i class="bi bi-bar-chart-line-fill me-2"></i> Grafik
                            Penjualan Harian</h5>
                        {{-- <small class="text-muted">Periode Bulan</small> --}}
                    </div>
                    <div class="card-body">
                        <canvas id="chartBar1" class="h-300"></canvas>
                    </div>
                </div>
            </div>

            <!-- 4 & 5. Diagram Frame dan Lensa 50%-50% -->
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header border-0 bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-dark fw-semibold"><i class="bi bi-box-seam me-2"></i> Frame Paling Laris
                        </h5>
                        <small class="text-muted">Berdasarkan Merek</small>
                    </div>
                    <div class="card-body">
                        <canvas id="chartPie" class="h-300"></canvas>

                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header border-0 bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-dark fw-semibold"><i class="bi bi-brush me-2"></i> Lensa Paling Laris</h5>
                        <small class="text-muted">Berdasarkan Merek & Tipe</small>
                    </div>
                    <div class="card-body">
                        <canvas id="chartDonut" class="h-300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app>
