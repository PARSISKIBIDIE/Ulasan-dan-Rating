<nav class="navbar navbar-expand-lg shadow-sm"
     style="background: linear-gradient(90deg, #ff9a3c, #ff7a00);">

    <div class="container-fluid">

        <a class="navbar-brand fw-bold text-white" href="/">
            Sistem Ulasan dan Rating
        </a>

        <button class="navbar-toggler border-0" 
                type="button" 
                data-bs-toggle="collapse" 
                data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav me-auto">

                @if(session('role') == 'admin')
                    <li class="nav-item">
                        <a class="nav-link text-white fw-semibold" href="/admin/dashboard">
                            Dashboard
                        </a>
                    </li>
                @endif

                @if(session('role') == 'guru')
                    <li class="nav-item">
                        <a class="nav-link text-white fw-semibold" href="/guru/dashboard">
                            Dashboard
                        </a>
                    </li>
                @endif

                @if(session('role') == 'murid')
                    <li class="nav-item">
                        <a class="nav-link text-white fw-semibold" href="/murid/dashboard">
                            Dashboard
                        </a>
                    </li>
                @endif

            </ul>

            @if(session()->has('user_id'))
                <span class="text-white me-3 fw-semibold">
                    {{ session('name') }} ({{ session('role') }})
                </span>

                <a href="/logout" 
                   class="btn btn-light btn-sm fw-semibold rounded-3">
                   Logout
                </a>
            @endif

        </div>
    </div>
</nav>