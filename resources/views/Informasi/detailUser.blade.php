<x-app>
    @section('title', 'Detail User & Resep')

    <div class="container-fluid py-4">
        <!-- HEADER PROFILE USER -->
        <div class="page-header mb-3">
            <div class="page-title d-flex justify-content-between align-items-center">
                <h2 class="fw-bold"><i class="bi bi-person-bounding-box me-2"></i>Data User / Pelanggan</h2>
                <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-person-fill text-primary fs-3 me-3"></i>
                        <div>
                            <div class="fw-semibold text-muted small">Nama</div>
                            <div class="fw-bold">{{ $customer->name ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-envelope-fill text-success fs-3 me-3"></i>
                        <div>
                            <div class="fw-semibold text-muted small">Email</div>
                            <div class="fw-bold">{{ $customer->email ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-telephone-fill text-danger fs-3 me-3"></i>
                        <div>
                            <div class="fw-semibold text-muted small">No Telp</div>
                            <div class="fw-bold">{{ $customer->phone ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alamat -->
            <div class="col-12 col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-geo-alt-fill text-warning fs-3 me-3"></i>
                        <div>
                            <div class="fw-semibold text-muted small">Alamat</div>
                            <div class="fw-bold">{{ $customer->alamat ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Umur -->
            <div class="col-12 col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-calendar-fill text-info fs-3 me-3"></i>
                        <div>
                            <div class="fw-semibold text-muted small">Umur</div>
                            <div class="fw-bold">{{ $customer->umur ? $customer->umur . ' tahun' : '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gender -->
            <div class="col-12 col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-gender-ambiguous text-secondary fs-3 me-3"></i>
                        <div>
                            <div class="fw-semibold text-muted small">Gender</div>
                            <div class="fw-bold">{{ ucfirst($customer->gender ?? '-') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- TABEL 1: RIWAYAT RESEP KACAMATA USER -->
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0 text-primary">
                                <i class="bi bi-journal-medical me-2"></i>Riwayat Resep Kacamata
                            </h5>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID Resep</th>
                                        <th>Tgl Pemeriksaan</th>
                                        <th>Pemeriksa</th>
                                        <th>OD (SPH / CYL / AXIS)</th>
                                        <th>OS (SPH / CYL / AXIS)</th>
                                        <th>Catatan</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($reseps ?? [] as $resep)
                                        <tr>
                                            <td class="fw-bold">#{{ $resep->id }}</td>
                                            <td>{{ $resep->tanggal_pemeriksaan ? \Carbon\Carbon::parse($resep->tanggal_pemeriksaan)->format('d-m-Y') : '-' }}</td>
                                            <td>{{ $resep->staff->name ?? 'Tidak Ditentukan' }}</td>
                                            <td>
                                                <small class="badge bg-light text-dark border">
                                                    {{ $resep->od_sph ?? '0.00' }} / {{ $resep->od_cyl ?? '0.00' }} / {{ $resep->od_axis ?? '0' }}°
                                                </small>
                                            </td>
                                            <td>
                                                <small class="badge bg-light text-dark border">
                                                    {{ $resep->os_sph ?? '0.00' }} / {{ $resep->os_cyl ?? '0.00' }} / {{ $resep->os_axis ?? '0' }}°
                                                </small>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ Str::limit($resep->notes ?? '-', 30) }}</small>
                                            </td>
                                            <td class="text-center">
                                                <!-- TOMBOL PENSIL (EDIT RESEP - DISIAPKAN UNTUK NANTI) -->
                                            <a href="{{ route('resep.edit', $resep->id) }}"
   class="btn btn-sm btn-outline-warning"
   title="Edit Resep">
    <i class="bi bi-pencil-fill"></i>
</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-3">
                                                <i class="bi bi-info-circle me-1"></i> Pelanggan ini belum memiliki riwayat resep kacamata.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABEL 2: RIWAYAT TRANSAKSI / ORDERAN USER -->
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3 text-dark">
                            <i class="bi bi-cart-check me-2"></i>Riwayat Orderan
                        </h5>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID Order</th>
                                        <th>Tanggal Order</th>
                                        <th>Total Transaksi</th>
                                        <th>Status Order & Bayar</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="transactionTable">
                                    @forelse ($orderan as $item)
                                        <tr data-status="{{ strtolower($item->payment_status) }}">
                                            <td class="fw-bold">#{{ $item->id }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->order_date)->format('d-m-Y') }}</td>
                                            <td>Rp {{ number_format($item->total, 0, ',', '.') }}</td>
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
                                                <a href="{{ route('orderan.detail', $item->id) }}" class="btn btn-sm btn-outline-primary" title="Lihat Detail Order">
                                                    <i class="bi bi-eye-fill"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-3">
                                                Belum ada riwayat orderan untuk pelanggan ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app>
