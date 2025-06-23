<x-app>
    @section('title', 'List Pembelian')

    <div class="container-fluid py-4">
        <div class="page-header">
            <div class="page-title">
                <h1 class="fw-bold">List Pembelian</h1>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-4">Pembelian</h5>

                <ul class="nav border-bottom mb-4" id="pembelianTabs">
                    <li class="nav-item">
                        <a class="nav-link active border-0 border-bottom border-primary fw-semibold" href="#"
                            data-status="all">
                            All <span class="text-muted">{{ count($pembelians) }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link border-0" href="#" data-status="retur">
                            Retur <span
                                class="text-muted">{{ $pembelians->where('retur', true)->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link border-0" href="#" data-status="tidak">
                            Tidak Retur <span
                                class="text-muted">{{ $pembelians->where('retur', false)->count() }}</span>
                        </a>
                    </li>
                </ul>

                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Supplier</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="pembelianTable">
                            @foreach ($pembelians as $pembelian)
                                <tr data-status="{{ $pembelian->retur ? 'retur' : 'tidak' }}">
                                    <td>{{ $pembelian->id }}</td>
                                    <td>
                                        <strong>{{ $pembelian->supplier->name ?? 'Unknown' }}</strong><br>
                                        <small class="text-muted">{{ $pembelian->supplier->email ?? '-' }}</small>
                                    </td>
                                    <td>{{ $pembelian->created_at->format('Y-m-d H:i') }}</td>
                                    <td>Rp. {{ number_format($pembelian->total, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge {{ $pembelian->retur ? 'bg-danger' : 'bg-success' }}">
                                            {{ $pembelian->retur ? 'Retur' : 'Tidak Retur' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('detail.pembelian', $pembelian->id) }}"
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
        const tabs = document.querySelectorAll('#pembelianTabs .nav-link');
        const rows = document.querySelectorAll('#pembelianTable tr');

        tabs.forEach(tab => {
            tab.addEventListener('click', function (e) {
                e.preventDefault();

                tabs.forEach(t => t.classList.remove('active', 'border-bottom', 'border-primary', 'fw-semibold'));
                this.classList.add('active', 'border-bottom', 'border-primary', 'fw-semibold');

                const selected = this.getAttribute('data-status');

                rows.forEach(row => {
                    const status = row.getAttribute('data-status');
                    row.style.display = (selected === 'all' || selected === status) ? '' : 'none';
                });
            });
        });
    </script>
</x-app>
