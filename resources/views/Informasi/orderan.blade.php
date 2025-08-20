<x-app>
    @section('title', 'Orderan')

    <div class="container-fluid py-4">
        <div class="page-header">
            <div class="page-title">
                <h1 class="fw-bold">List Orderan Cabang {{ session('cabang_id') }}</h1>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-4">Orderan</h5>

                <!-- Tabs -->
                <ul class="nav border-bottom mb-4" id="transactionTabs">
                    <li class="nav-item">
                        <a class="nav-link active border-0 border-bottom border-primary fw-semibold" href="#"
                            data-status="all">
                            All <span class="text-muted">{{ count($orderans) }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link border-0" href="#" data-status="complete">
                            Selesai <span
                                class="text-muted">{{ $orderans->where('order_status', 'complete')->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link border-0" href="#" data-status="pending">
                            Belum Selesai <span
                                class="text-muted">{{ $orderans->where('order_status', 'pending')->count() }}</span>
                        </a>
                    </li>
                </ul>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="transactionTable">
                            @foreach ($orderans as $orderan)
                                <tr data-status="{{ $orderan->order_status }}">
                                    <td>{{ $orderan->id }}</td>
                                    <td>
                                        <strong>{{ $orderan->user->name ?? 'Unknown' }}</strong><br>
                                        <small class="text-muted">{{ $orderan->user->phone ?? '-' }}</small>
                                    </td>
                                    <td>{{ $orderan->order_date }} {{ $orderan->created_at->format('H:i') }}</td>
                                    <td>Rp. {{ number_format($orderan->total, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="d-flex gap-1 flex-wrap">
                                            {{-- Status order --}}
                                            @if ($orderan->order_status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif ($orderan->order_status === 'complete')
                                                <span class="badge bg-success">Complete</span>
                                            @else
                                                <span class="badge bg-secondary">Unknown</span>
                                            @endif

                                            {{-- Status pembayaran --}}
                                            @if ($orderan->payment_status === 'paid')
                                                <span class="badge bg-success-subtle text-success">Paid</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger">Unpaid</span>
                                            @endif

                                            {{-- Status retur --}}
                                            @if ($orderan->is_returned)
                                                <span class="badge bg-danger">Retur</span>
                                            @else
                                                <span class="badge bg-primary">Tidak Retur</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('orderan.detail', $orderan->id) }}"
                                            class="text-primary me-2">
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

    <script>
        const tabs = document.querySelectorAll('#transactionTabs .nav-link');
        const rows = document.querySelectorAll('#transactionTable tr');

        tabs.forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();

                tabs.forEach(t => {
                    t.classList.remove('active', 'border-bottom', 'border-primary', 'fw-semibold');
                });
                this.classList.add('active', 'border-bottom', 'border-primary', 'fw-semibold');

                const selected = this.getAttribute('data-status');

                rows.forEach(row => {
                    const status = row.getAttribute('data-status');
                    if (selected === 'all' || status === selected) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
</x-app>
