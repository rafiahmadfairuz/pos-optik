<div class="card mt-3 col-md-4">
    <div class="card-header d-flex justify-content-between">
        <span class="fw-bold text-decoration-underline">Bill Summary</span>
        <small class="text-muted">{{ date('Y/m/d') }}</small>
    </div>
    <div class="card-body">
        <p>Total: <strong>Rp. {{ number_format($total, 0, ',', '.') }}</strong></p>

        <p>Asuransi: <strong> Rp. {{ number_format($asuransi, 0, ',', '.') }}</strong></p>

        <p>Total Final: <strong>Rp. {{ number_format($finalTotal, 0, ',', '.') }}</strong></p>

        <p>Dibayar Customer: <strong>Rp. {{ number_format($customerPaying, 0, ',', '.') }}</strong></p>

        <p>Kembalian: <strong>Rp. {{ number_format($kembalian, 0, ',', '.') }}</strong></p>
    </div>
</div>
