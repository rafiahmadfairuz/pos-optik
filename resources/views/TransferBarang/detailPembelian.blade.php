<x-app>
    @section('title', 'Detail Pembelian')

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
        <h2 class="fw-bold"><i class="bi bi-card-list me-2"></i>Detail Pembelian</h2>

        <div class="row g-3">
            {{-- Informasi item pembelian --}}
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header fw-bold text-decoration-underline d-flex align-items-center gap-2">
                        <i class="bi bi-box-seam"></i> Detail Item
                    </div>
                    <table class="table table-hover align-middle p-1">
                        <thead class="table-light">
                            <tr>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pembelian->items as $item)
                                @php
                                    $itemable = $item->itemable;
                                    $type = class_basename($item->itemable_type ?? '');
                                    $merk = $itemable->merk ?? 'Merk Tidak Diketahui / Barang Telah Dihapus';
                                    $tipeProduk = $itemable->tipe ?? null;
                                @endphp
                                <tr>
                                    <td>
                                        {{ $merk }}
                                        @if ($tipeProduk)
                                            <small class="text-muted">({{ $type }} -
                                                {{ $tipeProduk }})</small>
                                        @else
                                            <small class="text-muted">({{ $type }})</small>
                                        @endif
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>

            {{-- Ringkasan pembelian --}}
            <div class="card mt-3 col-lg-4 col-md-6 col-sm-12 shadow rounded-4 position-relative overflow-hidden">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-decoration-underline">
                        <i class="bi bi-receipt-cutoff me-2"></i>Ringkasan Pembelian
                    </span>
                    <small class="text-muted">{{ $pembelian->created_at->format('Y-m-d') }}</small>
                </div>
                <div class="card-body fs-5">
                    <p>
                        <i class="bi bi-cash-stack me-2"></i>
                        Total:
                        <strong class="float-end">Rp.
                            {{ number_format((float) ($pembelian->total ?? 0), 2, ',', '.') }}
                        </strong>
                    </p>

                    <p>
                        <i class="bi bi-person-circle me-2"></i>
                        Supplier:
                        <strong class="float-end">{{ $pembelian->supplier->name ?? 'Unknown' }}</strong>
                    </p>

                    <p class="mt-3">
                        <i class="bi bi-arrow-counterclockwise me-2"></i>
                        Status:
                        <strong class="float-end">
                            @if ($pembelian->retur)
                                <span class="badge bg-danger">Retur</span>
                            @else
                                <span class="badge bg-success">Tidak Retur</span>
                            @endif
                        </strong>
                    </p>
                </div>
            </div>

            {{-- Tombol retur --}}
            @if (!$pembelian->retur)
                <div class="col-12 mt-3">
                    <form action="{{ route('pembelian.retur', $pembelian->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Tandai Sebagai Retur & Kurangi Stok
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app>
