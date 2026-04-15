@extends('layouts.app')

@section('content')

<!-- DEBUG section -->
@if(config('app.debug'))
<div style="background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; margin-bottom: 10px; border-radius: 4px; font-family: monospace; font-size: 11px; display: none;">
    <strong>DEBUG jamByDayAll:</strong><br>
    @json($jamByDayAll ?? [])
</div>
@endif

<style>
/* Jadwal table compact styles */
.jadwal-table { width:100%; border-collapse:separate; border-spacing:0; }
.jadwal-table th { color: var(--color-indigo); font-weight:700; font-size:0.95rem; border-bottom:1px solid #eee; padding:0.55rem 0.75rem; }
.jadwal-table td { padding:0.5rem 0.75rem; vertical-align:middle; font-size:0.95rem; }
.jadwal-table tbody tr + tr td { border-top:1px solid #f5f5f5; }
.jadwal-table .text-truncate { max-width:220px; display:inline-block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; vertical-align:middle; }
.mapel-full { display:block; white-space:normal; word-break:break-word; max-width:100%; }
.btn-survey { background:var(--color-indigo); color:#fff; border-radius:20px; padding:0.28rem 0.8rem; font-size:0.86rem; border:none; }
.jadwal-day { margin-bottom:1.2rem; }
@media (max-width:768px) {
	.jadwal-table th, .jadwal-table td { padding:0.4rem 0.5rem; font-size:0.9rem; }
	.jadwal-table .text-truncate { max-width:120px; }
}
</style>

<div class="main-content">

<div class="container">

<!-- ================= TITLE ================= -->
<h2 class="fw-bold text-center mt-4 mb-5 text-indigo">Dashboard Murid</h2>

@if(isset($replies) && $replies->count() > 0)
	<div class="mb-4">
		<div class="d-flex justify-content-between align-items-center mb-2">
			<h5 class="mb-0">Inbox Guru</h5>
			<a id="inboxPanelToggle" class="btn btn-outline-primary" data-bs-toggle="collapse" href="#inboxPanel" role="button" aria-expanded="false" aria-controls="inboxPanel">
				Lihat Inbox <span class="badge bg-danger ms-2">{{ $replies->count() }}</span>
			</a>
		</div>
	</div>

	<div class="collapse mb-4" id="inboxPanel">
		<div class="card p-2">
			<ul class="list-group list-group-flush">
				@foreach($replies as $r)
					@php
						$teacherName = $r->guru->user->name ?? 'Guru';
						$parts = preg_split('/\s+/', trim($teacherName));
						$initials = '';
						foreach($parts as $p) { $initials .= strtoupper(mb_substr($p,0,1)); if (mb_strlen($initials) >= 2) break; }
					@endphp
					<li class="list-group-item px-3 py-3 d-flex align-items-start gap-3" data-survey-id="{{ $r->survey->id }}">
						<div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:48px;height:48px;font-weight:600; flex-shrink:0;">
							{{ $initials }}
						</div>
						<div style="flex:1 1 auto; min-width:0;">
							<div class="d-flex justify-content-between align-items-start">
								<div class="fw-semibold">{{ $teacherName }}</div>
								<div class="small text-muted">{{ $r->created_at->format('d M Y H:i') }}</div>
							</div>
							<div class="mt-1">{{ $r->message }}</div>
							<div class="small text-muted mt-1">Untuk komentar: "{{ \Illuminate\Support\Str::limit($r->survey->komentar, 100) }}"</div>

							<!-- Reply area for murid -->
							<div class="mt-2 reply-area">
								<button type="button" class="btn btn-sm btn-outline-primary btn-open-reply">Balas</button>
								<div class="reply-form mt-2" style="display: none;">
									<textarea class="form-control form-control-sm reply-text" rows="2" placeholder="Tulis balasan..."></textarea>
									<div class="mt-2 text-end">
										<button class="btn btn-sm btn-primary btn-send-reply">Kirim</button>
										<button class="btn btn-sm btn-outline-secondary btn-cancel-reply">Batal</button>
									</div>
								</div>
								<div class="sent-replies mt-2"></div>
							</div>
						</div>
					</li>
				@endforeach
			</ul>
		</div>
	</div>
@endif

@if(isset($readReplies) && $readReplies && $readReplies->total() > 0)
	<div class="mb-4">
		<div class="d-flex justify-content-between align-items-center mb-2">
			<h5 class="mb-0">Riwayat Balasan</h5>
			<a class="btn btn-outline-secondary" data-bs-toggle="collapse" href="#historyPanel" role="button" aria-expanded="false" aria-controls="historyPanel">Lihat</a>
		</div>
	</div>

	<div class="collapse mb-4" id="historyPanel">
		<div class="card p-2">
			<ul class="list-group list-group-flush">
				@foreach($readReplies as $r)
					@php
						$teacherName = $r->guru->user->name ?? 'Guru';
						$parts = preg_split('/\s+/', trim($teacherName));
						$initials = '';
						foreach($parts as $p) { $initials .= strtoupper(mb_substr($p,0,1)); if (mb_strlen($initials) >= 2) break; }
					@endphp
					<li class="list-group-item px-3 py-3" data-survey-id="{{ $r->survey->id }}">
						<div class="d-flex justify-content-between">
							<div class="d-flex align-items-center gap-3">
								<div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width:40px;height:40px;font-weight:600;">
									{{ $initials }}
								</div>
								<div>
									<div class="fw-semibold">{{ $teacherName }}</div>
									<div class="small text-muted">{{ $r->read_at->format('d M Y H:i') }}</div>
								</div>
							</div>
							<div class="small text-muted">Untuk komentar: "{{ \Illuminate\Support\Str::limit($r->survey->komentar, 80) }}"</div>
						</div>
						<div class="mt-2">{{ $r->message }}</div>

						<!-- Reply area for murid in history -->
						<div class="mt-2 reply-area">
							<button type="button" class="btn btn-sm btn-outline-primary btn-open-reply">Balas</button>
							<div class="reply-form mt-2" style="display: none;">
								<textarea class="form-control form-control-sm reply-text" rows="2" placeholder="Tulis balasan..."></textarea>
								<div class="mt-2 text-end">
									<button class="btn btn-sm btn-primary btn-send-reply">Kirim</button>
									<button class="btn btn-sm btn-outline-secondary btn-cancel-reply">Batal</button>
								</div>
							</div>
							<div class="sent-replies mt-2"></div>
						</div>
					</li>
				@endforeach
			</ul>

			<nav aria-label="Riwayat pagination" class="mt-3">
				<ul class="pagination justify-content-between mb-0">
					<li class="page-item {{ $readReplies->previousPageUrl() ? '' : 'disabled' }}">
						@if($readReplies->previousPageUrl())
							<a class="page-link" href="{{ $readReplies->previousPageUrl() }}">Sebelumnya</a>
						@else
							<span class="page-link">Sebelumnya</span>
						@endif
					</li>

					<li class="page-item disabled align-self-center"><span class="page-link">Halaman {{ $readReplies->currentPage() }} dari {{ $readReplies->lastPage() }}</span></li>

					<li class="page-item {{ $readReplies->nextPageUrl() ? '' : 'disabled' }}">
						@if($readReplies->nextPageUrl())
							<a class="page-link" href="{{ $readReplies->nextPageUrl() }}">Selanjutnya</a>
						@else
							<span class="page-link">Selanjutnya</span>
						@endif
					</li>
				</ul>
			</nav>
		</div>
	</div>
@endif


<!-- ================= ROW CARD + FILTER ================= -->

<div class="row mb-5 g-4">

<!-- CARD KELAS -->

<div class="col-md-4">

<div class="card border-0 shadow-lg rounded-4 h-100">

<div class="card-body text-center p-3 p-md-5">

<h5 class="fw-bold mb-3">
Kelas Kamu
</h5>

<p class="text-muted fs-4 mb-0">
{{ $murid->kelas }}
</p>

</div>

</div>

</div>



<!-- FILTER HARI + JAM -->

<div class="col-md-8">

<div class="card border-0 shadow-lg rounded-4 h-100">

<div class="card-body p-3 p-md-5">

<h5 class="fw-bold mb-3 text-indigo">Pilih Hari & Jam Pelajaran</h5>

<div class="row g-2">

	<div class="col-md-6">
		@php $weekdayOptions = ['Senin','Selasa','Rabu','Kamis','Jumat']; @endphp
		<select id="filterHari" class="form-select">
			<option value="">-- Pilih Hari --</option>
			@foreach($weekdayOptions as $d)
				<option value="{{ $d }}">{{ $d }}</option>
			@endforeach
		</select>
	</div>

	<div class="col-md-6">
		<select id="filterJam" class="form-select">
			<option value="">-- Pilih Jam --</option>
			@foreach(($jamOptions ?? collect()) as $jam)
				<option value="{{ $jam }}">{{ $jam }}</option>
			@endforeach
		</select>
	</div>

</div>

</div>

</div>

</div>

</div>



<!-- ================= TABEL JADWAL ================= -->

<div class="row">

<div class="col-12">

<div class="card border-0 shadow-lg rounded-4">

<div class="card-body p-4">

<h4 class="fw-bold mb-4 text-center text-indigo">Jadwal Pelajaran</h4>

<div class="table-responsive">

@php
	$weekdays = ['Senin','Selasa','Rabu','Kamis','Jumat'];
	$grouped = $jadwal->groupBy('hari');
@endphp

<div id="emptyScheduleMessage" class="text-center text-muted py-5" style="display:none;">
    Silahkan pilih hari, jam, dan mata pelajaran sesuai.
</div>

@foreach($weekdays as $day)
	<div class="day-block mb-4" data-day="{{ $day }}">
		<h5 class="fw-semibold text-start text-indigo">{{ $day }}</h5>

			@if(isset($grouped[$day]))
			<div class="table-responsive">
				<table class="table jadwal-table table-borderless align-middle">
					<thead>
						<tr>
							<th class="text-start">Guru</th>
							<th class="text-start">Mapel</th>
							<th class="text-center">Jam</th>
							<th class="text-center">Aksi</th>
						</tr>
					</thead>
					<tbody>
						@foreach($grouped[$day] as $j)
							<tr class="jadwal-row" data-jam="{{ $j->jam }}" data-hari="{{ $j->hari }}" data-mapel="{{ $j->mapel }}">
								<td class="fw-semibold text-start"><span class="text-truncate">{{ $j->guru->user->name }}</span></td>
								<td class="text-start"><div class="mapel-full">{{ $j->mapel }}</div></td>
								<td class="text-center text-muted small">{{ $j->jam }}</td>
								<td class="text-center">
									@if(isset($eligibilities[$j->id]) && $eligibilities[$j->id]['eligible'])
										<a href="/survey/{{ $j->id }}" class="btn btn-survey btn-sm check-eligibility" data-id="{{ $j->id }}" title="Isi Sistem Ulasan dan Rating untuk {{ $j->mapel }} ({{ $j->guru->user->name }})" aria-label="Isi rating">Isi</a>
									@else
										<div class="small text-muted">{{ isset($eligibilities[$j->id]) ? $eligibilities[$j->id]['message'] : 'Lengkapi syarat terlebih dahulu' }}</div>
									@endif
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		@else
			<p class="text-muted">Tidak ada jadwal</p>
		@endif
	</div>
@endforeach

</div>

</div>

</div>

</div>

</div>

</div>

</div>



<!-- ================= SCRIPT FILTER HARI + JAM ================= -->

<!-- DEBUG: Raw jamByDayAll from server -->
<!-- jamByDayAll = @json($jamByDayAll ?? []) -->

<script>

function applyFilters() {
	const jamDipilih = (document.getElementById('filterJam').value || '').toString();
	const hariDipilih = (document.getElementById('filterHari').value || '').toString();
	const rows = document.querySelectorAll('.jadwal-row');
	const emptyMessage = document.getElementById('emptyScheduleMessage');

	// helper to normalize strings for comparison
	function norm(v){ return (v||'').toString().trim().toLowerCase(); }

	// if both filters are empty, hide everything (unless special ALL_MAPEL option selected)
	const dayBlocks = document.querySelectorAll('.day-block');
	const SPECIAL_ALL = 'ALL_MAPEL_TODAY';
	const todayNameSrv = @json($todayName ?? null);
	const todayNameNorm = norm(todayNameSrv);
	const jamIsAll = norm(jamDipilih) === norm(SPECIAL_ALL);

	// determine which day to show when ALL option selected: prefer selected hari, fallback to today
	const dayToShow = jamIsAll ? (norm(hariDipilih) ? hariDipilih : todayNameSrv) : null;

	if (!jamIsAll && (norm(jamDipilih) === "" || norm(hariDipilih) === "")) {
		dayBlocks.forEach(function(block){ block.style.display = 'none'; });
		if (emptyMessage) emptyMessage.style.display = '';
		return;
	}

	if (emptyMessage) emptyMessage.style.display = 'none';

	// show rows depending on selection
	rows.forEach(function(row){
		const jam = (row.getAttribute('data-jam') || '').toString();
		const hari = (row.getAttribute('data-hari') || '').toString();

		if (jamIsAll) {
			// show all rows that belong to the determined day
			if (norm(hari) === norm(dayToShow)) {
				row.style.display = "";
			} else {
				row.style.display = "none";
			}
		} else {
			if (norm(jam) === norm(jamDipilih) && norm(hari) === norm(hariDipilih)) {
				row.style.display = "";
			} else {
				row.style.display = "none";
			}
		}
	});

	// hide day block if all rows hidden
	dayBlocks.forEach(function(block){
		const dayRows = block.querySelectorAll('.jadwal-row');
		let anyVisible = false;
		dayRows.forEach(function(r){ if (r.style.display !== 'none') anyVisible = true; });
		block.style.display = anyVisible ? '' : 'none';
	});

}

// Populate jam dropdown dynamically based on selected hari (uses jamByDayAll from controller)
const jamByDayAll = @json($jamByDayAll ?? []);
const jamOptionsAll = @json($jamOptions ?? []);

console.debug('=== Startup Debug ===');
console.debug('jamByDayAll received from server:', jamByDayAll);
console.debug('jamByDayAll keys:', Object.keys(jamByDayAll));
console.debug('jamOptionsAll received from server:', jamOptionsAll);
console.log('FULL jamByDayAll as JSON string:');
console.log(JSON.stringify(jamByDayAll, null, 2));

function normKey(v){ return (v||'').toString().trim().toLowerCase(); }

function parseStartMinutes(jam){
	try {
		if (!jam) return Number.MAX_SAFE_INTEGER;
		const parts = jam.split('-');
		const start = (parts[0] || jam).trim().replace('.', ':');
		const m = start.split(':').map(x=>parseInt(x,10));
		if (m.length === 1 || isNaN(m[0])) return Number.MAX_SAFE_INTEGER;
		const minutes = (m[0] || 0) * 60 + (m[1] || 0);
		return minutes;
	} catch (e) {
		return Number.MAX_SAFE_INTEGER;
	}
}

function sortJams(list){
	if (!Array.isArray(list)) return [];
	const sorted = (list || []).slice().sort(function(a,b){
		return parseStartMinutes(a) - parseStartMinutes(b);
	});
	console.debug('sortJams input:', list, ' -> sorted:', sorted);
	return sorted;
}

// normalize jamByDayAll keys and ensure unique + sorted jam lists
const normalizedJamByDay = {};
Object.keys(jamByDayAll || {}).forEach(function(k){
	const key = normKey(k);
	const raw = Array.isArray(jamByDayAll[k]) ? jamByDayAll[k] : [];
	const seen = new Set();
	const uniq = [];
	raw.forEach(function(j){
		const v = (j || '').toString().trim();
		if (!v) return;
		if (!seen.has(v)) { seen.add(v); uniq.push(v); }
	});
	normalizedJamByDay[key] = sortJams(uniq);
});

const jamOptionsAllSorted = sortJams(Array.isArray(jamOptionsAll) ? jamOptionsAll : []);

// DEBUG: print jam data to console to help debugging if options appear missing
console.debug('jamByDayAll (raw):', jamByDayAll);
console.debug('normalizedJamByDay:', normalizedJamByDay);
console.debug('jamOptionsAllSorted:', jamOptionsAllSorted);
console.debug('Object.keys(normalizedJamByDay):', Object.keys(normalizedJamByDay));
console.debug('Number of days with jam:', Object.keys(normalizedJamByDay).length);

function populateJamOptions(list) {
	const sel = document.getElementById('filterJam');
	if (!sel) return;
	sel.innerHTML = '<option value="">-- Pilih Jam --</option>';
	console.debug('Populating jam with list:', list, ' (count:', (list || []).length, ')');
	(list || []).forEach(function(j){
		const opt = document.createElement('option');
		opt.value = j;
		opt.textContent = j;
		sel.appendChild(opt);
	});
	console.debug('JAM DROPDOWN NOW HAS ' + sel.options.length + ' TOTAL OPTIONS (including "Pilih Jam")');
}

document.getElementById('filterJam').addEventListener('change', applyFilters);
document.getElementById('filterHari').addEventListener('change', function(e){
	const day = (e.target.value || '');
	console.log('Selected day:', day);
	// Keep jam dropdown as the full list (do not filter by day)
	applyFilters();
});

// populate jam dropdown initially with all jam options then hide until both selected
document.addEventListener('DOMContentLoaded', function(){
	console.log('Page loaded - dropdowns initialized');
	console.log('jamByDayAll keys at init:', Object.keys(normalizedJamByDay));
	// Populate jam dropdown with all jam options (sorted earliest-first)
	populateJamOptions(jamOptionsAllSorted);
	applyFilters();
});

// AJAX eligibility check on click
document.addEventListener('click', function(e){
	const btn = e.target.closest('.check-eligibility');
	if (!btn) return;
	e.preventDefault();
	const id = btn.getAttribute('data-id');
	fetch('/ajax/check-eligibility/' + id, { headers: { 'Accept': 'application/json' } })
		.then(response => response.json())
		.then(data => {
			if (data.eligible) {
				window.location = '/survey/' + id;
			} else {
				if (window.showNotification) {
					var type = data.already ? 'success' : 'warning';
					showNotification(data.message || 'Lengkapi syarat terlebih dahulu', type);
				}
				const parentTd = btn.parentElement;
				parentTd.innerHTML = '<select class="form-select" disabled><option>' + (data.message || 'Lengkapi syarat terlebih dahulu') + '</option></select>';
			}
		})
		.catch(() => {
			alert('Gagal mengecek kelayakan. Coba lagi.');
		});
});

// Reply handling (open form, cancel, send via AJAX)
document.addEventListener('click', function(e){
	var openBtn = e.target.closest('.btn-open-reply');
	if (openBtn) {
		var li = openBtn.closest('li');
		var form = li.querySelector('.reply-form');
		if (form) { form.style.display = (form.style.display === 'none' || !form.style.display) ? '' : 'none'; var ta = form.querySelector('.reply-text'); if (ta) ta.focus(); }
		return;
	}

	var cancelBtn = e.target.closest('.btn-cancel-reply');
	if (cancelBtn) {
		var li = cancelBtn.closest('li');
		var form = li.querySelector('.reply-form');
		if (form) { form.style.display = 'none'; var ta = form.querySelector('.reply-text'); if (ta) ta.value = ''; }
		return;
	}

	var sendBtn = e.target.closest('.btn-send-reply');
	if (sendBtn) {
		var li = sendBtn.closest('li');
		var surveyId = li.getAttribute('data-survey-id');
		var form = li.querySelector('.reply-form');
		var ta = form ? form.querySelector('.reply-text') : null;
		if (!ta) return;
		var message = (ta.value || '').trim();
		if (!message) { alert('Isi pesan balasan terlebih dahulu'); return; }

		sendBtn.disabled = true;

		fetch('/murid/reply', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'Accept': 'application/json',
				'X-CSRF-TOKEN': '{{ csrf_token() }}'
			},
			body: JSON.stringify({ survey_id: surveyId, message: message })
		}).then(function(res){ return res.json(); }).then(function(data){
			sendBtn.disabled = false;
			if (data && data.success) {
				// append the sent reply to UI
				var sentContainer = li.querySelector('.sent-replies');
				if (sentContainer) {
					var node = document.createElement('div');
					node.className = 'border rounded p-2 mb-1 bg-light';
					var when = data.reply && data.reply.created_at ? data.reply.created_at : new Date().toLocaleString();
					var who = data.reply && data.reply.murid_name ? data.reply.murid_name : 'Anda';
					node.innerHTML = '<div class="small text-muted">' + when + ' - ' + who + '</div><div>' + (data.reply.message || message) + '</div>';
					sentContainer.insertBefore(node, sentContainer.firstChild);
				}
				// clear and hide form
				ta.value = '';
				form.style.display = 'none';
			} else {
				alert((data && data.message) ? data.message : 'Gagal mengirim balasan');
			}
		}).catch(function(err){
			sendBtn.disabled = false;
			alert('Gagal mengirim balasan. Coba lagi.');
			console.error(err);
		});

		return;
	}
});

</script>

<script>
document.addEventListener('DOMContentLoaded', function(){
	var inboxPanelEl = document.getElementById('inboxPanel');
	var toggle = document.getElementById('inboxPanelToggle');
	if (inboxPanelEl && toggle && typeof bootstrap !== 'undefined') {
		inboxPanelEl.addEventListener('show.bs.collapse', function () {
			fetch('/murid/replies/mark-read', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'Accept': 'application/json',
					'X-CSRF-TOKEN': '{{ csrf_token() }}'
				},
				body: JSON.stringify({})
			}).then(function(res){ return res.json(); }).then(function(data){
				// reload page so history/pagination updates
				window.location.reload();
			}).catch(function(err){
				console.error('Gagal menandai notifikasi dibaca', err);
			});
		});
	}
});
</script>

@endsection