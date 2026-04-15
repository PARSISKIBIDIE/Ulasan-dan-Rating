@extends('layouts.app')

@section('content')

<div class="main-content py-5">

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-indigo">Data Jadwal Mengajar</h3>
        </div>

        <!-- Grid Kelas -->
        <div class="row g-3">
            @foreach($daftar_kelas as $kelas)
                <div class="col-md-6 col-lg-4">
                    <a href="/admin/jadwal/show/{{ urlencode($kelas) }}" style="text-decoration: none; cursor: pointer;">
                            <div class="card border-0 shadow-lg rounded-4 cursor-pointer"
                                style="transition: all 0.3s ease; min-height: 250px;"
                                onmouseover="this.style.transform='translateY(-5px)';this.style.boxShadow='0 10px 30px rgba(79,70,229,0.12)'"
                                onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 15px 35px rgba(0, 0, 0, 0.1)'">
                            
                            <div class="card-body p-4 text-center">
                                <div style="font-size: 2.5rem; color: var(--color-indigo); margin-bottom: 15px;">
                                    📚
                                </div>
                                <h5 class="fw-bold text-indigo" style="font-size: 1.2rem;">{{ $kelas }}</h5>
                                <p class="text-muted mb-0">
                                    {{ count($jadwal_per_kelas[$kelas]) }} Jadwal
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

    </div>

</div>

<style>
    .cursor-pointer {
        cursor: pointer;
    }
</style>

@endsection