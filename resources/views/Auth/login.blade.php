<x-app :struktur="false">
    @section('title', 'Login')
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            height: 100vh;
        }
        .forgot-link {
            font-size: 0.9rem;
        }
    </style>
    <div class="d-flex align-items-center justify-content-center h-100">
        <div class="card shadow-sm border-0 p-4" style="width: 100%; max-width: 400px;">
            <h3 class="text-center fw-bold mb-2">Login</h3>
            <p class="text-center text-muted mb-4">Please login to your account</p>
            <form method="POST" action="{{ route('login.process') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-envelope"></i></span>
                        <input type="email" id="email" name="email"
                            class="form-control" value="{{ old('email') }}"
                            autofocus required>
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-flex align-items-center mt-1" style="display: block;">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
                        <input type="password" id="password" name="password"
                            class="form-control" required>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-flex align-items-center mt-1" style="display: block;">
                            <i class="bi bi-exclamation-circle-fill me-2"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>


                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-dark">Login</button>
                </div>

            </form>
        </div>
    </div>
</x-app>
