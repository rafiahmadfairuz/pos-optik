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

        <div class="row g-3">
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
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transfer->items as $item)
                                @php
                                    $itemable = $item->itemable;
                                    $type = class_basename($item->itemable_type ?? '');
                                    $merk = $itemable->merk ?? ($itemable->nama ?? 'Tidak Diketahui');
                                    $tipe = $itemable->tipe ?? null;
                                @endphp
                                <tr>
                                    <td>
                                        {{ $merk }}
                                        @if ($tipe)
                                            <small class="text-muted">({{ $type }} -
                                                {{ $tipe }})</small>
                                        @else
                                            <small class="text-muted">({{ $type }})</small>
                                        @endif
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mt-3 col-lg-4 col-md-6 col-sm-12 shadow rounded-4 position-relative overflow-hidden">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-decoration-underline">
                        <i class="bi bi-receipt-cutoff me-2"></i>Ringkasan Transfer
                    </span>
                    <small class="text-muted">{{ $transfer->created_at->format('Y-m-d') }}</small>
                </div>
                <div class="card-body fs-5">
                    <p>
                        <i class="bi bi-person-gear me-2"></i>
                        Cabang Tujuan:
                        <strong class="float-end">{{ $transfer->cabang->nama ?? 'Unknown' }}</strong>
                    </p>

                    <p>
                        <i class="bi bi-ticket-detailed me-2"></i>
                        Kode Transfer:
                        <strong class="float-end">{{ $transfer->kode }}</strong>
                    </p>

                    <p class="mt-3">
                        <i class="bi bi-arrow-counterclockwise me-2"></i>
                        Status:
                        <strong class="float-end">
                            @if ($transfer->retur)
                                <span class="badge bg-danger">Retur</span>
                            @else
                                <span class="badge bg-success">Tidak Retur</span>
                            @endif
                        </strong>
                    </p>
                </div>
            </div>

            @if (!$transfer->retur)
                <div class="col-12 mt-3">
                    <form action="{{ route('transfer.retur', $transfer->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Tandai Sebagai Retur & Tambah Stok Gudang
                            Utama
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app>
