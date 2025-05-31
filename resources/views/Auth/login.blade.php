<x-app :struktur="false">
    @section('title', 'Login')

    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            height: 100vh;
        }
    </style>
    </head>

    <body>
        <div class="d-flex align-items-center justify-content-center h-100">
            <div class="card shadow-sm border-0 p-4" style="width: 100%; max-width: 400px;">
                <h3 class="text-center fw-bold mb-4">Login</h3>
                <form method="POST" >
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control" required autofocus>
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
                        <a href="{{url("admin")}}" type="submit" class="btn btn-dark">Login</a>
                    </div>

                    <div class="text-center mt-3">
                        <small>Belum punya akun? <a href="{{url("register")}}">Daftar di sini</a></small>
                    </div>
                </form>
            </div>
        </div>


</x-app>
