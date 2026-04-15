@extends('layouts.app')

@section('content')

<div class="main-content py-5">

<div class="container-fluid">

<h3 class="fw-bold mb-4 text-indigo">Edit Jadwal Mengajar</h3>

<div class="card border-0 shadow-lg rounded-4">
<div class="card-body p-4">

<form action="/admin/jadwal/update/{{$jadwal->id}}" method="POST">
@csrf

<div class="mb-3">
<label class="fw-semibold">Guru</label>
<select name="guru_id" class="form-control">
@foreach($guru as $g)
<option value="{{$g->id}}" data-mapel="{{ $g->mapel }}"
{{ $jadwal->guru_id == $g->id ? 'selected' : '' }}>
{{$g->user->name}}
</option>
@endforeach
</select>
</div>

<div class="mb-3">
<label class="fw-semibold">Mapel</label>
<input type="text" name="mapel" class="form-control"
value="{{$jadwal->mapel}}">
</div>

<div class="mb-3">
<label class="fw-semibold">Kelas</label>
<select name="kelas" class="form-control">
<option {{ $jadwal->kelas == 'X RPL 1' ? 'selected' : '' }}>X RPL 1</option>
<option {{ $jadwal->kelas == 'XI RPL 1' ? 'selected' : '' }}>XI RPL 1</option>
<option {{ $jadwal->kelas == 'XII RPL 1' ? 'selected' : '' }}>XII RPL 1</option>
</select>
</div>

<div class="mb-3">
<label class="fw-semibold">Hari</label>
<select name="hari" class="form-control">
<option {{ $jadwal->hari == 'Senin' ? 'selected' : '' }}>Senin</option>
<option {{ $jadwal->hari == 'Selasa' ? 'selected' : '' }}>Selasa</option>
<option {{ $jadwal->hari == 'Rabu' ? 'selected' : '' }}>Rabu</option>
<option {{ $jadwal->hari == 'Kamis' ? 'selected' : '' }}>Kamis</option>
<option {{ $jadwal->hari == 'Jumat' ? 'selected' : '' }}>Jumat</option>
</select>
</div>

<div class="mb-4">
<label class="fw-semibold">Jam</label>
<input list="jamOptionsList" name="jam" class="form-control" value="{{ old('jam', $jadwal->jam ?? '') }}" placeholder="08:00 - 09:30">
<datalist id="jamOptionsList">
    @if(isset($jamOptions) && $jamOptions->count())
        @foreach($jamOptions as $jam)
            <option value="{{ $jam }}"></option>
        @endforeach
    @endif
</datalist>
</div>

<button class="btn btn-indigo fw-semibold px-4">Update Jadwal</button>

<a href="/admin/jadwal" class="btn btn-outline-secondary ms-2">
Batal
</a>

</form>

</div>
</div>

</div>

</div>

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

    // If an option is preselected, fill mapel
    var initialOpt = guruSelect.options[guruSelect.selectedIndex];
    if (initialOpt && initialOpt.getAttribute('data-mapel')) {
        mapelInput.value = initialOpt.getAttribute('data-mapel') || '';
    }
});
</script>

@endsection