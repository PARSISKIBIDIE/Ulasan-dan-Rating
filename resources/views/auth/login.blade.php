@extends('layouts.app')

@section('content')

<div class="d-flex align-items-center justify-content-center"
     style="
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background: linear-gradient(135deg, #ff9a3c, #ff7a00);
     ">

    <div style="width:100%; max-width:450px;">
        <div class="card shadow-lg border-0 rounded-4 p-4">

            <h3 class="text-center mb-4 fw-bold" style="color:#ff7a00;">
                Login
            </h3>

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
                    <button type="submit"
                            class="btn text-white fw-semibold rounded-3"
                            style="background-color:#ff7a00;">
                        Login
                    </button>
                </div>

            </form>

            <p class="text-center mt-3">
                Belum punya akun?
                <a href="/register"
                   class="fw-semibold text-decoration-none"
                   style="color:#ff7a00;">
                   Register
                </a>
            </p>

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