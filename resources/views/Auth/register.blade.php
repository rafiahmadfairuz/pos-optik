<x-app :struktur="false">
    @section('title', 'Register')

    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa, #dee2e6);
            height: 100vh;
        }
    </style>

    <div class="d-flex align-items-center justify-content-center h-100">
        <div class="card shadow-sm border-0 p-4" style="width: 100%; max-width: 450px;">
            <h3 class="text-center fw-bold mb-4">Register</h3>
            <form method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-person"></i></span>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">No Telp</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-telephone"></i></span>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-dark">Register</button>
                </div>

                <div class="text-center mt-3">
                    <small>Sudah punya akun? <a href="{{url("login")}}">Login di sini</a></small>
                </div>
            </form>
        </div>
    </div>

</x-app>
