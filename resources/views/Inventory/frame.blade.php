<x-app>
    @section('title', 'Frame')

    <div class="container-fluid">
        <div class="page-header">
            <div class="page-title">
                <h1 class="fw-bold">Frame</h1>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body py-5 px-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">List Frame</h5>
                    <button type="button" class="btn btn-primary px-4 rounded-pill" data-bs-toggle="modal"
                        data-bs-target="#addFrameModal">
                        Tambah Data
                    </button>
                </div>

                <hr class="mb-4 mt-0" style="border-top: 2px solid #dee2e6;">

                <div class="table-responsive mt-3">
                    <table class="table table-borderless align-middle">
                        <thead class="table-light">
                            <tr class="align-middle">
                                <th class="py-3 px-4 fw-bold">MERK</th>
                                <th class="py-3 px-4 fw-bold">TYPE FRAME</th>
                                <th class="py-3 px-4 fw-bold">WARNA</th>
                                <th class="py-3 px-4 fw-bold">HARGA</th>
                                <th class="py-3 px-4 fw-bold">STOK</th>
                                <th class="py-3 px-4 fw-bold">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="align-middle">
                                <td class="py-3 px-4">Ray-Ban</td>
                                <td class="py-3 px-4">Aviator</td>
                                <td class="py-3 px-4">Emas</td>
                                <td class="py-3 px-4">Rp 2.500.000</td>
                                <td class="py-3 px-4">10</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary" data-bs-toggle="modal" data-bs-target="#editFrameModal">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                                  <tr class="align-middle">
                                <td class="py-3 px-4">Vogue</td>
                                <td class="py-3 px-4">Classic</td>
                                <td class="py-3 px-4">Coklat</td>
                                <td class="py-3 px-4">Rp 1.800.000</td>
                                <td class="py-3 px-4">12</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <tr class="align-middle">
                                <td class="py-3 px-4">Gucci</td>
                                <td class="py-3 px-4">Fashion</td>
                                <td class="py-3 px-4">Merah</td>
                                <td class="py-3 px-4">Rp 4.200.000</td>
                                <td class="py-3 px-4">3</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <!-- Tambahkan data lain sesuai kebutuhan -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Frame -->
    <div class="modal fade" id="addFrameModal" tabindex="-1" aria-labelledby="addFrameModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-3">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFrameModalLabel">Tambah Data Frame</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="frameForm">
                        <div class="mb-3">
                            <label for="merk" class="form-label">Merk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="merk" placeholder="Masukkan merk frame" required>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Type Frame <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="type" placeholder="Masukkan type frame" required>
                        </div>
                        <div class="mb-3">
                            <label for="warna" class="form-label">Warna <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="warna" placeholder="Masukkan warna frame" required>
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="harga" placeholder="Masukkan harga dalam Rupiah" required>
                        </div>
                        <div class="mb-3">
                            <label for="stok" class="form-label">Stok <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="stok" placeholder="Masukkan jumlah stok" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" form="frameForm" class="btn btn-primary px-4">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Frame -->
    <div class="modal fade" id="editFrameModal" tabindex="-1" aria-labelledby="editFrameModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-3">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFrameModalLabel">Edit Data Frame</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editFrameForm">
                        <div class="mb-3">
                            <label for="editMerk" class="form-label">Merk <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editMerk" placeholder="Masukkan merk frame" required>
                        </div>
                        <div class="mb-3">
                            <label for="editType" class="form-label">Type Frame <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editType" placeholder="Masukkan type frame" required>
                        </div>
                        <div class="mb-3">
                            <label for="editWarna" class="form-label">Warna <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editWarna" placeholder="Masukkan warna frame" required>
                        </div>
                        <div class="mb-3">
                            <label for="editHarga" class="form-label">Harga <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="editHarga" placeholder="Masukkan harga dalam Rupiah" required>
                        </div>
                        <div class="mb-3">
                            <label for="editStok" class="form-label">Stok <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="editStok" placeholder="Masukkan jumlah stok" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" form="editFrameForm" class="btn btn-primary px-4">Update</button>
                </div>
            </div>
        </div>
    </div>
</x-app>
