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
            <strong class="float-end">Rp. {{ number_format($total, 0, ',', '.') }}</strong>
        </p>
        <p>
            <i class="bi bi-shield-check me-2"></i>
            Asuransi:
            <strong class="float-end">Rp. {{ number_format($asuransi, 0, ',', '.') }}</strong>
        </p>
        <hr>
        <p>
            <i class="bi bi-calculator me-2"></i>
            Total Final:
            <strong class="float-end">Rp. {{ number_format($finalTotal, 0, ',', '.') }}</strong>
        </p>
        <p>
            <i class="bi bi-wallet2 me-2"></i>
            Dibayar Customer:
            <strong class="float-end">
                Rp. {{ number_format((int) preg_replace('/[^\d]/', '', $customer_paying), 0, ',', '.') }}
            </strong>
        </p>
        <hr>
        <p>
            <i class="bi bi-arrow-repeat me-2"></i>
            Kembalian:
            <strong class="float-end">Rp. {{ number_format($kembalian, 0, ',', '.') }}</strong>
        </p>
    </div>
</div>
