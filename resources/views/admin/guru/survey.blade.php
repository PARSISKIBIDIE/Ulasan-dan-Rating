@extends('layouts.app')

@section('content')

<style>
    .survey-table-wrap {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .survey-comments-table {
        min-width: 980px;
        table-layout: fixed;
    }

    .survey-comments-table th,
    .survey-comments-table td {
        white-space: normal;
        word-break: break-word;
        vertical-align: top;
    }

    .survey-col-komentar {
        min-width: 260px;
    }

    .survey-col-aksi {
        min-width: 360px;
    }

    @media (max-width: 768px) {
        .survey-table-wrap table.survey-comments-table {
            display: table !important;
            width: 100%;
            white-space: normal !important;
        }

        .survey-comments-table th,
        .survey-comments-table td {
            padding: 0.5rem;
            font-size: 0.9rem;
        }
    }
</style>

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
    <h3 class="fw-bold mb-2 text-indigo">{{ $guru->user->name }}</h3>
    <p class="text-muted">{{ $guru->mapel }}</p>
</div>
@endif

<h3 class="fw-bold mb-4 text-indigo">Komentar Murid</h3>

<div class="card shadow-lg border-0">

<div class="card-body">

@if($surveys->isEmpty())
    <div class="alert alert-info">
        Belum ada komentar dari murid.
    </div>
@else
<div class="table-responsive survey-table-wrap">
<table class="table table-striped table-hover table-bordered survey-comments-table">

<thead class="table-dark">
<tr>
<th>Murid</th>
<th>Kelas</th>
<th class="survey-col-komentar">Komentar</th>
<th>Tanggal</th>
<th class="survey-col-aksi">Balasan / Aksi</th>
</tr>
</thead>

<tbody>

@foreach($surveys as $s)

<tr>

<td>{{ $s->murid->name }}</td>

<td>{{ $s->murid->murid->kelas ?? '-' }}</td>

<td class="survey-col-komentar">{{ $s->komentar }}</td>

<td>{{ $s->created_at->format('d M Y') }}</td>

    <td class="survey-col-aksi">
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
                            <div class="small text-muted">
                                <time class="reply-time" data-ts="{{ ($t->created_at instanceof \Illuminate\Support\Carbon) ? $t->created_at->getTimestamp() : strtotime((string)$t->created_at) }}">
                                    {{ ($t->created_at instanceof \Illuminate\Support\Carbon) ? $t->created_at->format('d M Y H:i') : (string)$t->created_at }}
                                </time> — <strong>{{ $t->name }}</strong>
                            </div>
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
                <button class="btn btn-sm btn-indigo">Kirim Balasan</button>
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