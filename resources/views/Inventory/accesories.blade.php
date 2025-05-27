<x-app>
    @section('title', 'Aksesoris')
    <div class="container-fluid">
        <div class="page-header">
            <div class="page-title">
                <h1 class="fw-bold">Aksesori Optik</h1>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body py-5 px-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">List Aksesori</h5>
                    <button type="button" class="btn btn-primary px-4 rounded-pill" data-bs-toggle="modal" data-bs-target="#addAccessoryModal">
                        Tambah Data
                    </button>
                </div>

                <hr class="mb-4 mt-0" style="border-top: 2px solid #dee2e6;">

                <div class="table-responsive mt-3">
                    <table class="table table-borderless align-middle">
                        <thead class="table-light">
                            <tr class="align-middle">
                                <th class="py-3 px-4 fw-bold">Nama</th>
                                <th class="py-3 px-4 fw-bold">Jenis</th>
                                <th class="py-3 px-4 fw-bold">Harga</th>
                                <th class="py-3 px-4 fw-bold">Stok</th>
                                <th class="py-3 px-4 fw-bold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="align-middle">
                                <td class="py-3 px-4">Lap Kacamata Microfiber</td>
                                <td class="py-3 px-4">Pembersih</td>
                                <td class="py-3 px-4">Rp 10.000</td>
                                <td class="py-3 px-4">50</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <tr class="align-middle">
                                <td class="py-3 px-4">Box Kacamata Hardcase</td>
                                <td class="py-3 px-4">Tempat Kacamata</td>
                                <td class="py-3 px-4">Rp 20.000</td>
                                <td class="py-3 px-4">30</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <tr class="align-middle">
                                <td class="py-3 px-4">Cairan Pembersih Lensa</td>
                                <td class="py-3 px-4">Pembersih</td>
                                <td class="py-3 px-4">Rp 15.000</td>
                                <td class="py-3 px-4">40</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <tr class="align-middle">
                                <td class="py-3 px-4">Karet Penyangga Telinga</td>
                                <td class="py-3 px-4">Aksesoris Tambahan</td>
                                <td class="py-3 px-4">Rp 5.000</td>
                                <td class="py-3 px-4">70</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <tr class="align-middle">
                                <td class="py-3 px-4">Strap Kacamata Anak</td>
                                <td class="py-3 px-4">Pengikat</td>
                                <td class="py-3 px-4">Rp 12.000</td>
                                <td class="py-3 px-4">25</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <tr class="align-middle">
                                <td class="py-3 px-4">Antifog Spray</td>
                                <td class="py-3 px-4">Pembersih</td>
                                <td class="py-3 px-4">Rp 25.000</td>
                                <td class="py-3 px-4">15</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <tr class="align-middle">
                                <td class="py-3 px-4">Obeng Kecil Set</td>
                                <td class="py-3 px-4">Peralatan</td>
                                <td class="py-3 px-4">Rp 8.000</td>
                                <td class="py-3 px-4">45</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <tr class="align-middle">
                                <td class="py-3 px-4">Spray Botol Kecil</td>
                                <td class="py-3 px-4">Botol</td>
                                <td class="py-3 px-4">Rp 3.000</td>
                                <td class="py-3 px-4">80</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <tr class="align-middle">
                                <td class="py-3 px-4">Tali Kacamata Dewasa</td>
                                <td class="py-3 px-4">Pengikat</td>
                                <td class="py-3 px-4">Rp 10.000</td>
                                <td class="py-3 px-4">35</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <tr class="align-middle">
                                <td class="py-3 px-4">Kain Pembersih Anti Jamur</td>
                                <td class="py-3 px-4">Pembersih</td>
                                <td class="py-3 px-4">Rp 18.000</td>
                                <td class="py-3 px-4">60</td>
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

    <!-- Modal Tambah Aksesori -->
    <div class="modal fade" id="addAccessoryModal" tabindex="-1" aria-labelledby="addAccessoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-3">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAccessoryModalLabel">Tambah Aksesori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="accessoryForm">
                        <div class="mb-3">
                            <label for="accessoryName" class="form-label">Nama Aksesori <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="accessoryName" required>
                        </div>
                        <div class="mb-3">
                            <label for="accessoryType" class="form-label">Jenis <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="accessoryType" required>
                        </div>
                        <div class="mb-3">
                            <label for="accessoryPrice" class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="accessoryPrice" required>
                        </div>
                        <div class="mb-3">
                            <label for="accessoryStock" class="form-label">Stok <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="accessoryStock" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" form="accessoryForm" class="btn btn-primary px-4">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</x-app>
