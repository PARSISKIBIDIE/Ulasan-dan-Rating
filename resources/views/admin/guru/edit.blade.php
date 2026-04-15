@extends('layouts.app')

@section('content')

<div class="main-content">

    <div class="container">

        <h2 class="fw-bold mb-4 text-indigo">Edit Guru</h2>

        <div class="card border-0 shadow-lg rounded-4">
            <div class="card-body p-3 p-md-5">

                <form action="/admin/guru/update/{{ $guru->id }}" method="POST" enctype="multipart/form-data">
                @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama</label>
                        <input type="text" name="name"
                               value="{{ $guru->user->name }}"
                               class="form-control rounded-3">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">NIP</label>
                        <input type="text" name="nip"
                               value="{{ $guru->nip }}"
                               class="form-control rounded-3">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mata Pelajaran</label>
                        <input type="text" name="mapel"
                               value="{{ $guru->mapel }}"
                               class="form-control rounded-3">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Foto Profil</label>
                        @if($guru->foto)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $guru->foto) }}" 
                                     alt="{{ $guru->user->name }}" 
                                     style="max-width: 150px; border-radius: 10px;">
                            </div>
                        @endif
                        <input type="file" name="foto" accept="image/*"
                               class="form-control rounded-3">
                        <small class="text-muted">Format: JPG, PNG (Max: 2MB)</small>
                    </div>

                    <div class="d-flex justify-content-between">

                        <a href="/admin/guru"
                           class="btn btn-outline-secondary rounded-3 px-4">
                            Kembali
                        </a>

                        <button class="btn btn-indigo px-4 rounded-3">Update</button>

                    </div>

                </form>

            </div>
        </div>

    </div>

</div>

@endsection