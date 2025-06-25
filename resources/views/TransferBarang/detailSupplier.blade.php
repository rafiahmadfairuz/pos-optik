<x-app>
    @section('title', 'Detail Supplier')
    <div class="container-fluid py-4">
        <div class="page-header">
            <div class="page-title">
                <h1 class="fw-bold">Detail Supplier</h1>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-person-fill text-primary fs-3 me-3"></i>
                        <div>
                            <div class="fw-semibold text-muted small">Nama</div>
                            <div class="fw-bold">{{ $supplier->name }}</div>
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
                            <div class="fw-bold">{{ $supplier->email }}</div>
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
                            <div class="fw-bold">{{ $supplier->phone }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-4">Data Pembelian</h5>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Retur</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pembelian as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                                    <td>{{ number_format($item->total, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($item->retur)
                                            <span class="badge bg-danger">Retur</span>
                                        @else
                                            <span class="badge bg-success">Tidak Retur</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('detail.pembelian', $item->id) }}" class="text-primary me-2">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            @if ($pembelian->isEmpty())
                                <tr>
                                    <td colspan="5" class="text-center">Belum ada pembelian dari supplier ini.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app>
