@extends('layouts.app')

@section('content')

<div class="container mt-5">

    <h3 class="fw-bold mb-4" style="color:#ff7a00;">
        Hasil Sistem Ulasan dan Rating & Rating Guru
    </h3>

    @if($guruStats->isEmpty())
        <div class="alert alert-info">
            Belum ada data sistem ulasan dan rating yang tersedia.
        </div>
    @else
        <div class="row">
            @foreach($guruStats as $guruId => $stats)
                <div class="col-md-6 mb-4">
                    <div class="card shadow-lg border-0 rounded-4" style="overflow: hidden;">
                        <div style="background: linear-gradient(135deg, #ff7a00 0%, #ffb400 100%); padding: 20px; text-align: center;">
                            @if($stats['guru']->foto)
                                <img src="{{ asset('storage/' . $stats['guru']->foto) }}" 
                                     alt="{{ $stats['guru']->user->name }}" 
                                     class="rounded-circle" 
                                     style="width: 120px; height: 120px; object-fit: cover; border: 4px solid white; margin-bottom: 15px;">
                            @else
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center" 
                                     style="width: 120px; height: 120px; background: #fff; margin-bottom: 15px; font-size: 48px;">
                                    👨‍🏫
                                </div>
                            @endif
                            <h5 class="mb-0 text-white fw-bold">{{ $stats['guru']->user->name }}</h5>
                            <small class="text-white">{{ $stats['guru']->mapel }}</small>
                        </div>
                        <div class="card-body">
                            <div class="row text-center mb-3">
                                <div class="col-6">
                                    <h4 class="text-warning">{{ $stats['avg_rating'] }} ⭐</h4>
                                    <small>Rata-rata Rating</small>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-primary">{{ $stats['total_survey'] }}</h4>
                                    <small>Total Sistem Ulasan dan Rating</small>
                                </div>
                            </div>

                            <h6 class="fw-bold mb-3">Detail Sistem Ulasan dan Rating:</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Murid</th>
                                            <th>Kelas</th>
                                            <th>Rating</th>
                                            <th>Komentar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stats['surveys'] as $survey)
                                            <tr>
                                                <td>{{ $survey->murid->name }}</td>
                                                <td>{{ $survey->murid->murid->kelas ?? '-' }}</td>
                                                <td>
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $survey->rating)
                                                            ⭐
                                                        @else
                                                            ☆
                                                        @endif
                                                    @endfor
                                                </td>
                                                <td>
                                                    @if($survey->komentar)
                                                        <small>{{ Str::limit($survey->komentar, 30) }}</small>
                                                    @else
                                                        <em class="text-muted">Tidak ada komentar</em>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if($ratings->isNotEmpty())
        <div class="card shadow-lg border-0 rounded-4 mt-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Rating Detail</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Guru</th>
                                <th>Murid</th>
                                <th>Kelas</th>
                                <th>Rating</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ratings as $rating)
                                <tr>
                                    <td class="fw-semibold">{{ $rating->survey->guru->user->name }}</td>
                                    <td>{{ $rating->murid->user->name }}</td>
                                    <td>{{ $rating->murid->kelas }}</td>
                                    <td>
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $rating->rating)
                                                ⭐
                                            @else
                                                ☆
                                            @endif
                                        @endfor
                                    </td>
                                    <td>{{ $rating->created_at->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

</div>

@endsection