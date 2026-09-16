<x-app>
    @section('title', 'Detail Transfer')

    <style>
        .card-scroll {
            max-height: 200px;
            overflow-y: auto;
        }

        .table thead th {
            position: sticky;
            top: 0;
            background: #fff;
        }
    </style>

    <div class="container-fluid py-3">
        <h2 class="fw-bold"><i class="bi bi-card-list me-2"></i>Detail Transfer</h2>

        {{-- Alert Notifikasi Sukses / Gagal --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-3">
            {{-- Tabel Detail Item --}}
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header fw-bold text-decoration-underline d-flex align-items-center gap-2">
                        <i class="bi bi-box-seam"></i> Detail Item
                    </div>
                    <table class="table table-hover align-middle p-1">
                        <thead class="table-light">
                            <tr>
                                <th>Item</th>
                                <th>Harga Acuan</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transfer->items as $item)
                                @php
                                    $itemable = $item->itemable;
                                    $type = class_basename($item->itemable_type ?? '');
                                    $merk = $itemable->merk ?? ($itemable->nama ?? 'Tidak Diketahui / Barang Telah Dihapus');
                                    $tipe = $itemable->tipe ?? null;
                                @endphp
                                <tr>
                                    <td>
                                        {{ $merk }}
                                        @if ($tipe)
                                            <small class="text-muted">({{ $type }} - {{ $tipe }})</small>
                                        @else
                                            <small class="text-muted">({{ $type }})</small>
                                        @endif
                                    </td>
                                    <td>Rp. {{ number_format($item->price ?? 0, 0, ',', '.') }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rp. {{ number_format(($item->price ?? 0) * $item->quantity, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Kartu Ringkasan Transfer --}}
            <div class="card mt-3 col-lg-5 col-md-8 col-sm-12 shadow rounded-4 position-relative overflow-hidden">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-decoration-underline">
                        <i class="bi bi-receipt-cutoff me-2"></i>Ringkasan Transfer
                    </span>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($transfer->tanggal)->format('Y-m-d') }}</small>
                </div>
                <div class="card-body fs-6">
                    <p>
                        <i class="bi bi-geo-alt me-2"></i>
                        Cabang Asal:
                        <strong class="float-end">
                            {{ $transfer->from_cabang_id == 0 ? 'Gudang Utama' : ($transfer->fromCabang->nama ?? 'Cabang ' . $transfer->from_cabang_id) }}
                        </strong>
                    </p>

                    <p>
                        <i class="bi bi-geo-fill me-2"></i>
                        Cabang Tujuan:
                        <strong class="float-end">
                            {{ $transfer->to_cabang_id == 0 ? 'Gudang Utama' : ($transfer->toCabang->nama ?? 'Cabang ' . $transfer->to_cabang_id) }}
                        </strong>
                    </p>

                    <p>
                        <i class="bi bi-ticket-detailed me-2"></i>
                        Kode Transfer:
                        <strong class="float-end">{{ $transfer->kode }}</strong>
                    </p>

                    <p class="mt-3">
                        <i class="bi bi-arrow-counterclockwise me-2"></i>
                        Status Transaksi:
                        <strong class="float-end">
                            @if ($transfer->status === 'returned')
                                <span class="badge bg-danger"><i class="bi bi-arrow-return-left me-1"></i> Diretur ke Gudang Utama</span>
                            @elseif ($transfer->status === 'transferred_out')
                                <span class="badge bg-secondary"><i class="bi bi-arrow-right-circle me-1"></i> Dialihkan ke Cabang Lain</span>
                            @else
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Stok Aktif di Cabang</span>
                            @endif
                        </strong>
                    </p>
                </div>
            </div>

            {{-- TOMBOL AKSI: Hanya Muncul Jika Status Masih 'completed' & Barang Bukan di Gudang Utama --}}
            @if ($transfer->status === 'completed' && $transfer->to_cabang_id != 0)
                <div class="col-12 mt-3">
                    <div class="d-flex flex-wrap gap-2 align-items-start">
                        <form action="{{ route('transfer.retur', $transfer->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning" onclick="return confirm('Proses retur barang kembali ke Gudang Utama?')">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Retur Ke Gudang Utama
                            </button>
                        </form>
                    </div>

                    <form action="{{ route('transfer.ke.cabang', $transfer->id) }}" method="POST" class="mt-3 card p-3 shadow-sm border-0 col-lg-5">
                        @csrf
                        <div class="mb-2">
                            <label for="target_cabang_id" class="form-label fw-bold">Alihkan Stok Ke Cabang Lain</label>
                            <input type="number" name="target_cabang_id" id="target_cabang_id" class="form-control"
                                placeholder="Masukkan ID Cabang Tujuan (1-4)" required min="1" max="4" step="1">
                            @error('target_cabang_id')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-secondary" onclick="return confirm('Proses pengalihan stok ke cabang lain?')">
                            <i class="bi bi-box-arrow-right me-1"></i> Transfer Ke Cabang Lain
                        </button>
                    </form>
                </div>
            @else
                <div class="col-12 mt-3">
                    <div class="alert alert-secondary d-inline-block">
                        <i class="bi bi-lock-fill me-1"></i> Transaksi ini sudah dikunci (Closed) dan tidak dapat dimutasi lagi.
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app>
