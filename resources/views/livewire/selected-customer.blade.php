<div class="col-lg-5 col-md-12">
  <div class="card shadow-sm h-100 border-0 rounded-3">
    <div class="card-header bg-white fw-semibold border-bottom d-flex align-items-center">
      <i class="bi bi-person-circle fs-3  me-2"></i>
      Customer Information
    </div>
    <div class="card-body py-4" style="min-height: 160px;">
      @if ($customer)
        <div class="d-flex align-items-center mb-3 text-truncate" title="{{ $customer['name'] }}">
          <i class="bi bi-person-fill fs-4 text-primary me-3"></i>
          <div>
            <div class="text-muted small">Name</div>
            <strong class="fs-5">{{ $customer['name'] }}</strong>
          </div>
        </div>
        <div class="d-flex align-items-center mb-3 text-truncate" title="{{ $customer['phone'] }}">
          <i class="bi bi-telephone-fill fs-4 text-success me-3"></i>
          <div>
            <div class="text-muted small">Phone</div>
            <span class="fs-5">{{ $customer['phone'] }}</span>
          </div>
        </div>
        <div class="d-flex align-items-center text-truncate" title="{{ $customer['email'] }}">
          <i class="bi bi-envelope-fill fs-4 text-warning me-3"></i>
          <div>
            <div class="text-muted small">Email</div>
            <span class="fs-5">{{ $customer['email'] }}</span>
          </div>
        </div>
      @else
        <div class="text-center text-muted py-5">
          <i class="bi bi-info-circle display-5 d-block mb-3"></i>
          <small>Please select customer first</small>
        </div>
      @endif
    </div>
  </div>
</div>
