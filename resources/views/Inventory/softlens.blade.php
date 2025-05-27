<x-app>
    @section('title', 'Softlens')
    <div class="container-fluid">
        <div class="page-header">
            <div class="page-title">
                <h1 class="fw-bold">Softlens</h1>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body py-5 px-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">List Softlens</h5>
                    <button type="button" class="btn btn-primary px-4 rounded-pill" data-bs-toggle="modal"
                        data-bs-target="#addSoftlensModal">
                        Tambah Data
                    </button>
                </div>

                <hr class="mb-4 mt-0" style="border-top: 2px solid #dee2e6;">

                <div class="table-responsive mt-3">
                    <table class="table table-borderless align-middle">
                        <thead class="table-light">
                            <tr class="align-middle">
                                <th class="py-3 px-4 fw-bold">MERK</th>
                                <th class="py-3 px-4 fw-bold">TYPE</th>
                                <th class="py-3 px-4 fw-bold">WARNA</th>
                                <th class="py-3 px-4 fw-bold">HARGA</th>
                                <th class="py-3 px-4 fw-bold">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="align-middle">
                                <td class="py-3 px-4">Acuvue</td>
                                <td class="py-3 px-4">Daily</td>
                                <td class="py-3 px-4">Blue</td>
                                <td class="py-3 px-4">Rp 200.000</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <tr class="align-middle">
                                <td class="py-3 px-4">Air Optix</td>
                                <td class="py-3 px-4">Monthly</td>
                                <td class="py-3 px-4">Gray</td>
                                <td class="py-3 px-4">Rp 250.000</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <tr class="align-middle">
                                <td class="py-3 px-4">FreshLook</td>
                                <td class="py-3 px-4">Daily</td>
                                <td class="py-3 px-4">Green</td>
                                <td class="py-3 px-4">Rp 180.000</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <tr class="align-middle">
                                <td class="py-3 px-4">Bausch + Lomb</td>
                                <td class="py-3 px-4">Monthly</td>
                                <td class="py-3 px-4">Brown</td>
                                <td class="py-3 px-4">Rp 230.000</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <tr class="align-middle">
                                <td class="py-3 px-4">Dailies</td>
                                <td class="py-3 px-4">Daily</td>
                                <td class="py-3 px-4">Gray</td>
                                <td class="py-3 px-4">Rp 220.000</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <tr class="align-middle">
                                <td class="py-3 px-4">FreshLook Colorblends</td>
                                <td class="py-3 px-4">Monthly</td>
                                <td class="py-3 px-4">Hazel</td>
                                <td class="py-3 px-4">Rp 270.000</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <tr class="align-middle">
                                <td class="py-3 px-4">Clariti</td>
                                <td class="py-3 px-4">Monthly</td>
                                <td class="py-3 px-4">Blue</td>
                                <td class="py-3 px-4">Rp 210.000</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <tr class="align-middle">
                                <td class="py-3 px-4">Air Optix Night & Day</td>
                                <td class="py-3 px-4">Monthly</td>
                                <td class="py-3 px-4">Clear</td>
                                <td class="py-3 px-4">Rp 300.000</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <tr class="align-middle">
                                <td class="py-3 px-4">Biofinity</td>
                                <td class="py-3 px-4">Monthly</td>
                                <td class="py-3 px-4">Gray</td>
                                <td class="py-3 px-4">Rp 280.000</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <tr class="align-middle">
                                <td class="py-3 px-4">Acuvue Oasys</td>
                                <td class="py-3 px-4">Weekly</td>
                                <td class="py-3 px-4">Blue</td>
                                <td class="py-3 px-4">Rp 190.000</td>
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
    <div class="modal fade" id="addSoftlensModal" tabindex="-1" aria-labelledby="addSoftlensModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-3">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSoftlensModalLabel">Tambah Softlens</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="softlensForm">
                        <div class="mb-3">
                            <label for="merkInput" class="form-label">Merk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="merkInput" required>
                        </div>
                        <div class="mb-3">
                            <label for="typeInput" class="form-label">Type <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="typeInput" required>
                        </div>
                        <div class="mb-3">
                            <label for="warnaInput" class="form-label">Warna <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="warnaInput" required>
                        </div>
                        <div class="mb-3">
                            <label for="hargaInput" class="form-label">Harga <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="hargaInput" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="softlensForm" class="btn btn-primary px-4">Save</button>
                </div>
            </div>
        </div>
    </div>
</x-app>
