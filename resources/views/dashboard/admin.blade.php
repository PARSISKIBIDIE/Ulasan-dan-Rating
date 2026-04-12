@extends('layouts.app')

@section('content')

<div class="main-content" style="background: #fff3e6;">

    <div class="container">

        <h2 class="fw-bold mb-5" style="color:#ff7a00;">
            Dashboard Admin
        </h2>

        <div class="row g-4 justify-content-center">

            <div class="col-md-4">
                <div class="card border-0 shadow-lg rounded-4 h-100">
                    <div class="card-body text-center p-3 p-md-5">
                        <h5 class="fw-bold mb-3">Kelola Guru</h5>
                        <p class="text-muted">Tambah, edit, dan hapus data guru.</p>
                        <a href="/admin/guru"
                           class="btn text-white px-4 rounded-3"
                           style="background-color:#ff7a00;">
                            Masuk
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-lg rounded-4 h-100">
                    <div class="card-body text-center p-3 p-md-5">
                        <h5 class="fw-bold mb-3">Kelola Jadwal</h5>
                        <p class="text-muted">Atur jadwal mengajar guru untuk setiap kelas.</p>
                        <a href="/admin/jadwal"
                           class="btn text-white px-4 rounded-3"
                           style="background-color:#ff7a00;">
                            Masuk
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-lg rounded-4 h-100">
                    <div class="card-body text-center p-3 p-md-5">
                        <h5 class="fw-bold mb-3">Hasil Sistem Ulasan dan Rating</h5>
                        <p class="text-muted">Lihat hasil dan laporan sistem ulasan dan rating.</p>
                        <a href="/admin/hasil-survey"
                           class="btn text-white px-4 rounded-3"
                           style="background-color:#ff7a00;">
                            Masuk
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-4 text-center">
                        <h5 class="fw-bold mb-3">Reset Sistem Ulasan dan Rating</h5>
                        <p class="text-muted">Klik tombol ini untuk menghapus semua data sistem ulasan dan rating sehingga murid dapat mengisi ulang.</p>

                        <form method="POST" action="/admin/reset-surveys" onsubmit="return confirm('Yakin ingin mereset semua sistem ulasan dan rating? Tindakan ini tidak dapat dikembalikan.');" style="display:inline-block;">
                            @csrf
                            <button class="btn btn-danger px-4">Reset Semua Sistem Ulasan dan Rating</button>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Reset Berdasarkan Kelas / Hari</h5>
                        <p class="text-muted">Pilih kelas dan/atau hari untuk mereset sistem ulasan dan rating hanya pada filter tersebut. Biarkan kosong untuk tidak memfilter.</p>

                        <form method="POST" action="/admin/reset-surveys" onsubmit="return confirm('Yakin ingin mereset sistem ulasan dan rating sesuai filter ini?');">
                            @csrf
                            <div class="row g-2 align-items-center">
                                <div class="col-md-4">
                                    <label class="form-label">Kelas (opsional)</label>
                                    @php
                                        $classes = [
                                            'X RPL 1', 'X RPL 2', 'X MPLB', 'X AK', 'X TKJ',
                                            'XI RPL 1', 'XI RPL 2', 'XI AK', 'XI TKJ', 'XI MPLB',
                                            'XII RPL 1', 'XII RPL 2', 'XII MPLB', 'XII AK', 'XII TKJ'
                                        ];
                                    @endphp
                                    <select name="kelas" class="form-select">
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach($classes as $c)
                                            <option value="{{ $c }}">{{ $c }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Hari (opsional)</label>
                                    <select name="hari" class="form-select">
                                        <option value="">-- Pilih Hari --</option>
                                        @foreach(['Senin','Selasa','Rabu','Kamis','Jumat'] as $d)
                                            <option value="{{ $d }}">{{ $d }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4 mt-4 mt-md-0 text-md-end">
                                    <button class="btn btn-warning px-4">Reset Berdasarkan Filter</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Keluarkan Jadwal Per Hari</h5>
                        <p class="text-muted">Klik tombol untuk menampilkan atau menyembunyikan jadwal pada hari tertentu di dashboard murid.</p>

                        <div class="d-flex flex-wrap gap-2">
                            @php
                                // Only show weekdays here; Sabtu and Minggu removed per admin preference
                                $days = ['Senin','Selasa','Rabu','Kamis','Jumat'];
                            @endphp

                            @foreach($days as $d)
                                @php
                                    $released = false;
                                    if (isset($dayReleases) && $dayReleases && count($dayReleases) > 0) {
                                        $found = $dayReleases->where('day', $d)->first();
                                        if ($found) {
                                            $released = (bool) $found->released;
                                        }
                                    }
                                @endphp

                                <form method="POST" action="/admin/day-release/toggle/{{ strtolower($d) }}" style="display:inline-block;">
                                    @csrf
                                    <button class="btn px-3 py-2 {{ $released ? 'btn-danger' : 'btn-primary' }}">
                                        {{ $d }} — {{ $released ? 'Sembunyikan' : 'Keluarkan' }}
                                    </button>
                                </form>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4 justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-lg rounded-4">
                    <div class="card-body p-4">
                        @php
                            $lastReset = null;
                            $file = storage_path('app/last_survey_reset.json');
                            if (file_exists($file)) {
                                try {
                                    $lastReset = json_decode(file_get_contents($file), true);
                                } catch (\Exception $e) {
                                    $lastReset = null;
                                }
                            }
                        @endphp

                        <h5 class="fw-bold mb-2">Reset Otomatis</h5>
                        <p class="small text-muted mb-2">Dijadwalkan: <strong>Setiap Sabtu, 00:00</strong></p>

                        @if($lastReset)
                            <p class="mb-1"><strong>Terakhir dijalankan:</strong><br>{{ $lastReset['timestamp'] }}</p>
                            <p class="mb-1 text-muted">Mereset {{ $lastReset['count'] }} survey.</p>
                        @else
                            <p class="mb-1 text-muted">Reset otomatis belum pernah dijalankan.</p>
                        @endif

                        <hr>
                        <p class="small mb-1"><strong>Cara menjalankan scheduler:</strong></p>
                        <p class="small text-muted mb-1">Laravel scheduler harus dijalankan oleh OS scheduler tiap menit. Contoh:</p>
                        <pre class="small bg-light p-2" style="border-radius:6px; overflow-x: auto;">* * * * * php /path/to/your/project/artisan schedule:run >> /dev/null 2>&1</pre>
                        <p class="small text-muted mb-0">Atau (Windows Task Scheduler):</p>
                        <pre class="small bg-light p-2 mt-1" style="border-radius:6px; overflow-x: auto;">php C:\\xampp\\htdocs\\survey-sekolah\\artisan schedule:run >> NUL 2>&1</pre>
                        <p class="small text-muted mt-2 mb-0">Set trigger berulang setiap 1 menit agar Laravel mengeksekusi job yang dijadwalkan.</p>

                        <form method="POST" action="/admin/reset-surveys/now" onsubmit="return confirm('Yakin ingin menjalankan reset sekarang? Tindakan ini akan menghapus semua survey.');" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">Jalankan sekarang</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection