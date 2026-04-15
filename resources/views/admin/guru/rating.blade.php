@extends('layouts.app')

@section('content')

<div class="container mt-5 p-3 p-md-4">

@if($guru)
<div style="text-align: center; margin-bottom: 30px;">
    @if($guru->foto)
           <img src="{{ asset('storage/' . $guru->foto) }}" 
               alt="{{ $guru->user->name }}" 
               style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #4F46E5; margin-bottom: 15px;">
    @else
        <div style="width: 120px; height: 120px; margin: 0 auto 15px; border-radius: 50%; background: #ffd580; display: flex; align-items: center; justify-content: center; font-size: 48px; border: 4px solid #4F46E5;">
            👨‍🏫
        </div>
    @endif
    <h3 class="fw-bold mb-2 text-indigo">
        {{ $guru->user->name }}
    </h3>
    <p class="text-muted">{{ $guru->mapel }}</p>
</div>
@endif

<h3 class="fw-bold mb-4 text-indigo">Rating Guru</h3>

@php
$rata = round($ratings->avg('rating'),1);
$total = $ratings->count();
@endphp


<!-- CARD RATA RATA RATING -->

<div class="card shadow-lg border-0 rounded-4 mb-4">

<div class="card-body text-center p-4">

<h5 class="fw-bold mb-2">
Rata-rata Penilaian Murid
</h5>

<div style="font-size:40px; color:#ffb400;">
{{ $rata }} ⭐
</div>

<p class="text-muted mb-0">
Berdasarkan {{ $total }} penilaian murid
</p>

</div>

</div>



<!-- TABEL RATING -->

<div class="card shadow-lg border-0 rounded-4">

<div class="card-body">

@if($ratings->isEmpty())
    <div class="alert alert-info">
        Belum ada rating dari murid.
    </div>
@else
<div class="table-responsive">

<table class="table table-hover align-middle">

<thead>
<tr>
<th>Murid</th>
<th>Kelas</th>
<th>Rating</th>
<th>Tanggal</th>
</tr>
</thead>

<tbody>

@foreach($ratings as $r)

<tr>

<td class="fw-semibold">
{{ $r->murid->name }}
</td>

<td>
{{ $r->murid->murid->kelas ?? '-' }}
</td>

<td>

@if($r->rating == 5)
⭐⭐⭐⭐⭐
@elseif($r->rating == 4)
⭐⭐⭐⭐
@elseif($r->rating == 3)
⭐⭐⭐
@elseif($r->rating == 2)
⭐⭐
@else
⭐
@endif

</td>

<td>
{{ $r->created_at->format('d M Y') }}
</td>

</tr>

@endforeach

</tbody>

</table>

</div>
@endif

</div>

</div>

</div>

@endsection