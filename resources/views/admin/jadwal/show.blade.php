@extends('layouts.app')

@section('content')

<div style="
    min-height: calc(100vh - 70px);
    background: #fff3e6;
    padding: 40px;
">

    <div class="container-fluid">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold" style="color:#ff7a00;">
                📚 Jadwal Kelas: <span style="font-size: 1.5rem;">{{ $kelas }}</span>
            </h3>
            <a href="/admin/jadwal" class="btn btn-outline-secondary">
                ← Kembali
            </a>
        </div>

        <!-- Form Tambah Jadwal -->
        <div class="card border-0 shadow-lg rounded-4 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4" style="color:#ff7a00;">
                    ➕ Tambah Jadwal Baru
                </h5>

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Ada kesalahan:</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form id="jadwalForm" action="/admin/jadwal/store" method="POST">
                    @csrf
                    <input type="hidden" name="kelas" value="{{ $kelas }}">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Guru</label>
                            <select name="guru_id" class="form-select" required>
                                <option selected disabled>Pilih Guru</option>
                                @foreach($guru as $g)
                                <option value="{{ $g->id }}" data-mapel="{{ $g->mapel }}">{{ $g->user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Mapel</label>
                            <input type="text" name="mapel" class="form-control" placeholder="Contoh: Matematika" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Hari</label>
                            <select name="hari" class="form-select" required>
                                <option selected disabled>Pilih Hari</option>
                                <option>Senin</option>
                                <option>Selasa</option>
                                <option>Rabu</option>
                                <option>Kamis</option>
                                <option>Jumat</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Jam</label>
                            <input list="jamOptionsList" name="jam" class="form-control" placeholder="08:00 - 09:30" required value="{{ old('jam') }}">
                            <datalist id="jamOptionsList">
                                @if(isset($jamOptions) && $jamOptions->count())
                                    @foreach($jamOptions as $jam)
                                        <option value="{{ $jam }}"></option>
                                    @endforeach
                                @endif
                            </datalist>
                        </div>

                        <div class="col-12">
                            <button type="submit" id="submitBtn" class="btn text-white fw-semibold px-4" style="background:#ff7a00;">
                                Simpan Jadwal
                            </button>
                            <button type="button" id="cancelEditBtn" class="btn btn-outline-secondary ms-2" style="display:none;">
                                Batal Edit
                            </button>
                        </div>
                    </div>
                </form>
                <script>
                document.addEventListener('DOMContentLoaded', function(){
                    var guruSelect = document.querySelector('select[name="guru_id"]');
                    var mapelInput = document.querySelector('input[name="mapel"]');
                    if (!guruSelect || !mapelInput) return;

                    guruSelect.addEventListener('change', function(){
                        var opt = this.options[this.selectedIndex];
                        var mapel = opt ? opt.getAttribute('data-mapel') : '';
                        mapelInput.value = mapel || '';
                    });

                    // if there's a preselected option (unlikely), fill
                    var initialOpt = guruSelect.options[guruSelect.selectedIndex];
                    if (initialOpt && initialOpt.getAttribute('data-mapel')) {
                        mapelInput.value = initialOpt.getAttribute('data-mapel') || '';
                    }
                });
                </script>
            </div>
        </div>

        <!-- Tabel Jadwal -->
        <div class="card border-0 shadow-lg rounded-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4" style="color:#ff7a00;">
                    📋 Daftar Jadwal Kelas {{ $kelas }}
                </h5>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(count($jadwal) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead style="color:#ff7a00; background-color: #fff3e6;">
                                <tr>
                                    <th>No</th>
                                    <th>Guru</th>
                                    <th>Mapel</th>
                                    <th>Hari</th>
                                    <th>Jam</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jadwal as $index => $j)
                                <tr>
                                    <td class="fw-semibold">{{ $index + 1 }}</td>
                                    <td class="fw-semibold">{{ $j->guru->user->name }}</td>
                                    <td>{{ $j->mapel }}</td>
                                    <td>{{ $j->hari }}</td>
                                    <td>{{ $j->jam }}</td>
                                    <td class="text-center">
                                        <button type="button"
                                            class="btn btn-sm text-white me-1 btn-edit"
                                            style="background:#ff7a00;"
                                            data-id="{{ $j->id }}"
                                            data-guru-id="{{ $j->guru->id }}"
                                            data-mapel="{{ $j->mapel }}"
                                            data-hari="{{ $j->hari }}"
                                            data-jam="{{ $j->jam }}">
                                            Edit
                                        </button>
                                        <a href="/admin/jadwal/delete/{{$j->id}}"
                                           class="btn btn-sm text-white"
                                           style="background:#dc3545;"
                                           onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');">
                                            Hapus
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">
                        <strong>Belum ada jadwal untuk kelas {{ $kelas }}</strong>. Tambahkan jadwal baru menggunakan form di atas.
                    </div>
                @endif
            </div>
        </div>

    </div>

</div>

    <script>
    document.addEventListener('DOMContentLoaded', function(){
        var form = document.getElementById('jadwalForm');
        var submitBtn = document.getElementById('submitBtn');
        var cancelBtn = document.getElementById('cancelEditBtn');
        var originalAction = form.action;

        function enterEditMode(data) {
            // set form action to update
            form.action = '/admin/jadwal/update/' + data.id;
            submitBtn.textContent = 'Update Jadwal';
            cancelBtn.style.display = '';

            // set guru select
            var guruSelect = form.querySelector('select[name="guru_id"]');
            if (guruSelect) {
                guruSelect.value = data.guruId;
                // trigger change to auto-fill mapel if available
                var evt = new Event('change');
                guruSelect.dispatchEvent(evt);
            }

            // set mapel
            var mapelInput = form.querySelector('input[name="mapel"]');
            if (mapelInput) mapelInput.value = data.mapel || '';

            // set hari
            var hariSelect = form.querySelector('select[name="hari"]');
            if (hariSelect) hariSelect.value = data.hari || '';

            // set jam
            var jamInput = form.querySelector('input[name="jam"]');
            if (jamInput) jamInput.value = data.jam || '';
        }

        function exitEditMode() {
            form.action = originalAction;
            submitBtn.textContent = 'Simpan Jadwal';
            cancelBtn.style.display = 'none';

            // reset fields
            var guruSelect = form.querySelector('select[name="guru_id"]');
            if (guruSelect) guruSelect.selectedIndex = 0;
            var mapelInput = form.querySelector('input[name="mapel"]');
            if (mapelInput) mapelInput.value = '';
            var hariSelect = form.querySelector('select[name="hari"]');
            if (hariSelect) hariSelect.selectedIndex = 0;
            var jamInput = form.querySelector('input[name="jam"]');
            if (jamInput) jamInput.value = '';
        }

        // attach edit button handlers
        var editButtons = document.querySelectorAll('.btn-edit');
        editButtons.forEach(function(btn){
            btn.addEventListener('click', function(e){
                var id = this.getAttribute('data-id');
                var guruId = this.getAttribute('data-guru-id');
                var mapel = this.getAttribute('data-mapel');
                var hari = this.getAttribute('data-hari');
                var jam = this.getAttribute('data-jam');

                enterEditMode({id: id, guruId: guruId, mapel: mapel, hari: hari, jam: jam});
                // scroll to form
                form.scrollIntoView({behavior: 'smooth', block: 'start'});
            });
        });

        // cancel edit
        cancelBtn.addEventListener('click', function(){
            exitEditMode();
        });
    });
    </script>

    @endsection
