<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Homepage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap');
        body {
              font-family: "Nunito", sans-serif;
        }
    </style>
</head>
<body>
<div class="main-wrapper">
  <x-header :showToggle="false"></x-header>

    <div class=" vh-100 d-flex justify-content-center align-items-center">
    <div class="content">
        <div class="container py-5 d-flex flex-column align-items-center">
  <!-- Logo -->
  <div class="mb-4 text-center">
    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="bi bi-building" viewBox="0 0 16 16">
      <path d="M14 14V1h-3v2H5V1H2v13H0v1h16v-1h-2zM4 14V6h1v8H4zm3 0V6h1v8H7zm3 0V6h1v8h-1z"/>
    </svg>
    <h2 class="mt-2 fw-bold">Pilih Cabang</h2>
  </div>

  <!-- Cards for branches -->
  <div class="row w-100 justify-content-center g-4">
    <!-- Cabang 1 -->
    <a href="{{url("")}}" class="col-12 col-sm-6 col-lg-3">
      <div class="card border-0 shadow-lg rounded-4 text-center p-4 branch-card" role="button">
        <div class="mb-3">
          <i class="bi bi-shop-window" style="font-size: 3rem; color: #0d6efd;"></i>
        </div>
        <h5 class="fw-semibold">Cabang Jakarta</h5>
        <p class="text-muted">Lihat data dan laporan cabang Jakarta.</p>
      </div>
    </a>
    <!-- Cabang 2 -->
    <div class="col-12 col-sm-6 col-lg-3">
      <div class="card border-0 shadow-lg rounded-4 text-center p-4 branch-card" role="button">
        <div class="mb-3">
          <i class="bi bi-building" style="font-size: 3rem; color: #198754;"></i>
        </div>
        <h5 class="fw-semibold">Cabang Bandung</h5>
        <p class="text-muted">Lihat data dan laporan cabang Bandung.</p>
      </div>
    </div>
    <!-- Cabang 3 -->
    <div class="col-12 col-sm-6 col-lg-3">
      <div class="card border-0 shadow-lg rounded-4 text-center p-4 branch-card" role="button">
        <div class="mb-3">
          <i class="bi bi-shop" style="font-size: 3rem; color: #fd7e14;"></i>
        </div>
        <h5 class="fw-semibold">Cabang Surabaya</h5>
        <p class="text-muted">Lihat data dan laporan cabang Surabaya.</p>
      </div>
    </div>
    <!-- Cabang 4 -->
    <div class="col-12 col-sm-6 col-lg-3">
      <div class="card border-0 shadow-lg rounded-4 text-center p-4 branch-card" role="button">
        <div class="mb-3">
          <i class="bi bi-building-check" style="font-size: 3rem; color: #dc3545;"></i>
        </div>
        <h5 class="fw-semibold">Cabang Medan</h5>
        <p class="text-muted">Lihat data dan laporan cabang Medan.</p>
      </div>
    </div>
  </div>
</div>

<style>
  .branch-card {
    cursor: pointer;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  .branch-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 1rem 2rem rgba(13,110,253,0.3);
    background: #f8f9fa;
  }
</style>



    </div>
    </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
</body>
</html>
