@extends('layouts.app')

@section('content')

<div class="container mt-5 p-3 p-md-4">

@if($guru)
<div style="text-align: center; margin-bottom: 30px;">
    @if($guru->foto)
        <img src="{{ asset('storage/' . $guru->foto) }}" 
             alt="{{ $guru->user->name }}" 
             style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #ff7a00; margin-bottom: 15px;">
    @else
        <div style="width: 120px; height: 120px; margin: 0 auto 15px; border-radius: 50%; background: #ffd580; display: flex; align-items: center; justify-content: center; font-size: 48px; border: 4px solid #ff7a00;">
            👨‍🏫
        </div>
    @endif
    <h3 class="fw-bold mb-2" style="color:#ff7a00;">
        {{ $guru->user->name }}
    </h3>
    <p class="text-muted">{{ $guru->mapel }}</p>
</div>
@endif

<h3 class="fw-bold mb-4" style="color:#ff7a00;">
Komentar Murid
</h3>

<div class="card shadow-lg border-0">

<div class="card-body">

@if($surveys->isEmpty())
    <div class="alert alert-info">
        Belum ada komentar dari murid.
    </div>
@else
<div class="table-responsive">
<table class="table table-striped table-hover table-bordered">

<thead class="table-dark">
<tr>
<th>Murid</th>
<th>Kelas</th>
<th>Komentar</th>
<th>Tanggal</th>
<th>Balasan / Aksi</th>
</tr>
</thead>

<tbody>

@foreach($surveys as $s)

<tr>

<td>{{ $s->murid->name }}</td>

<td>{{ $s->murid->murid->kelas ?? '-' }}</td>

<td style="max-width: 300px; word-wrap: break-word;">{{ $s->komentar }}</td>

<td>{{ $s->created_at->format('d M Y') }}</td>

    <td style="max-width: 420px; vertical-align: top;">
        @php
            // Build a merged thread of muridReplies and guru replies sorted by timestamp
            $thread = collect();
            if (isset($s->muridReplies) && $s->muridReplies->isNotEmpty()) {
                foreach ($s->muridReplies as $mr) {
                    $thread->push((object)[
                        'type' => 'murid',
                        'name' => $mr->murid->name ?? 'Murid',
                        'created_at' => $mr->created_at,
                        'message' => $mr->message,
                    ]);
                }
            }
            if (isset($s->replies) && $s->replies->isNotEmpty()) {
                foreach ($s->replies as $r) {
                    $thread->push((object)[
                        'type' => 'guru',
                        'name' => $r->guru->user->name ?? 'Guru',
                        'created_at' => $r->created_at,
                        'message' => $r->message,
                    ]);
                }
            }
            $thread = $thread->sortBy('created_at')->values();
        @endphp

        <div style="max-height:340px; overflow:auto; padding-right:6px;">
            @if($thread->isEmpty())
                <div class="text-muted small">Belum ada balasan.</div>
            @else
                @foreach($thread as $t)
                    @php
                        $parts = preg_split('/\s+/', trim($t->name));
                        $initials = '';
                        foreach($parts as $p) { $initials .= strtoupper(mb_substr($p,0,1)); if (mb_strlen($initials) >= 2) break; }
                    @endphp
                    <div class="d-flex mb-2">
                        <div class="me-2" style="flex-shrink:0;">
                            <div class="rounded-circle text-white d-flex align-items-center justify-content-center" style="width:40px;height:40px;font-weight:700; background: {{ $t->type == 'murid' ? '#0d6efd' : '#6c757d' }};">
                                {{ $initials }}
                            </div>
                        </div>
                        <div style="flex:1 1 auto; min-width:0;">
                            <div class="small text-muted">{{ 
                                ($t->created_at instanceof \Illuminate\Support\Carbon) ? $t->created_at->format('d M Y H:i') : (string)$t->created_at
                             }} — <strong>{{ $t->name }}</strong></div>
                            @if($t->type == 'murid')
                                <div class="mt-1 p-2 rounded" style="background:#0d6efd;color:#fff;">{{ $t->message }}</div>
                            @else
                                <div class="mt-1 p-2 rounded bg-light">{{ $t->message }}</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <form action="/guru/survey/reply" method="POST" class="mt-2">
            @csrf
            <input type="hidden" name="survey_id" value="{{ $s->id }}" />
            <div class="mb-2">
                <textarea name="message" class="form-control form-control-sm" rows="3" placeholder="Tulis balasan..." required style="resize:vertical;"></textarea>
            </div>
            <div class="d-flex justify-content-end">
                <button class="btn btn-sm btn-primary">Kirim Balasan</button>
            </div>
        </form>
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