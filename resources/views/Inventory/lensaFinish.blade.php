<x-app>
    @section('title', 'Lensa Finish')

    <div class="container-fluid">
        <div class="page-header">
            <div class="page-title">
                <h1 class="fw-bold">Lensa Finish</h1>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body py-5 px-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">List Lensa Finish</h5>
                    <button type="button" class="btn btn-primary px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#addLensaModal">
                        Tambah Data
                    </button>
                </div>

                <hr class="mb-4 mt-0" style="border-top: 2px solid #dee2e6;">

                <div class="table-responsive mt-3">
                    <table class="table table-borderless align-middle">
                        <thead class="table-light">
                            <tr class="align-middle">
                                <th class="py-3 px-4 fw-bold">MERK</th>
                                <th class="py-3 px-4 fw-bold">DESAIN</th>
                                <th class="py-3 px-4 fw-bold">TYPE</th>
                                <th class="py-3 px-4 fw-bold">SPH</th>
                                <th class="py-3 px-4 fw-bold">CYL</th>
                                <th class="py-3 px-4 fw-bold">ADD</th>
                                <th class="py-3 px-4 fw-bold">STOK</th>
                                <th class="py-3 px-4 fw-bold">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="align-middle">
                                <td class="py-3 px-4">Ray-Ban</td>
                                <td class="py-3 px-4">Aviator</td>
                                <td class="py-3 px-4">Emas</td>
                                <td class="py-3 px-4">-2.50</td>
                                <td class="py-3 px-4">-1.00</td>
                                <td class="py-3 px-4">+1.50</td>
                                <td class="py-3 px-4">50</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary" data-bs-toggle="modal" data-bs-target="#editLensaModal">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <tr class="align-middle">
                                <td class="py-3 px-4">Oakley</td>
                                <td class="py-3 px-4">Sport</td>
                                <td class="py-3 px-4">Hitam</td>
                                <td class="py-3 px-4">-3.00</td>
                                <td class="py-3 px-4">-0.50</td>
                                <td class="py-3 px-4">+2.00</td>
                                <td class="py-3 px-4">120</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary" data-bs-toggle="modal" data-bs-target="#editLensaModal">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <tr class="align-middle">
                                <td class="py-3 px-4">Vogue</td>
                                <td class="py-3 px-4">Classic</td>
                                <td class="py-3 px-4">Coklat</td>
                                <td class="py-3 px-4">-1.75</td>
                                <td class="py-3 px-4">-1.25</td>
                                <td class="py-3 px-4">+1.75</td>
                                <td class="py-3 px-4">80</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary" data-bs-toggle="modal" data-bs-target="#editLensaModal">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <tr class="align-middle">
                                <td class="py-3 px-4">Gucci</td>
                                <td class="py-3 px-4">Fashion</td>
                                <td class="py-3 px-4">Merah</td>
                                <td class="py-3 px-4">-4.00</td>
                                <td class="py-3 px-4">-0.75</td>
                                <td class="py-3 px-4">+2.50</td>
                                <td class="py-3 px-4">40</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary" data-bs-toggle="modal" data-bs-target="#editLensaModal">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Lensa -->
    <div class="modal fade" id="addLensaModal" tabindex="-1" aria-labelledby="addLensaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-3">
                <div class="modal-header">
                    <h5 class="modal-title" id="addLensaModalLabel">Tambah Data Lensa Finish</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addLensaForm">
                        <div class="mb-3">
                            <label for="merk" class="form-label">Merk</label>
                            <input type="text" class="form-control" id="merk" required>
                        </div>
                        <div class="mb-3">
                            <label for="desain" class="form-label">Desain</label>
                            <input type="text" class="form-control" id="desain" required>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Type</label>
                            <input type="text" class="form-control" id="type" required>
                        </div>
                        <div class="mb-3">
                            <label for="sph" class="form-label">SPH</label>
                            <input type="text" class="form-control" id="sph" required>
                        </div>
                        <div class="mb-3">
                            <label for="cyl" class="form-label">CYL</label>
                            <input type="text" class="form-control" id="cyl" required>
                        </div>
                        <div class="mb-3">
                            <label for="add" class="form-label">ADD</label>
                            <input type="text" class="form-control" id="add" required>
                        </div>
                        <div class="mb-3">
                            <label for="stok" class="form-label">Stok</label>
                            <input type="number" class="form-control" id="stok" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" form="addLensaForm" class="btn btn-primary px-4">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Lensa -->
    <div class="modal fade" id="editLensaModal" tabindex="-1" aria-labelledby="editLensaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-3">
                <div class="modal-header">
                    <h5 class="modal-title" id="editLensaModalLabel">Edit Data Lensa Finish</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editLensaForm">
                        <div class="mb-3">
                            <label for="editMerk" class="form-label">Merk</label>
                            <input type="text" class="form-control" id="editMerk" required>
                        </div>
                        <div class="mb-3">
                            <label for="editDesain" class="form-label">Desain</label>
                            <input type="text" class="form-control" id="editDesain" required>
                        </div>
                        <div class="mb-3">
                            <label for="editType" class="form-label">Type</label>
                            <input type="text" class="form-control" id="editType" required>
                        </div>
                        <div class="mb-3">
                            <label for="editSph" class="form-label">SPH</label>
                            <input type="text" class="form-control" id="editSph" required>
                        </div>
                        <div class="mb-3">
                            <label for="editCyl" class="form-label">CYL</label>
                            <input type="text" class="form-control" id="editCyl" required>
                        </div>
                        <div class="mb-3">
                            <label for="editAdd" class="form-label">ADD</label>
                            <input type="text" class="form-control" id="editAdd" required>
                        </div>
                        <div class="mb-3">
                            <label for="editStok" class="form-label">Stok</label>
                            <input type="number" class="form-control" id="editStok" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" form="editLensaForm" class="btn btn-primary px-4">Update</button>
                </div>
            </div>
        </div>
    </div>
</x-app>
