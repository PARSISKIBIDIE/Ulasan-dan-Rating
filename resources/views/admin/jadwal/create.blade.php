@extends('layouts.app')

@section('content')

<div class="main-content py-5">

<div class="container-fluid">

<h3 class="fw-bold mb-4 text-indigo">Tambah Jadwal Mengajar</h3>

<div class="card border-0 shadow-lg rounded-4">
<div class="card-body p-4">

<form action="/admin/jadwal/store" method="POST">
@csrf

<div class="mb-3">
<label class="fw-semibold">Guru</label>
<select name="guru_id" class="form-control">
    <option selected disabled>Pilih Guru</option>
    @foreach($guru as $g)
        <option value="{{ $g->id }}" data-mapel="{{ $g->mapel }}">{{ $g->user->name }}</option>
    @endforeach
</select>
</div>

<div class="mb-3">
<label class="fw-semibold">Mapel</label>
<input type="text" name="mapel" class="form-control">
</div>

<div class="mb-3">
<label class="fw-semibold">Kelas</label>
<select name="kelas" class="form-control">
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

<div class="mb-3">
<label class="fw-semibold">Hari</label>
<select name="hari" class="form-control">
<option>Senin</option>
<option>Selasa</option>
<option>Rabu</option>
<option>Kamis</option>
<option>Jumat</option>
</select>
</div>

<div class="mb-4">
<label class="fw-semibold">Jam</label>
<input list="jamOptionsList" name="jam" class="form-control" placeholder="08:00 - 09:30" value="{{ old('jam') }}">
<datalist id="jamOptionsList">
    @if(isset($jamOptions) && $jamOptions->count())
        @foreach($jamOptions as $jam)
            <option value="{{ $jam }}"></option>
        @endforeach
    @endif
</datalist>
</div>

<button class="btn btn-indigo fw-semibold px-4">Simpan Jadwal</button>

<a href="/admin/jadwal" class="btn btn-outline-secondary ms-2">
Batal
</a>

</form>

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

</div>
</div>

@endsection