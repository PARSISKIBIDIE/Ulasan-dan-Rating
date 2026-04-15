@extends('layouts.app')

@section('content')



<div class="bg-light min-vh-100 d-flex align-items-center py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-8 col-lg-6 mx-auto">
                <div class="card shadow-sm card-rounded bg-white border-0 overflow-hidden">
                    <div class="card-header bg-indigo text-center py-3 border-0">
                        <h3 class="mb-0 fw-bold">Register Akun</h3>
                    </div>
                    <div class="card-body p-4">

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="/register" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama</label>
                                <input type="text" name="name"
                                       class="form-control rounded-3"
                                       placeholder="Masukkan Nama"
                                       value="{{ old('name') }}" required>
                            </div>

                            <!-- PASSWORD -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Password</label>

                                <div class="d-flex align-items-center">
                                    <input type="password" name="password"
                                           id="password"
                                           class="form-control rounded-3"
                                           placeholder="Masukkan Password" required>
                                    <i class="bi bi-eye ms-2" id="eye1" style="cursor:pointer;" onclick="togglePassword('password','eye1')"></i>
                                </div>
                                <small id="passwordHelp" class="text-muted">Minimal 6 karakter</small>
                            </div>

                            <!-- KONFIRMASI PASSWORD -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Konfirmasi Password</label>

                                <div class="d-flex align-items-center">
                                    <input type="password" name="password_confirmation"
                                           id="password_confirmation"
                                           class="form-control rounded-3"
                                           placeholder="Ulangi Password" required>
                                    <i class="bi bi-eye ms-2" id="eye2" style="cursor:pointer;" onclick="togglePassword('password_confirmation','eye2')"></i>
                                </div>
                                <small id="matchHelp" class="text-muted"></small>
                            </div>

                            <!-- ROLE -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Role</label>
                                <select name="role" id="role"
                                        class="form-select rounded-3" required>
                                    <option value="">-- Pilih Role --</option>
                                    <option value="murid">Murid</option>
                                </select>
                            </div>

                            <!-- KELAS -->
                            <div class="mb-3" id="kelasField" style="display:none;">
                                <label class="form-label fw-semibold">Kelas</label>
                                <select name="kelas"
                                        class="form-select rounded-3">
                                    <option value="">-- Pilih Kelas --</option>
                                    <option value="X RPL 1">X RPL 1</option>
                                    <option value="X RPL 2">X RPL 2</option>
                                    <option value="X MPLB">X MPLB</option>
                                    <option value="X AK">X AK</option>
                                    <option value="X TKJ">X TKJ</option>
                                    <option value="X RPL 1">XI RPL 1</option>
                                    <option value="XI RPL 2">XI RPL 2</option>
                                    <option value="XI AK">XI AK</option>
                                    <option value="XI TKJ">XI TKJ</option>
                                    <option value="XI RPL 2">XI MPLB</option>
                                    <option value="XII RPL 1">XII RPL 1</option>
                                    <option value="XII RPL 2">XII RPL 2</option>
                                    <option value="XII MPLB">XII MPLB</option>
                                    <option value="XII AK">XII AK</option>
                                    <option value="XII TKJ">XII TKJ</option>
                                </select>
                            </div>

                            <div class="d-grid">
                                <button type="submit"
                                        class="btn btn-indigo fw-semibold rounded-3">
                                    Register
                                </button>
                            </div>

                        </form>

                        <p class="text-center mt-3 mb-0">
                            Sudah punya akun?
                            <a href="/login"
                               class="fw-semibold text-decoration-none"
                               style="color:#4F46E5;">
                               Login
                            </a>
                        </p>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPT ROLE -->
<script>
document.getElementById('role').addEventListener('change', function() {
    let kelasField = document.getElementById('kelasField');

    if (this.value === 'murid') {
        kelasField.style.display = 'block';
    } else {
        kelasField.style.display = 'none';
    }
});
</script>

<!-- SCRIPT SHOW PASSWORD -->
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

// password strength / match feedback
const pwd = document.getElementById('password');
const confirm = document.getElementById('password_confirmation');
const matchHelp = document.getElementById('matchHelp');
const pwdHelp = document.getElementById('passwordHelp');

function checkPasswords() {
    if (pwd.value.length > 0) {
        pwdHelp.textContent = pwd.value.length < 6 ? 'Minimal 6 karakter' : '';
    } else {
        pwdHelp.textContent = 'Minimal 6 karakter';
    }

    if (confirm.value.length > 0) {
        if (pwd.value === confirm.value) {
            matchHelp.textContent = 'Password cocok';
            matchHelp.style.color = 'green';
        } else {
            matchHelp.textContent = 'Password tidak sama';
            matchHelp.style.color = 'red';
        }
    } else {
        matchHelp.textContent = '';
    }
}

pwd.addEventListener('input', checkPasswords);
confirm.addEventListener('input', checkPasswords);
</script>

@endsection