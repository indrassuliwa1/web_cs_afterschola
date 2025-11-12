<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AfterSchola - Landing Page</title> {{-- Ganti title jika perlu --}}

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        xintegrity="sha384-QWTQh1Y1JjA3yW21RewAhxjanvaUoSkVZF3P5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>

<body>

    <!-- Header -->
    @include('layouts.partials.header')

    <!-- Konten Utama -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('layouts.partials.footer')

    <!-- ================================ -->
    <!-- == MODALS & FLOATING BUTTON == -->
    <!-- ================================ -->

    <!-- 1. Floating Chat Bubble (Trigger Modal Kontak) -->
    <!-- 1. Floating Chat Bubble (Hanya tampil di Homepage) -->
    

    <

    <!-- 3. Modal Konfirmasi Kirim Pesan (Kode Anda sudah benar) -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg">
                <div class="modal-body p-4 text-center">
                    <i class="bi bi-check-circle-fill text-success mb-3" style="font-size: 3rem;"></i>
                    <h5 class="modal-title mb-3" id="confirmationModalLabel">Pesan Terkirim!</h5>
                    <p class="text-muted small">Terima kasih telah menghubungi kami. Kami akan segera merespon pesan
                        Anda.</p>
                    <button type="button" class="btn btn-dark-blue rounded-pill px-4 mt-2"
                        data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <!-- == END MODALS == -->


    <!-- ================================ -->
    <!-- == Scripts == -->
    <!-- ================================ -->

    <!-- Bootstrap JS Bundle (Harus sebelum script lain) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <!-- Tempat untuk script kustom dari halaman lain (misal: format rupiah, tombol trainer) -->
    @stack('scripts')

    <!-- Script Navbar Blur [DIKEMBALIKAN] -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navbar = document.getElementById('mainNavbar');
            if (navbar) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 50) {
                        navbar.classList.add('navbar-scrolled');
                    } else {
                        navbar.classList.remove('navbar-scrolled');
                    }
                });
            }
        });
    </script>

    <!-- Script Modal Konfirmasi Sukses (Kode Anda sudah benar) -->
    @if (session('success_contact'))
        <script>
            document.addEventListener('DOMContentLoaded', (event) => {
                var confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
                confirmationModal.show();
            });
        </script>
    @endif

    <!-- Script Animasi Scroll (Kode Anda sudah benar) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1 // Picu saat 10% terlihat
            });
            const targets = document.querySelectorAll('.animate-on-scroll');
            targets.forEach(target => {
                observer.observe(target);
            });
        });
    </script>

    {{-- AKHIR SCRIPT ANIMASI SCROLL --}}

</body>

</html>
