<x-app>
    @section('title', 'Laporan Penjualan')

    <div class="container-fluid py-4">
       <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <h2 class="fw-bold mb-0">
        <i class="bi bi-file-earmark-bar-graph me-2"></i> Laporan Penjualan
    </h2>

    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 w-100 justify-content-md-end">
        <!-- Form Filter -->
        <form method="GET" action="{{ route('report.index') }}" class="row gx-2 gy-2 align-items-end">
            <div class="col-auto">
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-auto">
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-auto d-flex gap-2">
                <button type="submit" class="btn btn-outline-secondary">
                    <i class="bi bi-funnel me-1"></i> Filter Data
                </button>
                <a href="{{ route('report.index') }}" class="btn btn-outline-danger">Reset</a>
            </div>
        </form>

        <!-- Form Export -->
        <form method="GET" action="{{ route('report.export') }}" class="d-flex">
            <input type="hidden" name="date_from" value="{{ request('date_from') }}">
            <input type="hidden" name="date_to" value="{{ request('date_to') }}">
            <button type="submit" class="btn btn-outline-success">
                <i class="bi bi-download me-1"></i> Export Excel
            </button>
        </form>
    </div>
</div>


        <div class="row">
            <!-- 1. Penjualan Per Bulan -->
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
                                    <tr>
                                        <th>Bulan</th>
                                        <th>Total Penjualan</th>
                                        <th>Jumlah Transaksi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @php
                                        $totalAll = 0;
                                        $jumlahTransaksiAll = 0;
                                        $bulanIndo = [
                                            1 => 'Januari',
                                            2 => 'Februari',
                                            3 => 'Maret',
                                            4 => 'April',
                                            5 => 'Mei',
                                            6 => 'Juni',
                                            7 => 'Juli',
                                            8 => 'Agustus',
                                            9 => 'September',
                                            10 => 'Oktober',
                                            11 => 'November',
                                            12 => 'Desember',
                                        ];
                                    @endphp
                                    @foreach ($penjualanPerBulan as $item)
                                        @php
                                            $totalAll += $item->total_penjualan;
                                            $jumlahTransaksiAll += $item->jumlah_transaksi;
                                        @endphp
                                        <tr>
                                            <td>{{ $bulanIndo[$item->bulan] ?? 'Bulan ' . $item->bulan }}</td>
                                            <td>Rp {{ number_format($item->total_penjualan, 0, ',', '.') }}</td>
                                            <td>{{ number_format($item->jumlah_transaksi) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light text-center fw-semibold">
                                    <tr>
                                        <td>Total</td>
                                        <td>Rp {{ number_format($totalAll, 0, ',', '.') }}</td>
                                        <td>{{ number_format($jumlahTransaksiAll) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Grafik Penjualan Harian -->
            <div class="col-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header border-0 bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-dark fw-semibold"><i class="bi bi-bar-chart-line-fill me-2"></i> Grafik
                            Penjualan Harian</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chartBar1" class="h-300"></canvas>
                    </div>
                </div>
            </div>

            <!-- 4. Frame Paling Laris -->
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header border-0 bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-dark fw-semibold"><i class="bi bi-box-seam me-2"></i> Frame Paling Laris
                        </h5>
                        <small class="text-muted">Berdasarkan Merek</small>
                    </div>
                    <div class="card-body">
                        <canvas id="chartPie" ></canvas>
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
                        <canvas id="chartDonut" ></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Grafik Penjualan Harian
        const chartBar1 = new Chart(document.getElementById('chartBar1'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($penjualanHarian->pluck('tanggal')->toArray()) !!},
                datasets: [{
                    label: 'Total Penjualan (Rp)',
                    data: {!! json_encode($penjualanHarian->pluck('total_penjualan')->toArray()) !!},
                    backgroundColor: '#4caf50',
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const chartPie = new Chart(document.getElementById('chartPie'), {
            type: 'pie',
            data: {
                labels: {!! json_encode($frameTerlaris->pluck('name')->toArray()) !!},
                datasets: [{
                    data: {!! json_encode($frameTerlaris->pluck('total_terjual')->toArray()) !!},
                    backgroundColor: ['#4caf50', '#2196f3', '#ffc107', '#ff5722', '#9c27b0', '#00bcd4'],
                }]
            }
        });

        const chartDonut = new Chart(document.getElementById('chartDonut'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($lensaTerlaris->pluck('nama_lensa')->toArray()) !!},

                datasets: [{
                    data: {!! json_encode($lensaTerlaris->pluck('total_terjual')->toArray()) !!},
                    backgroundColor: ['#ff9800', '#8bc34a', '#03a9f4', '#f44336', '#673ab7', '#009688'],
                }]
            }
        });
    </script>
</x-app>
