<x-app>
    @section('title', 'Orderan')

    <div class="container-fluid py-4">
        <div class="page-header">
            <div class="page-title">
                <h1 class="fw-bold">List Orderan</h1>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-4">Orderan</h5>

                <!-- Tabs -->
                <ul class="nav border-bottom mb-4" id="transactionTabs">
                    <li class="nav-item">
                        <a class="nav-link active border-0 border-bottom border-primary fw-semibold" href="#" data-status="all">
                            All <span class="text-muted">2207</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link border-0" href="#" data-status="paid">
                            Paid <span class="text-muted">1779</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link border-0" href="#" data-status="partially">
                            Partially Paid <span class="text-muted">216</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link border-0" href="#" data-status="unpaid">
                            Unpaid <span class="text-muted">212</span>
                        </a>
                    </li>
                </ul>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="transactionTable">
                            <tr data-status="paid">
                                <td>2191</td>
                                <td>
                                    <strong>Brenna Langosh</strong><br>
                                    <small class="text-muted">1-513-216-6602</small>
                                </td>
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
                                <td>
                                    <strong>Wade Warren</strong><br>
                                    <small class="text-muted">(775) 732-0014</small>
                                </td>
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
                                <td>
                                    <strong>Kathryn Murphy</strong><br>
                                    <small class="text-muted">615.922.6912</small>
                                </td>
                                <td>2025-04-20 14:32</td>
                                <td>1.800.000</td>
                                <td>
                                    <span class="badge text-bg-light text-dark">Ongoing</span>
                                    <span class="badge text-bg-warning text-dark">Partially Paid</span>
                                </td>
                                <td class="text-center">
                                    <a href="#" class="text-primary me-2"><i class="bi bi-eye"></i></a>
                                </td>
                            </tr>
                            <tr data-status="paid">
                                <td>2181</td>
                                <td>
                                    <strong>Theresa Webb</strong><br>
                                    <small class="text-muted">+62 813-3178-1117</small>
                                </td>
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

    <script>
        const tabs = document.querySelectorAll('#transactionTabs .nav-link');
        const rows = document.querySelectorAll('#transactionTable tr');

        tabs.forEach(tab => {
            tab.addEventListener('click', function (e) {
                e.preventDefault();
                tabs.forEach(t => {
                    t.classList.remove('active', 'border-bottom', 'border-primary', 'fw-semibold');
                });
                this.classList.add('active', 'border-bottom', 'border-primary', 'fw-semibold');

                let selected = this.getAttribute('data-status');
                rows.forEach(row => {
                    if (selected === 'all' || row.dataset.status === selected) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
</x-app>
