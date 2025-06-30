<div class="card mt-3 col-lg-12 col-md-12 col-sm-12 shadow rounded-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-bold text-decoration-underline">
            <i class="bi bi-receipt-cutoff me-2"></i>Bill Summary
        </span>
        <small class="text-muted">{{ date('Y/m/d') }}</small>
    </div>
    <div class="card-body fs-5">

        <p>
            <i class="bi bi-cash-stack me-2"></i>
            Total:
            <strong class="float-end">Rp. {{ number_format((int) $total, 0, ',', '.') }}</strong>
        </p>

        <p>
            <i class="bi bi-tag me-2"></i>
            Diskon:
            <strong class="float-end">Rp. {{ number_format((int) $diskon, 0, ',', '.') }}</strong>
        </p>

        <p>
            <i class="bi bi-shield-check me-2"></i>
            Asuransi:
            <strong class="float-end">Rp. {{ number_format((int) $asuransi, 0, ',', '.') }}</strong>
        </p>

        <hr>

        <p>
            <i class="bi bi-calculator me-2"></i>
            Total Final:
            <strong class="float-end">Rp. {{ number_format((int) $finalTotal, 0, ',', '.') }}</strong>
        </p>

        <p>
            <i class="bi bi-wallet2 me-2"></i>
            Dibayar:
            <strong class="float-end">
                Rp. {{ number_format((int) str_replace('.', '', $customer_paying), 0, ',', '.') }}
            </strong>

        </p>

        <hr>

        <p>
            <i class="bi bi-dash-circle me-2"></i>
            Kurang Bayar:
            <strong class="float-end">Rp. {{ number_format((int) $kurang_bayar, 0, ',', '.') }}</strong>
        </p>

        <p>
            <i class="bi bi-cash-coin me-2"></i>
            Kembalian:
            <strong class="float-end">Rp. {{ number_format((int) $kembalian, 0, ',', '.') }}</strong>
        </p>

    </div>
</div>
