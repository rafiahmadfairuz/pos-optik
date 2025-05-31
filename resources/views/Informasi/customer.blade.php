<x-app>
    @section('title', 'Customer')
    <div class="page-header">
        <div class="page-title">
            <h4>Customer List</h4>
            <h6>Manage your Customers</h6>
        </div>
        <div class="page-btn">
            <p   data-bs-toggle="modal" data-bs-target="#editCustomerModal" class="btn btn-added"><img src="assets/img/icons/plus.svg" alt="img"> Add Customer</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table ">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach([
                            ['Thomas', 'thomas@example.com', '+12163547758'],
                            ['Benjamin', 'benjamin@example.com', '123-456-888'],
                            ['James', 'james@example.com', '123-456-888'],
                            ['Bruklin', 'bruklin@example.com', '123-456-888'],
                            ['Beverly', 'beverly@example.com', '+12163547758'],
                            ['B. Huber', 'huber@example.com', '123-456-888'],
                            ['James Stawberry', 'stawberry@example.com', '+12163547758'],
                            ['Fred John', 'fredjohn@example.com', '123-456-888'],
                        ] as [$name, $email, $phone])
                        <tr>
                            <td>{{ $name }}</td>
                            <td>{{ $email }}</td>
                            <td>{{ $phone }}</td>
                            <td>
                              <a class="me-2" href="{{ route('customer.detail') }}">
    <i class="bi bi-person fs-4"></i>
</a>

                                <a class="me-3" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editCustomerModal">
                                    <img src="assets/img/icons/edit.svg" alt="edit">
                                </a>
                                <a class="me-3 confirm-text" href="javascript:void(0);">
                                    <img src="assets/img/icons/delete.svg" alt="delete">
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

 <!-- Modal Edit Customer -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content rounded-3">
      <div class="modal-header">
        <h5 class="modal-title" id="editCustomerModalLabel">Update Customer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="customerForm">
          <div class="row g-3">
            <div class="col-md-4">
              <label for="customerNik" class="form-label">Nik</label>
              <input type="text" class="form-control" id="customerNik" />
            </div>
            <div class="col-md-4">
              <label for="customerName" class="form-label">Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="customerName" required />
            </div>
            <div class="col-md-4">
              <label for="customerPhone" class="form-label">Phone</label>
              <input type="text" class="form-control" id="customerPhone" />
            </div>

            <div class="col-md-6">
              <label for="customerEmail" class="form-label">Email</label>
              <input type="email" class="form-control" id="customerEmail" />
            </div>
            <div class="col-md-3">
              <label for="customerGender" class="form-label">Gender</label>
              <select class="form-select" id="customerGender">
                <option selected disabled>Select Gender</option>
                <option>Male</option>
                <option>Female</option>
              </select>
            </div>
            <div class="col-md-3">
              <label for="customerDob" class="form-label">Date of Birth</label>
              <input type="date" class="form-control" id="customerDob" />
            </div>

            <div class="col-12">
              <label for="customerAddress" class="form-label">Address</label>
              <input type="text" class="form-control" id="customerAddress" />
            </div>

            <div class="col-12">
              <label for="customerNotes" class="form-label">Notes</label>
              <textarea class="form-control" id="customerNotes" rows="3"></textarea>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
        <button type="submit" form="customerForm" class="btn btn-primary px-4">Save</button>
      </div>
    </div>
  </div>
</div>

</x-app>
