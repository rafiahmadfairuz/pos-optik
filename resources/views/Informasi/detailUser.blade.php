<x-app>
    @section('title', 'Orderan')
    <div class="container-fluid py-4">
        <div class="page-header">
            <div class="page-title">
                <h1 class="fw-bold">Data User</h1>
            </div>
        </div>

          <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <i class="bi bi-person-fill text-primary fs-3 me-3"></i>
                    <div>
                        <div class="fw-semibold text-muted small">Nama</div>
                        <div class="fw-bold">Joko Widodo</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <i class="bi bi-envelope-fill text-success fs-3 me-3"></i>
                    <div>
                        <div class="fw-semibold text-muted small">Email</div>
                        <div class="fw-bold">joko@example.com</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <i class="bi bi-telephone-fill text-danger fs-3 me-3"></i>
                    <div>
                        <div class="fw-semibold text-muted small">No Telp</div>
                        <div class="fw-bold">+62 812-3456-7890</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-4">Orderan </h5>
                <!-- Table -->
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="transactionTable">
                            <tr data-status="paid">
                                <td>2191</td>

                                <td>2025-04-23 02:05</td>
                                <td>3.283.124</td>
                                <td>
                                    <span class="badge text-bg-light text-dark">Ongoing</span>
                                    <span class="badge text-bg-success">Paid</span>
                                </td>
                                <td class="text-center">
                                    <a href="#" class="text-primary me-2"><i class="bi bi-eye"></i></a>
                                </td>
                            </tr>
                            <tr data-status="unpaid">
                                <td>2187</td>

                                <td>2025-04-22 12:11</td>
                                <td>2.101.400</td>
                                <td>
                                    <span class="badge text-bg-light text-dark">Complete</span>
                                    <span class="badge text-bg-danger">Unpaid</span>
                                </td>
                                <td class="text-center">
                                    <a href="#" class="text-primary me-2"><i class="bi bi-eye"></i></a>
                                </td>
                            </tr>
                            <tr data-status="partially">
                                <td>2184</td>

                                <td>2025-04-20 14:32</td>
                                <td>1.800.000</td>
                                <td>
                                    <span class="badge text-bg-light text-dark">Ongoing</span>
                                    <span class="badge text-bg-warning text-dark">Partially Paid</span>
                                </td>
                                <td class="text-center">
                                    {{-- <a href="{{ route('orderan.detail') }}" class="text-primary me-2"><i class="bi bi-eye"></i></a> --}}
                                </td>
                            </tr>
                            <tr data-status="paid">
                                <td>2181</td>

                                <td>2025-04-19 08:09</td>
                                <td>2.500.000</td>
                                <td>
                                    <span class="badge text-bg-light text-dark">Complete</span>
                                    <span class="badge text-bg-success">Paid</span>
                                </td>
                                <td class="text-center">
                                    <a href="#" class="text-primary me-2"><i class="bi bi-eye"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>


</x-app>
