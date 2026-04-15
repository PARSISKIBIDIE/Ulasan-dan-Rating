@extends('layouts.app')

@section('content')

<div class="container my-5">
	<div class="row justify-content-center">
		<div class="col-lg-8">
			<div class="card border-0 shadow-lg rounded-4 overflow-hidden">
				<div class="card-header d-flex align-items-center justify-content-between py-4 px-4 bg-indigo text-white">
					<div>
							<h3 class="fw-bold mb-1">Isi Sistem Ulasan dan Rating</h3>
						<div class="small text-muted">Guru: <strong>{{ $jadwal->guru->user->name }}</strong> • Mapel: <strong>{{ $jadwal->mapel }}</strong></div>
					</div>
					<div class="text-end">
						@if(isset($existingSurvey) && $existingSurvey)
							<div class="star-readonly" aria-hidden="true">
								@for($s=1;$s<=5;$s++)
									@if($s <= $existingSurvey->rating)
											<span class="me-1 text-warning" style="font-size:22px;">★</span>
									@else
										<span class="me-1" style="color:#ddd;font-size:22px;">★</span>
									@endif
								@endfor
							</div>
						@endif
					</div>
				</div>

				<div class="card-body p-4">

					@if(isset($existingSurvey) && $existingSurvey)
						<div class="alert alert-success">Rating dan ulasan sudah diberikan.</div>

						<div class="mb-3">
							<label class="form-label fw-semibold">Rating</label>
							<div class="mb-2">
								@for($s=1;$s<=5;$s++)
									@if($s <= $existingSurvey->rating)
										<span class="text-indigo" style="font-size:26px;">★</span>
									@else
										<span style="color:#ddd;font-size:26px;">★</span>
									@endif
								@endfor
							</div>
						</div>

						<div class="mb-4">
							<label class="form-label">Komentar</label>
							<textarea class="form-control form-control-lg" rows="4" disabled>{{ $existingSurvey->komentar }}</textarea>
						</div>

						<a href="/murid/dashboard" class="btn btn-outline-secondary">Kembali</a>

					@else

						<form action="/survey/store" method="POST">
							@csrf
							<input type="hidden" name="guru_id" value="{{ $jadwal->guru_id }}">
							<input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">

							<div class="row align-items-center mb-4">
								<div class="col-md-7">
									<label class="form-label fw-semibold">Komentar</label>
									<textarea id="komentarField" name="komentar" class="form-control form-control-lg" rows="4" placeholder="Berikan pertanyaan, saran, atau masukan..." required style="resize:vertical;"></textarea>
									<div class="mt-2">
										<div class="small text-muted mb-1">Saran cepat:</div>
										<div class="d-flex flex-wrap gap-2">
											<button type="button" class="btn btn-outline-secondary btn-sm suggestion-btn">Terima kasih, Bapak</button>
											<button type="button" class="btn btn-outline-secondary btn-sm suggestion-btn">Terima kasih, Ibu</button>
											<button type="button" class="btn btn-outline-secondary btn-sm suggestion-btn">Terima kasih atas pelajarannya</button>
											<button type="button" class="btn btn-outline-secondary btn-sm suggestion-btn">Bisa lebih dijelaskan lagi materinya</button>
											<button type="button" class="btn btn-outline-secondary btn-sm suggestion-btn">Terima kasih atas bimbingannya</button>
										</div>
									</div>
								</div>
								<div class="col-md-5 text-md-end mt-3 mt-md-0">
									<label class="form-label fw-semibold d-block">Rating</label>
									<div class="d-inline-block star-rating" title="Pilih rating" data-rating-input-name="rating">
										<input type="radio" name="rating" value="1" id="star1"><label for="star1" title="Buruk">★</label>
										<input type="radio" name="rating" value="2" id="star2"><label for="star2" title="Kurang">★</label>
										<input type="radio" name="rating" value="3" id="star3"><label for="star3" title="Cukup">★</label>
										<input type="radio" name="rating" value="4" id="star4"><label for="star4" title="Baik">★</label>
										<input type="radio" name="rating" value="5" id="star5"><label for="star5" title="Sangat Baik">★</label>
									</div>
									<div class="mt-2 small text-muted">Pilih dengan klik bintang</div>
								</div>
							</div>

							<div class="d-flex justify-content-between">
								<a href="/murid/dashboard" class="btn btn-outline-secondary">Batal</a>
								<button class="btn btn-indigo px-4">Kirim Ulasan</button>
							</div>
						</form>

					@endif

				</div>
			</div>
		</div>
	</div>
</div>

<style>
/* Modern survey form styles */
.card-header h3 { letter-spacing: .2px; }
.card { background: #ffffff; }
.star-rating{ display:flex; flex-direction: row; gap:6px; font-size:34px; align-items:center; justify-content:center; }
.star-rating input{ display:none; }
.tar .star-rating label{ user-select:none; }
.star-rating label{ color:#ddd; cursor:pointer; transition: transform .08s ease, color .12s ease; user-select:none; }
	.star-rating label.active{ color: var(--color-indigo); transform: translateY(-4px); }
	.star-rating label.hover{ color: var(--color-indigo); transform: translateY(-4px); }
.form-control-lg { border-radius:8px; }
		.btn-indigo { background: var(--color-indigo); border-color: var(--color-indigo); color: #fff; }
	.btn-outline-secondary { border-radius:6px; }

	/* suggestion styles */
	.suggestion-btn { border-radius:20px; padding:.25rem .6rem; border-color: var(--color-indigo); color: var(--color-indigo); background:#fff; }
	.suggestion-btn:hover { background:#eef2ff; color: var(--color-indigo-dark); }
	.suggestion-btn:focus { box-shadow: 0 0 0 0.1rem rgba(79,70,229,0.12); outline:none; }

	/* small responsive tweaks */
	@media (max-width: 576px){ .star-rating{ font-size:28px; } }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
	document.querySelectorAll('.star-rating').forEach(function(starWrap) {
		const labels = Array.from(starWrap.querySelectorAll('label'));
		const inputs = Array.from(starWrap.querySelectorAll('input[type="radio"]'));

		function updateActiveByValue(val) {
			labels.forEach(function(lbl){
				const input = document.getElementById(lbl.htmlFor);
				if (!input) return;
				if (Number(input.value) <= Number(val)) {
					lbl.classList.add('active');
				} else {
					lbl.classList.remove('active');
				}
			});
		}

		// initialize from checked input (if any)
		const checked = inputs.find(i => i.checked);
		if (checked) updateActiveByValue(checked.value);

		labels.forEach(function(lbl){
			const input = document.getElementById(lbl.htmlFor);
			if (!input) return;
			lbl.addEventListener('click', function(e){
				input.checked = true;
				// trigger change event
				const ev = new Event('change', { bubbles: true });
				input.dispatchEvent(ev);
				updateActiveByValue(input.value);
			});

			lbl.addEventListener('mouseenter', function(){
				const val = document.getElementById(lbl.htmlFor).value;
				labels.forEach(function(l){
					const i = document.getElementById(l.htmlFor);
					if (i && Number(i.value) <= Number(val)) l.classList.add('hover');
					else l.classList.remove('hover');
				});
			});

			lbl.addEventListener('mouseleave', function(){
				labels.forEach(function(l){ l.classList.remove('hover'); });
			});
		});

		// handle keyboard/radio changes
		inputs.forEach(function(inp){
			inp.addEventListener('change', function(){
				updateActiveByValue(this.value);
			});
		});

			// suggestion buttons to quickly fill komentar
			document.querySelectorAll('.suggestion-btn').forEach(function(btn){
				btn.addEventListener('click', function(){
					var ta = document.getElementById('komentarField');
					if(!ta) return;
					var text = btn.textContent.trim();
					if(ta.value.trim() === '') ta.value = text;
					else {
						if(ta.value.slice(-1) !== ' ' && ta.value.slice(-1) !== '\n') ta.value = ta.value + ' ';
						ta.value += text;
					}
					ta.focus();
					ta.selectionStart = ta.selectionEnd = ta.value.length;
				});
			});
	});
});
</script>

@endsection