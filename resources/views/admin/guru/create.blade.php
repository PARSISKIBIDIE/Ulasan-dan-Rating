@extends('layouts.app')

@section('content')

<div class="main-content" style="background: #fff3e6;">

    <div class="container">

        <h2 class="fw-bold mb-4" style="color:#ff7a00;">
            Tambah Guru
        </h2>

        <div class="card border-0 shadow-lg rounded-4">
            <div class="card-body p-3 p-md-5">

                <form action="/admin/guru/store" method="POST" enctype="multipart/form-data">
                @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama</label>
                           <input type="text" name="name"
                               class="form-control rounded-3"
                               placeholder="Masukkan Nama"
                               value="{{ old('name', request('name')) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                           <input type="password" name="password"
                               class="form-control rounded-3"
                               placeholder="Masukkan Password"
                               value="{{ old('password', request('password', '12345678')) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">NIP</label>
                           <input type="text" name="nip"
                               class="form-control rounded-3"
                               placeholder="Masukkan NIP"
                               value="{{ old('nip', request('nip')) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mata Pelajaran</label>
                           <input type="text" name="mapel"
                               class="form-control rounded-3"
                               placeholder="Masukkan Mata Pelajaran"
                               value="{{ old('mapel', request('mapel')) }}">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Foto Profil</label>
                        <input type="file" name="foto" accept="image/*"
                               class="form-control rounded-3">
                        <small class="text-muted">Format: JPG, PNG (Max: 2MB)</small>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button class="btn text-white px-4 rounded-3"
                                style="background-color:#ff7a00;">
                            Simpan
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>

</div>

@endsection