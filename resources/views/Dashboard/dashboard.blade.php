<x-app>
    @section('title', 'Dashboard')

    <div class="container-fluid">
        <div class="row">
            <!-- Diagram Penjualan Harian -->
            <div class="col-lg-9 col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Diagram Penjualan Harian Cabang {{ session('cabang_id') }}</p>
                        </h4>
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
                            <canvas id="chartPie" class="h-300"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-12 d-flex flex-column">
                <div class="card flex-fill h-100">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Stok Produk Paling Sedikit Cabang {{ session('cabang_id') }}</h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive dataview" style="max-height: 638px; overflow-y: auto;">
                            <table class="table table-striped mb-0">
                                <thead class="table-light sticky-top" style="top: 0; z-index: 1;">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Produk</th>
                                        <th>Kategori</th>
                                        <th>Stok</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($leastStockProducts as $index => $produk)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $produk->nama_produk }}</td>
                                            <td>{{ ucfirst(str_replace('_', ' ', $produk->kategori)) }}</td>
                                            <td>{{ $produk->stok }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak ada data produk.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>


            @auth
                @if (in_array(Auth::user()->role, ['admin', 'cabang']))
                    <div class="col-12 mt-4">
                        <div class="card mb-0">
                            <div class="card-body">
                                <h4 class="card-title">Orderan Pending (Berdasarkan Tanggal Terlama)</h4>
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
                                            @foreach ($pendingOrders as $order)
                                                <tr>
                                                    <td>{{ $order->id }}</td>
                                                    <td>
                                                        <a href="#" class="text-decoration-none">
                                                            {{ $order->user->name ?? 'Tidak Diketahui / Barang Telah Dihapus' }}
                                                        </a>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d-m-Y') }}
                                                    </td>
                                                    <td>Rp.{{ number_format($order->total, 0, ',', '.') }}</td>
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            <div class="px-2 rounded-3 bg-warning text-white">
                                                                {{ ucfirst($order->order_status) }}
                                                            </div>
                                                            <div
                                                                class="px-2 rounded-3 {{ $order->payment_status === 'paid' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                                                {{ ucfirst($order->payment_status) }}
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('orderan.detail', $order->id) }}"
                                                            class="btn-sm text-primary">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endauth

        </div>
    </div>
    <script>
        const chartLabels = {!! json_encode($chartData->pluck('label')) !!};
        const chartValues = {!! json_encode($chartData->pluck('total')) !!};

        var ctx1 = document.getElementById("chartBar1").getContext("2d");

        new Chart(ctx1, {
            type: "bar",
            data: {
                labels: chartLabels,
                datasets: [{
                    label: "Penjualan (Rp)",
                    data: chartValues,
                    backgroundColor: "#664dc9",
                    borderRadius: 4,
                    barThickness: 20,
                }],
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                size: 10
                            },
                            callback: function(value) {
                                return "Rp " + value.toLocaleString("id-ID");
                            },
                        },
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 11
                            },
                        },
                        grid: {
                            display: false
                        },
                    },
                },
            },
        });

        var datapie = {
            labels: @json($produkLabels),
            datasets: [{
                data: @json($produkValues),
                backgroundColor: [
                    "#664dc9",
                    "#44c4fa",
                    "#38cb89",
                    "#3e80eb",
                    "#ffab00",
                    "#ef4b4b",
                ],
            }]
        };

        var optionpie = {
            maintainAspectRatio: false,
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: {
                            size: 12
                        }
                    }
                },
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        };


        var ctx6 = document.getElementById("chartPie").getContext("2d");
        new Chart(ctx6, {
            type: "pie",
            data: datapie,
            options: optionpie
        });
    </script>

</x-app>
