<div class="col-lg-7 col-md-12">
    <div class="card shadow-sm h-100">
        <div class="card-header fw-semibold d-flex justify-content-between align-items-center bg-white border-bottom">
            <div>
                <i class="bi bi-search me-2"></i>Choose Customer
            </div>
            <button type="button" class="btn btn-primary px-4 rounded-pill" id="btnAddCustomer">
                Tambah Data
            </button>
        </div>

        <div class="card-body p-3 position-relative">
            <div class="input-group mb-3">

                <input type="text" class="form-control" placeholder="Search by name, Email, or phone..."
                    wire:model.defer="search" autocomplete="off">
                <button class="btn btn-primary" wire:click="runSearch">
                    <i class="bi bi-search"></i>
                </button>
                <button class="btn btn-outline-primary" wire:click="resetSearch">
                    <i class="bi bi-x-circle"></i>
                </button>
            </div>



            <div wire:loading.flex wire:target="selectedCustomer"
                style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10;"
                class="align-items-center justify-content-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <div wire:loading.class="opacity-50" wire:target="selectedCustomer" class="table-responsive card-scroll">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $customer)
                            <tr>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->phone }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>
                                    <button wire:click="selectCustomer({{ $customer->id }})" class="btn"
                                        wire:loading.attr="disabled">
                                        +
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada pelanggan ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3 d-flex justify-content-end">
                    {{ $customers->links() }}
                </div>


            </div>
        </div>
    </div>
</div>
