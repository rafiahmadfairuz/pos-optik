<x-app>
    @section('Asuransi', 'Dashboard')
    <div class="container-fluid">
        <div class="page-header">
            <div class="page-title">
                <h1 class="fw-bold">Asuransi</h1>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body py-5 px-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">List Asuransi</h5>
                    <button type="button" class="btn btn-primary px-4 rounded-pill" data-bs-toggle="modal"
                        data-bs-target="#addInsuranceModal">
                        Tambah Data
                    </button>
                </div>

                <hr class="mb-4 mt-0" style="border-top: 2px solid #dee2e6;">

                <div class="table-responsive mt-3">
                    <table class="table table-borderless align-middle">
                        <thead class="table-light">
                            <tr class="align-middle">
                                <th class="py-3 px-4 fw-bold">NAMA</th>
                                <th class="py-3 px-4 fw-bold">NOMINAL</th>
                                <th class="py-3 px-4 fw-bold">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="align-middle">
                                <td class="py-3 px-4">BPJS Level 1</td>
                                <td class="py-3 px-4">Rp 150.000</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary" data-bs-toggle="modal" data-bs-target="#editInsuranceModal">
    <i class="bi bi-pencil-fill"></i>
</a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <tr class="align-middle">
                                <td class="py-3 px-4">BPJS Level 2</td>
                                <td class="py-3 px-4">Rp 100.000</td>
                                <td class="py-3 px-4">
                                   <a href="#" class="me-3 text-primary" data-bs-toggle="modal" data-bs-target="#editInsuranceModal">
    <i class="bi bi-pencil-fill"></i>
</a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <tr class="align-middle">
                                <td class="py-3 px-4">BPJS Level 3</td>
                                <td class="py-3 px-4">Rp 50.000</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addInsuranceModal" tabindex="-1" aria-labelledby="addInsuranceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-3">
                <div class="modal-header">
                    <h5 class="modal-title" id="addInsuranceModalLabel">Add Insurance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="insuranceForm">
                        <div class="mb-3">
                            <label for="insuranceName" class="form-label">Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="insuranceName" required>
                        </div>
                        <div class="mb-3">
                            <label for="insuranceNominal" class="form-label">Nominal <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="insuranceNominal" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="insuranceForm" class="btn btn-primary px-4">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
<div class="modal fade" id="editInsuranceModal" tabindex="-1" aria-labelledby="editInsuranceModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title" id="editInsuranceModalLabel">Edit Insurance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editInsuranceForm">
                    <div class="mb-3">
                        <label for="editInsuranceName" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editInsuranceName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editInsuranceNominal" class="form-label">Nominal <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="editInsuranceNominal" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="editInsuranceForm" class="btn btn-primary px-4">Update</button>
            </div>
        </div>
    </div>
</div>

</x-app>
