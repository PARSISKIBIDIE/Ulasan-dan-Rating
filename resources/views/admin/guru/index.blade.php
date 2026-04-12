@extends('layouts.app')

@section('content')

<div style="
    min-height: calc(100vh - 56px);
    background: #fff3e6;
    padding: 60px 0;
">

    <div class="container" style="max-width:1100px;">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold m-0" style="color:#ff7a00;">
                Data Guru
            </h2>

            <a href="/admin/guru/create"
               class="btn text-white px-4 rounded-3"
               style="background-color:#ff7a00;">
                Tambah Guru
            </a>
        </div>

        <div class="card border-0 shadow-lg rounded-4">
            <div class="card-body p-4">

                <div class="table-responsive">
                    <table class="table align-middle">

                        <thead style="background-color:#fff0e6;">
                            <tr>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>NIP</th>
                                <th>Mapel</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if($gurus->count() > 0)
                                @foreach($gurus as $guru)
                                <tr>

                            @push('scripts')
                            <script>
                            document.addEventListener('click', function(e){
                                if (e.target && e.target.classList.contains('preview-delete')) {
                                    if (!confirm('Hapus preview ini dari daftar?')) return;
                                    const tr = e.target.closest('tr');
                                    if (tr) tr.remove();
                                    // if no more preview rows, show a small message
                                    const tbody = document.querySelector('table.table tbody');
                                    if (tbody && tbody.querySelectorAll('tr').length === 0) {
                                        const wrapper = document.querySelector('.table-responsive');
                                        if (wrapper) wrapper.innerHTML = '<p class="text-muted p-4">Tidak ada data guru.</p>';
                                    }
                                }
                            });
                            </script>
                            @endpush
                                    <td>
                                        @if($guru->foto)
                                            <img src="{{ asset('storage/' . $guru->foto) }}" 
                                                 alt="{{ $guru->user->name }}" 
                                                 style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                                        @else
                                            <div style="width: 50px; height: 50px; border-radius: 50%; background: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #999;">
                                                👨‍🏫
                                            </div>
                                        @endif
                                    </td>
                                    <td class="fw-semibold">{{ $guru->user->name }}</td>
                                    <td>{{ $guru->nip }}</td>
                                    <td>{{ $guru->mapel }}</td>
                                    <td class="text-center">

                                        <a href="/admin/guru/edit/{{ $guru->id }}"
                                           class="btn btn-sm btn-outline-warning rounded-3 me-2">
                                            Edit
                                        </a>

                                        <a href="/admin/guru/delete/{{ $guru->id }}"
                                           class="btn btn-sm btn-outline-danger rounded-3">
                                            Hapus
                                        </a>

                                    </td>
                                </tr>
                                @endforeach
                            @else
                                {{-- Preview transcribed data (database kosong) --}}
                                @php $nipBase = 1234567890; $i = 1; @endphp
                                @foreach($previewTeachers as $t)
                                    <tr>
                                        <td>
                                            <div style="width: 50px; height: 50px; border-radius: 50%; background: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #999;">
                                                👨‍🏫
                                            </div>
                                        </td>
                                        <td class="fw-semibold">{{ $t['name'] }}</td>
                                        <td>{{ $nipBase + $i }}</td>
                                        <td>{{ $t['mapel'] }}</td>
                                        <td class="text-center">
                                            <a href="/admin/guru/create?name={{ urlencode($t['name']) }}&nip={{ $nipBase + $i }}&mapel={{ urlencode($t['mapel']) }}" class="btn btn-sm btn-outline-warning me-2">Edit</a>
                                            <button class="btn btn-sm btn-outline-danger preview-delete">Hapus</button>
                                        </td>
                                    </tr>
                                    @php $i++; @endphp
                                @endforeach

                                <tr>
                                    <td colspan="5" class="text-end">
                                        <form method="POST" action="/admin/guru/import-preview" onsubmit="return confirm('Simpan semua preview guru ke database?');" style="display:inline-block;">
                                            @csrf
                                            <button class="btn btn-primary">Simpan Semua Preview ke Database</button>
                                        </form>
                                    </td>
                                </tr>
                            @endif
                        </tbody>

                    </table>
                </div>

            </div>
        </div>

    </div>

</div>

@endsection