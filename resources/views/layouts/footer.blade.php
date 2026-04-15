<footer class="bg-indigo text-white mt-4 py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold mb-3 text-white">Tentang Kami</h5>
                <p class="text-white-50">Sistem Ulasan dan Rating Sekolah adalah platform digital untuk mengumpulkan feedback dari murid tentang kualitas mengajar guru.</p>
            </div>
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold mb-3 text-white">Menu Utama</h5>
                <ul class="list-unstyled">
                    @if(session('role') == 'admin')
                        <li><a href="/admin/dashboard" class="text-white text-decoration-none">Dashboard Admin</a></li>
                        <li><a href="/admin/guru" class="text-white text-decoration-none">Kelola Guru</a></li>
                        <li><a href="/admin/jadwal" class="text-white text-decoration-none">Kelola Jadwal</a></li>
                        <li><a href="/admin/hasil-survey" class="text-white text-decoration-none">Hasil Sistem Ulasan dan Rating</a></li>
                    @elseif(session('role') == 'guru')
                        <li><a href="/guru/dashboard" class="text-white text-decoration-none">Dashboard Guru</a></li>
                        <li><a href="/guru/survey" class="text-white text-decoration-none">Sistem Ulasan dan Rating Diterima</a></li>
                        <li><a href="/guru/rating" class="text-white text-decoration-none">Rating</a></li>
                    @elseif(session('role') == 'murid')
                        <li><a href="/murid/dashboard" class="text-white text-decoration-none">Dashboard Murid</a></li>
                    @endif
                </ul>
            </div>
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold mb-3 text-white">Kontak</h5>
                <p class="text-white-50">
                    Email: info@survey-sekolah.com<br>
                    Telepon: (021) 1234-5678<br>
                    Alamat: Jl. Pendidikan No. 123, Jakarta
                </p>
            </div>
        </div>
        <hr style="border-color: rgba(255, 255, 255, 0.3);">
        <div class="text-center">
            <p class="mb-0 text-white-50">&copy; 2026 Sistem Ulasan dan Rating Sekolah. All rights reserved.</p>
        </div>
    </div>
</footer>