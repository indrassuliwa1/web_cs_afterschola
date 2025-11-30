<nav id="mainNavbar" class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3 sticky-top">
    <div class="container">
        {{-- Logo: Klik logo kembali ke paling atas (#home) --}}
        <a class="navbar-brand" href="#home">
            <img src="{{ asset('images/logo.png') }}" alt="AfterSchola Logo" height="60">
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                
                {{-- 1. Link ke Section About --}}
                <li class="nav-item">
                    <a class="nav-link" href="#about">Kenapa Afterschola</a>
                </li>
                
                {{-- 2. Link ke Section Kelas --}}
                <li class="nav-item">
                    <a class="nav-link" href="#kelas">Kelas</a>
                </li>

                {{-- 3. Link ke Section Prestasi (Pastikan section prestasi punya id="prestasi") --}}
                <li class="nav-item">
                    <a class="nav-link" href="#prestasi">Prestasi</a>
                </li>

                {{-- 4. Link ke Section Trainer --}}
                <li class="nav-item">
                    <a class="nav-link" href="#trainer">Trainer</a>
                </li>
                
                {{-- 5. Link ke Section Kontak --}}
                <li class="nav-item">
                    <a class="nav-link" href="#kontak">Hubungi Kami</a>
                </li>

            </ul>
            
            {{-- Tombol Masuk / Register --}}
            <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4">
                Masuk <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</nav>