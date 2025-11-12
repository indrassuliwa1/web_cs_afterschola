<nav id="mainNavbar" class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3 sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="{{ asset('images/logo.png') }}" alt="AfterSchola Logo" height="60">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown1" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Kenapa Afterschola
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown1">
                        <li><a class="dropdown-item" href="#">Tentang Kami</a></li>
                        <li><a class="dropdown-item" href="#">Visi & Misi</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Kelas
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown2">
                        <li><a class="dropdown-item" href="#">Kelas A</a></li>
                        <li><a class="dropdown-item" href="#">Kelas B</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown2" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        About
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown2">
                        <li><a class="dropdown-item" href="#">Kelas A</a></li>
                        <li><a class="dropdown-item" href="#">Kelas B</a></li>
                    </ul>
                </li>
            </ul>
            {{-- BAGIAN YANG DIKOREKSI --}}
            <a href="{{ route('login') }}" class="btn btn-dark-blue rounded-pill px-4">
                Masuk <i class="bi bi-arrow-right"></i>
            </a>
            {{-- END KOREKSI --}}
        </div>
    </div>
</nav>