<div class="card mt-3 col-md-4">
    <div class="card-header d-flex justify-content-between">
        <span class="fw-bold text-decoration-underline">Bill Summary</span>
        <small class="text-muted">{{ date('Y/m/d') }}</small>
    </div>
    <div class="card-body">
        <p>Total: <strong>Rp. {{ number_format($total ?? 0, 0, ',', '.') }}</strong></p>
        <p>Final Amount: <strong>Rp. {{ number_format($total ?? 0, 0, ',', '.') }}</strong></p>
    </div>
</div>
