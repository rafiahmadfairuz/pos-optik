<div class="card mt-3 col-lg-4 col-md-6 col-sm-12 shadow rounded-4">
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
            <strong class="float-end">Rp. {{ number_format($total, 2, ',', '.') }}</strong>
        </p>
        <p>
            <i class="bi bi-tag me-2"></i>
            Diskon:
            <strong class="float-end">Rp. {{ number_format($diskon, 2, ',', '.') }}</strong>
        </p>
        <p>
            <i class="bi bi-shield-check me-2"></i>
            Asuransi:
            <strong class="float-end">Rp. {{ number_format($asuransi, 2, ',', '.') }}</strong>
        </p>
        <hr>
        <p>
            <i class="bi bi-calculator me-2"></i>
            Total Final:
            <strong class="float-end">Rp. {{ number_format($finalTotal, 2, ',', '.') }}</strong>
        </p>
        <p>
            <i class="bi bi-wallet2 me-2"></i>
            Dibayar:
            <strong class="float-end">
                Rp. {{ number_format((float) str_replace(['.', ','], ['', '.'], $customer_paying), 2, ',', '.') }}
            </strong>
        </p>
        <hr>
        <p>
            <i class="bi bi-dash-circle  me-2"></i>
            Kurang Bayar:
            <strong class="float-end">Rp. {{ number_format($kurang_bayar, 2, ',', '.') }}</strong>
        </p>

        <p>
            <i class="bi bi-cash-coin me-2"></i>
            Kembalian:
            <strong class="float-end">Rp. {{ number_format($kembalian, 2, ',', '.') }}</strong>
        </p>


    </div>
</div>
