@extends('layouts.app')

@section('content')

<div class="bg-light min-vh-100 d-flex align-items-center py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-8 col-lg-6 mx-auto">
                <div class="card shadow-sm card-rounded bg-white border-0 overflow-hidden">
                    <div class="card-header bg-indigo text-center py-3 border-0">
                        <h3 class="mb-0 fw-bold">Login</h3>
                    </div>
                    <div class="card-body p-4">

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form action="/login" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama</label>
                                <input type="text" name="name"
                                       class="form-control rounded-3"
                                       placeholder="Masukkan Nama">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Password</label>
                                <div class="d-flex align-items-center">
                                    <input type="password" name="password"
                                           id="login-password"
                                           class="form-control rounded-3"
                                           placeholder="Masukkan Password">
                                    <i class="bi bi-eye ms-2" id="login-eye" style="cursor:pointer;" onclick="togglePassword('login-password','login-eye')"></i>
                                </div>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember">
                                    Remember me
                                </label>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-indigo fw-semibold rounded-3">Login</button>
                            </div>

                        </form>

                        <p class="text-center mt-3 mb-0">
                            Belum punya akun?
                            <a href="/register" class="fw-semibold text-decoration-none text-indigo">Register</a>
                        </p>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- toggle password script reused -->
<script>
function togglePassword(fieldId, eyeId) {
    const field = document.getElementById(fieldId);
    const eye = document.getElementById(eyeId);

    if (field.type === "password") {
        field.type = "text";
        eye.classList.remove("bi-eye");
        eye.classList.add("bi-eye-slash");
    } else {
        field.type = "password";
        eye.classList.remove("bi-eye-slash");
        eye.classList.add("bi-eye");
    }
}
</script>

@endsection