<x-app>
    @section('title', 'Lensa Khusus')

    <div class="container-fluid">
        <div class="page-header">
            <div class="page-title">
                <h1 class="fw-bold">Lensa Khsusus</h1>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body py-5 px-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">List Lensa Khsusus</h5>
                    <button type="button" class="btn btn-primary px-4 rounded-pill" data-bs-toggle="modal"
                        data-bs-target="#addFrameModal">
                        Tambah Data
                    </button>
                </div>

                <hr class="mb-4 mt-0" style="border-top: 2px solid #dee2e6;">

                <div class="table-responsive mt-3">
                    <table class="table table-borderless align-middle" id="lensaTable">
                        <thead class="table-light">
                            <tr class="align-middle">
                                <th class="py-3 px-4 fw-bold">MERK</th>
                                <th class="py-3 px-4 fw-bold">DESAIN</th>
                                <th class="py-3 px-4 fw-bold">TYPE</th>
                                <th class="py-3 px-4 fw-bold">SPH</th>
                                <th class="py-3 px-4 fw-bold">CYL</th>
                                <th class="py-3 px-4 fw-bold">ADD</th>
                                <th class="py-3 px-4 fw-bold">ESTIMASI SELESAI</th>
                                <th class="py-3 px-4 fw-bold">STATUS PESANAN</th>
                                <th class="py-3 px-4 fw-bold">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="py-3 px-4">Essilor</td>
                                <td class="py-3 px-4">Single Vision</td>
                                <td class="py-3 px-4">Bening</td>
                                <td class="py-3 px-4">-2.00</td>
                                <td class="py-3 px-4">-0.50</td>
                                <td class="py-3 px-4">+1.00</td>
                                <td class="py-3 px-4">5 Hari Kerja</td>
                                <td class="py-3 px-4">Menunggu Lensa</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4">Hoya</td>
                                <td class="py-3 px-4">Progressive</td>
                                <td class="py-3 px-4">Anti Radiasi</td>
                                <td class="py-3 px-4">-1.75</td>
                                <td class="py-3 px-4">-0.75</td>
                                <td class="py-3 px-4">+1.25</td>
                                <td class="py-3 px-4">7 Hari Kerja</td>
                                <td class="py-3 px-4">Dalam Proses</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4">Zeiss</td>
                                <td class="py-3 px-4">Single Vision</td>
                                <td class="py-3 px-4">Polikarbonat</td>
                                <td class="py-3 px-4">-3.00</td>
                                <td class="py-3 px-4">-1.00</td>
                                <td class="py-3 px-4">+2.00</td>
                                <td class="py-3 px-4">6 Hari Kerja</td>
                                <td class="py-3 px-4">Selesai</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4">Nikon</td>
                                <td class="py-3 px-4">Bifocal</td>
                                <td class="py-3 px-4">Anti Refleksi</td>
                                <td class="py-3 px-4">-1.25</td>
                                <td class="py-3 px-4">-0.25</td>
                                <td class="py-3 px-4">+1.50</td>
                                <td class="py-3 px-4">4 Hari Kerja</td>
                                <td class="py-3 px-4">Dalam Proses</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4">Seiko</td>
                                <td class="py-3 px-4">Progressive</td>
                                <td class="py-3 px-4">Blue Light</td>
                                <td class="py-3 px-4">-2.50</td>
                                <td class="py-3 px-4">-0.75</td>
                                <td class="py-3 px-4">+1.75</td>
                                <td class="py-3 px-4">8 Hari Kerja</td>
                                <td class="py-3 px-4">Menunggu Lensa</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4">Carl Zeiss</td>
                                <td class="py-3 px-4">Single Vision</td>
                                <td class="py-3 px-4">Photochromic</td>
                                <td class="py-3 px-4">-3.25</td>
                                <td class="py-3 px-4">-1.25</td>
                                <td class="py-3 px-4">+2.25</td>
                                <td class="py-3 px-4">7 Hari Kerja</td>
                                <td class="py-3 px-4">Selesai</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4">Transitions</td>
                                <td class="py-3 px-4">Progressive</td>
                                <td class="py-3 px-4">Anti Refleksi</td>
                                <td class="py-3 px-4">-2.00</td>
                                <td class="py-3 px-4">-0.50</td>
                                <td class="py-3 px-4">+1.00</td>
                                <td class="py-3 px-4">6 Hari Kerja</td>
                                <td class="py-3 px-4">Dalam Proses</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4">Essilor</td>
                                <td class="py-3 px-4">Bifocal</td>
                                <td class="py-3 px-4">Polikarbonat</td>
                                <td class="py-3 px-4">-1.00</td>
                                <td class="py-3 px-4">-0.25</td>
                                <td class="py-3 px-4">+1.25</td>
                                <td class="py-3 px-4">5 Hari Kerja</td>
                                <td class="py-3 px-4">Selesai</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4">Hoya</td>
                                <td class="py-3 px-4">Single Vision</td>
                                <td class="py-3 px-4">Anti Radiasi</td>
                                <td class="py-3 px-4">-2.25</td>
                                <td class="py-3 px-4">-0.50</td>
                                <td class="py-3 px-4">+1.75</td>
                                <td class="py-3 px-4">7 Hari Kerja</td>
                                <td class="py-3 px-4">Menunggu Lensa</td>
                                <td class="py-3 px-4">
                                    <a href="#" class="me-3 text-primary"><i class="bi bi-pencil-fill"></i></a>
                                    <a href="#" class="text-danger"><i class="bi bi-trash-fill"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4">Zeiss</td>
                                <td class="py-3 px-4">Progressive</td>
                                <td class="py-3 px-4">Blue Light</td>
                                <td class="py-3 px-4">-3.50</td>
                                <td class="py-3 px-4">-1.00</td>
                                <td class="py-3 px-4">+2.50</td>
                                <td class="py-3 px-4">6 Hari Kerja</td>
                                <td class="py-3 px-4">Dalam Proses</td>
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

    <!-- Modal Tambah Data -->
<div class="modal fade" id="addFrameModal" tabindex="-1" aria-labelledby="addFrameModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form>
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addFrameModalLabel">Tambah Data Lensa Finish</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="merk" class="form-label">Merk</label>
              <input type="text" class="form-control" id="merk" name="merk" required>
            </div>
            <div class="col-md-6">
              <label for="desain" class="form-label">Desain</label>
              <input type="text" class="form-control" id="desain" name="desain" required>
            </div>
            <div class="col-md-6">
              <label for="type" class="form-label">Type</label>
              <input type="text" class="form-control" id="type" name="type" required>
            </div>
            <div class="col-md-6">
              <label for="sph" class="form-label">SPH</label>
              <input type="number" step="0.25" class="form-control" id="sph" name="sph" required>
            </div>
            <div class="col-md-6">
              <label for="cyl" class="form-label">CYL</label>
              <input type="number" step="0.25" class="form-control" id="cyl" name="cyl" required>
            </div>
            <div class="col-md-6">
              <label for="add" class="form-label">ADD</label>
              <input type="number" step="0.25" class="form-control" id="add" name="add" required>
            </div>
            <div class="col-md-6">
              <label for="estimasi" class="form-label">Estimasi Selesai</label>
              <input type="text" class="form-control" id="estimasi" name="estimasi" placeholder="Contoh: 5 Hari Kerja" required>
            </div>
            <div class="col-md-6">
              <label for="status" class="form-label">Status Pesanan</label>
              <select class="form-select" id="status" name="status" required>
                <option value="" disabled selected>Pilih Status</option>
                <option value="Menunggu Lensa">Menunggu Lensa</option>
                <option value="Dalam Proses">Dalam Proses</option>
                <option value="Selesai">Selesai</option>
              </select>
            </div>

          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>

</x-app>
