<x-app>
    @section('title', 'Detail Supplier')
    <div class="container-fluid py-4">
        <div class="page-header">
            <div class="page-title">
                <h1 class="fw-bold">Data User</h1>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-person-fill text-primary fs-3 me-3"></i>
                        <div>
                            <div class="fw-semibold text-muted small">Nama</div>
                            <div class="fw-bold">{{ $customer->name }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-envelope-fill text-success fs-3 me-3"></i>
                        <div>
                            <div class="fw-semibold text-muted small">Email</div>
                            <div class="fw-bold">{{ $customer->email }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-telephone-fill text-danger fs-3 me-3"></i>
                        <div>
                            <div class="fw-semibold text-muted small">No Telp</div>
                            <div class="fw-bold">{{ $customer->phone }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-4">Orderan</h5>
                <!-- Table -->
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Tanggal Order</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="transactionTable">
                            @foreach ($orderan as $item)
                                <tr data-status="{{ strtolower($item->payment_status) }}">
                                    <td>{{ $item->id }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->order_date)->format('d-m-Y') }}</td>
                                    <td>{{ number_format($item->total, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="d-flex gap-1 flex-wrap">
                                            @if ($item->order_status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif ($item->order_status === 'complete')
                                                <span class="badge bg-success">Complete</span>
                                            @else
                                                <span class="badge bg-secondary">Unknown</span>
                                            @endif

                                            @if ($item->payment_status === 'paid')
                                                <span class="badge bg-success-subtle text-success">Paid</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger">Unpaid</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('orderan.detail', $item->id) }}" class="text-primary me-2">
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


</x-app>
