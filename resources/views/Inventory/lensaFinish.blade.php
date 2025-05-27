<x-app>
    @section('title', 'ensa Finish')

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
                                    <a href="#" class="me-3 text-primary"><i class="bi bi-pencil-fill"></i></a>
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
                                    <a href="#" class="me-3 text-primary"><i class="bi bi-pencil-fill"></i></a>
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
                                    <a href="#" class="me-3 text-primary"><i class="bi bi-pencil-fill"></i></a>
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


</x-app>
