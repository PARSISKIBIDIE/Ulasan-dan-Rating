@extends('layouts.app')

@section('content')

<div class="main-content">

    <div class="container">

        <h2 class="fw-bold mt-4 mb-5 text-indigo">Dashboard Guru</h2>

        <div class="row g-4 justify-content-center">

            <div class="col-md-6">
                <div class="card border-0 shadow-lg rounded-4 h-100">
                    <div class="card-body text-center p-3 p-md-5">
                        <h5 class="fw-bold mb-3">Lihat Hasil Sistem Ulasan dan Rating</h5>
                        <p class="text-muted">
                            Lihat detail hasil penilaian dari siswa.
                        </p>
                        <a href="/guru/survey" class="btn btn-indigo px-4 rounded-3">Lihat</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 shadow-lg rounded-4 h-100">
                    <div class="card-body text-center p-3 p-md-5">
                        <h5 class="fw-bold mb-3">Rata-rata Rating</h5>
                        <p class="text-muted">
                            Lihat nilai rata-rata performa mengajar.
                        </p>
                        <a href="/guru/rating"
                           class="btn btn-outline-warning px-4 rounded-3 fw-semibold">
                            Lihat
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection