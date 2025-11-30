@extends('layouts.app')
@section('content')
    <section id="home" class="hero-section" style="background: linear-gradient(135deg, #e8f5e8 0%, #e3f2fd 50%, #d4edda 100%);">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6 animate-on-scroll">
                    <h1 class="display-3 fw-bold mb-3">
                        Lessons and insights from <span class="text-custom-yellow">8 years</span>
                    </h1>
                    <p class="lead text-muted mb-4">
                        Where to grow your business as a photographer: site or social media?
                    </p>
                    <a href="{{ url('/register') }}" class="btn btn-dark-blue btn-lg rounded-pill px-4">
                        Daftar Sekarang <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="hero-image-container">
                        {{-- Asumsi path statis, biarkan --}}
                        <img src="{{ asset('images/trainer2.jpeg') }}" alt="Trainer" class="img-fluid hero-image-float rounded-4 shadow-lg">
                        <div class="chat-bubble-1 shadow">
                            <i class="bi bi-heart-fill text-danger me-2"></i> Bagaimana Saya
                            Bergabung
                        </div>
                        <div class="chat-bubble-2 shadow">
                            <p class="mb-1">Tentu Saja Mari Kita</p>
                            <p class="mb-0">Bergabung Bersama After Schola <i class="bi bi-headset ms-1"></i></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- Kenapa AfterSchola Section --}}
    <section id="about" class="section-padding why-afterschola-section">
        <div class="container">
            <div class="text-center mb-5 animate-on-scroll">
                <h2 class="display-5 fw-bold text-dark mb-3">Kenapa Memilih AfterSchola?</h2>
                <p class="lead text-muted">Platform belajar terdepan dengan metode pembelajaran yang telah terbukti efektif
                </p>
            </div>

            <div class="row g-4">
                {{-- Feature 1 --}}
                <div class="col-md-6 col-lg-3 animate-on-scroll">
                    <div class="feature-card text-center p-4 rounded-4 h-100">
                        <div class="feature-icon mx-auto mb-4">
                            <i class="bi bi-people-fill display-4 text-custom-yellow"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Trainer Berpengalaman</h4>
                        <p class="text-muted mb-0">Dibimbing oleh profesional dengan pengalaman lebih dari 8 tahun di
                            industri</p>
                    </div>
                </div>

                {{-- Feature 2 --}}
                <div class="col-md-6 col-lg-3 animate-on-scroll">
                    <div class="feature-card text-center p-4 rounded-4 h-100">
                        <div class="feature-icon mx-auto mb-4">
                            <i class="bi bi-laptop display-4 text-custom-yellow"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Kurikulum Update</h4>
                        <p class="text-muted mb-0">Materi selalu diperbarui mengikuti perkembangan teknologi terbaru</p>
                    </div>
                </div>

                {{-- Feature 3 --}}
                <div class="col-md-6 col-lg-3 animate-on-scroll">
                    <div class="feature-card text-center p-4 rounded-4 h-100">
                        <div class="feature-icon mx-auto mb-4">
                            <i class="bi bi-briefcase-fill display-4 text-custom-yellow"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Portfolio Nyata</h4>
                        <p class="text-muted mb-0">Bangun portfolio profesional yang siap untuk dunia kerja</p>
                    </div>
                </div>

                {{-- Feature 4 --}}
                <div class="col-md-6 col-lg-3 animate-on-scroll">
                    <div class="feature-card text-center p-4 rounded-4 h-100">
                        <div class="feature-icon mx-auto mb-4">
                            <i class="bi bi-award-fill display-4 text-custom-yellow"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Sertifikasi</h4>
                        <p class="text-muted mb-0">Dapatkan sertifikat resmi yang diakui oleh industri</p>
                    </div>
                </div>
            </div>

            {{-- Stats Section --}}
            <div class="row mt-5 pt-5 animate-on-scroll">
                <div class="col-12">
                    <div class="bg-white rounded-4 shadow-sm p-5">
                        <div class="row text-center">

                            <div class="col-md-3 col-6 mb-4 mb-md-0">
                                <div class="stat-number display-6 fw-bold text-dark-blue counter" data-target="500">0</div>
                                <div class="stat-label text-muted">Siswa Aktif</div>
                            </div>

                            <div class="col-md-3 col-6 mb-4 mb-md-0">
                                <div class="stat-number display-6 fw-bold text-dark-blue counter" data-target="50">0</div>
                                <div class="stat-label text-muted">Kelas Tersedia</div>
                            </div>

                            <div class="col-md-3 col-6">
                                <div class="stat-number display-6 fw-bold text-dark-blue counter" data-target="98">0</div>
                                <div class="stat-label text-muted">Kepuasan Siswa</div>
                            </div>

                            <div class="col-md-3 col-6">
                                <div class="stat-number display-6 fw-bold text-dark-blue counter" data-target="8">0</div>
                                <div class="stat-label text-muted">Tahun Pengalaman</div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- Floating Chatbot Button --}}
    <div id="chatbot-btn"
        style="position: fixed; bottom: 25px; right: 25px; background-color: #f9b233;
border-radius: 50%; width: 60px; height: 60px; display: flex; justify-content: center;
align-items: center; cursor: pointer; box-shadow: 0 4px 12px rgba(0,0,0,0.3); z-index: 999;
transition: transform 0.3s ease, box-shadow 0.3s ease;">
        <i class="bi bi-robot text-white fs-3"></i>
    </div>

    {{-- Chatbot Popup --}}
    <div id="chatbot-popup"
        style="display: none; position: fixed; bottom: 100px; right: 25px; width: 320px; height: 420px;
backdrop-filter: blur(10px); background: rgba(255,255,255,0.85); border-radius: 15px;
box-shadow: 0 4px 20px rgba(0,0,0,0.3); overflow: hidden; flex-direction: column;
z-index: 1000; font-family: 'Poppins', sans-serif; transform: scale(0.7);
opacity: 0; transition: all 0.3s ease;">
        <div
            style="background-color: #002147; color: white; padding: 12px 15px; font-weight: 600;
display: flex; justify-content: space-between; align-items: center;">
            <span>Chatbot 🤖</span>
            <button id="close-chatbot"
                style="background:none; border:none; color:white; font-size:20px; cursor:pointer;">×</button>
        </div>
        <div id="chatbot-messages"
            style="flex: 1; padding: 12px; overflow-y: auto; font-size: 14px; display: flex; flex-direction: column;">
            <div class="bot-bubble">Halo! Ada yang bisa saya bantu hari ini? 😊</div>
        </div>
        <div style="display: flex; border-top: 1px solid #ddd; background: rgba(255,255,255,0.7);">
            <input id="chatbot-input" type="text" placeholder="Ketik pesan..."
                style="flex: 1; padding: 10px; border: none; outline: none; font-size: 14px; background: transparent;">
            <button id="chatbot-send"
                style="background-color: #f9b233; border: none; padding: 0 20px; color: white; font-weight: bold; cursor: pointer; border-radius: 0 12px 12px 0;">
                ➤
            </button>
        </div>
    </div>

    {{-- 1.5. Berita Umum Section --}}
    <section id="berita" class="section-padding animate-on-scroll" style="background: rgba(248, 249, 250, 0.8);">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Berita & Informasi</h2>
                <p class="text-muted">Update terbaru seputar teknologi dan AfterSchola.</p>
            </div>
            <div class="row g-4">
                @forelse ($beritas as $item)
                    {{-- Menggunakan ID sebagai patokan untuk mencegah error SLUG/NULL --}}
                    @if ($item->id)
                        <div class="col-md-4">
                            {{-- Tautan KRITIS: Menggunakan ID ($item->id) sebagai parameter --}}
                            <a href="{{ route('berita.show', $item->id) }}"
                                class="card h-100 berita-card text-decoration-none text-dark rounded-4 overflow-hidden">

                                {{-- KOREKSI PATH GAMBAR --}}
                                <img src="{{ asset('uploads/informasi/' . $item->gambar) }}"
                                    class="card-img-top object-fit-cover" alt="{{ $item->judul }}"
                                    style="height: 200px;">

                                <div class="card-body p-4 d-flex flex-column">
                                    <div class="berita-meta mb-2 small text-muted">
                                        <span><i class="bi bi-person-fill"></i>
                                            {{ $item->author->name ?? 'Admin' }}</span> |
                                        <span><i class="bi bi-calendar-event-fill"></i>

                                            {{ \Carbon\Carbon::parse($item->tanggal ?? $item->created_at)->format('d M Y') }}</span>
                                    </div>

                                    <h5 class="card-title fw-bold mb-2">{{ $item->judul }}
                                    </h5>
                                    <p class="card-text text-muted small mb-3">
                                        {{ $item->ringkasan }}
                                    </p>

                                    {{-- Indikator "Baca Selengkapnya" --}}
                                    <div class="text-primary fw-bold mt-auto align-self-start">
                                        Baca Selengkapnya <i class="bi bi-arrow-right-short"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif
                @empty
                    <div class="col-12">
                        <p class="text-center text-muted">Belum ada berita untuk ditampilkan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>


    {{-- 2. Trainer Section --}}
   <section id="trainer" class="section-padding trainer-section animate-on-scroll">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-dark">Trainer</h2>
            <p class="text-muted">We have been working with some Fortune 500+ clients</p>
        </div>
        <div class="trainer-scroll-container">
            <div class="row g-4 flex-nowrap">
                
                {{-- LOOP 1: DATA ASLI --}}
                @forelse ($trainers as $trainer)
                    <div class="col-lg-3 col-md-4 col-sm-6 col-9">
                        <div class="card h-100 shadow-sm rounded-4 overflow-hidden">
                            <div class="trainer-image-container">
                                {{-- UPDATE DISINI: Tambah onclick dan atribut data- --}}
                                <img src="{{ asset('uploads/trainer_photos/' . $trainer->foto) }}"
                                     class="card-img-top object-fit-cover trainer-image cursor-pointer" 
                                     alt="{{ $trainer->nama }}"
                                     
                                     {{-- Data untuk dikirim ke Popup --}}
                                     data-name="{{ $trainer->nama }}"
                                     data-role="{{ $trainer->spesialisasi }}"
                                     data-img="{{ asset('uploads/trainer_photos/' . $trainer->foto) }}"
                                     {{-- Pakai text default jika deskripsi kosong --}}
                                     data-desc="{{ $trainer->deskripsi ?? 'Trainer profesional berpengalaman di bidangnya.' }}" 
                                     
                                     {{-- Fungsi Pemicu saat diklik --}}
                                     onclick="showTrainerPopup(this)">
                            </div>
                            <div class="card-body text-center p-4">
                                <h5 class="card-title fw-bold mb-1 text-dark">{{ $trainer->nama }}</h5>
                                <p class="card-text text-muted">{{ $trainer->spesialisasi }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center text-muted">Belum ada data trainer.</p>
                    </div>
                @endforelse

                {{-- LOOP 2: DUPLIKASI UNTUK ANIMASI SCROLL (Wajib diupdate juga) --}}
                @foreach ($trainers as $trainer)
                    <div class="col-lg-3 col-md-4 col-sm-6 col-9">
                        <div class="card h-100 shadow-sm rounded-4 overflow-hidden">
                            <div class="trainer-image-container">
                                {{-- UPDATE BAGIAN INI JUGA --}}
                                <img src="{{ asset('uploads/trainer_photos/' . $trainer->foto) }}"
                                     class="card-img-top object-fit-cover trainer-image cursor-pointer" 
                                     alt="{{ $trainer->nama }}"
                                     data-name="{{ $trainer->nama }}"
                                     data-role="{{ $trainer->spesialisasi }}"
                                     data-img="{{ asset('uploads/trainer_photos/' . $trainer->foto) }}"
                                     data-desc="{{ $trainer->deskripsi ?? 'Trainer profesional berpengalaman di bidangnya.' }}" 
                                     onclick="showTrainerPopup(this)">
                            </div>
                            <div class="card-body text-center p-4">
                                <h5 class="card-title fw-bold mb-1 text-dark">{{ $trainer->nama }}</h5>
                                <p class="card-text text-muted">{{ $trainer->spesialisasi }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>


    {{-- 3. Kelas Section --}}
    {{-- 3. Kelas Section --}}
    <section id="kelas" class="section-padding kelas-section animate-on-scroll">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-dark">Pilihan Kelas</h2>
                <p class="text-muted">Temukan kelas yang sesuai dengan minat dan bakat Anda</p>
            </div>

            {{-- Scroll Controls --}}
            <div class="position-relative">
                <button class="kelas-scroll-btn kelas-scroll-prev" aria-label="Previous">
                    <i class="bi bi-chevron-left"></i>
                </button>

                <div class="kelas-scroll-container">
                    <div class="row g-3 flex-nowrap"> {{-- g-3 untuk gap lebih kecil --}}
                        @forelse ($kelasList as $kelas)
                            <div class="col-lg-4 col-md-5 col-sm-6 col-8"> {{-- Column size diperkecil --}}
                                <div class="card h-100 shadow-sm rounded-3 overflow-hidden kelas-card">
                                    {{-- rounded-3 lebih kecil --}}
                                    <img src="{{ asset('uploads/kelas/' . $kelas->foto_kelas) }}"
                                        class="card-img-top object-fit-cover" alt="{{ $kelas->nama_kelas }}"
                                        style="height: 160px;"> {{-- Image height diperkecil --}}
                                    <div class="card-body p-3 d-flex flex-column"> {{-- Padding diperkecil --}}
                                        <h6 class="card-title fw-bold mb-2 text-dark"> {{-- h6 bukan h5 --}}
                                            {{ $kelas->nama_kelas }}
                                        </h6>
                                        <p class="card-text text-muted small mb-2 line-clamp-2"> {{-- Text lebih kecil --}}
                                            {{ Str::limit($kelas->deskripsi_singkat, 80) }}
                                        </p>
                                        <h6 class="fw-bold text-dark-blue mb-2 fs-6"> {{-- Font size lebih kecil --}}
                                            Rp {{ number_format($kelas->harga, 0, ',', '.') }}
                                        </h6>
                                        <a href="{{ route('kelas.show', ['id' => $kelas->id]) }}"
                                            class="btn btn-outline-primary btn-sm rounded-pill px-3 mt-auto">
                                            {{-- Button lebih kecil --}}
                                            Lihat Detail <i class="bi bi-arrow-right-short"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-center text-muted">Belum ada kelas yang tersedia.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <button class="kelas-scroll-btn kelas-scroll-next" aria-label="Next">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
    </section>


    <section id="prestasi" class="section-padding prestasi-section animate-on-scroll">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Prestasi</h2>
            <p class="text-muted">Pencapaian membanggakan dari siswa kami</p>
        </div>
        <div class="prestasi-scroll-container">
            <div class="row g-4 flex-nowrap">
                
                {{-- LOOP 1: DATA UTAMA --}}
                @forelse ($prestasis as $prestasi)
                    <div class="col-lg-3 col-md-4 col-sm-6 col-9">
                        {{-- LINK KE HALAMAN DETAIL --}}
                        <a href="{{ route('prestasi.show', $prestasi->id) }}" class="text-decoration-none">
                            
                            <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                                <img src="{{ asset('uploads/prestasi/' . $prestasi->bukti_foto) }}"
                                     class="card-img-top object-fit-cover" alt="{{ $prestasi->judul }}"
                                     style="height: 250px;">
                                
                                <div class="card-body text-center p-3 d-flex flex-column">
                                    <h6 class="card-title fw-bold mb-1 text-dark">
                                        {{ $prestasi->judul }}
                                    </h6>
                                    
                                    {{-- DESKRIPSI SINGKAT (Dibatasi 50 karakter & Hapus tag HTML) --}}
                                    <p class="card-text text-muted small mb-2">
                                        {{ Str::limit(strip_tags($prestasi->deskripsi), 50) }}
                                    </p>
                                    
                                    {{-- TOMBOL LIHAT DETAIL --}}
                                    <div class="mt-auto">
                                        <small class="text-primary fw-bold">
                                            Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                                        </small>
                                    </div>
                                </div>
                            </div>

                        </a>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center text-muted">Belum ada data prestasi.</p>
                    </div>
                @endforelse

                {{-- LOOP 2: DUPLIKASI UNTUK ANIMASI SCROLL --}}
                @foreach ($prestasis as $prestasi)
                    <div class="col-lg-3 col-md-4 col-sm-6 col-9">
                        <a href="{{ route('prestasi.show', $prestasi->id) }}" class="text-decoration-none">
                            <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                                <img src="{{ asset('uploads/prestasi/' . $prestasi->bukti_foto) }}"
                                     class="card-img-top object-fit-cover" alt="{{ $prestasi->judul }}"
                                     style="height: 250px;">
                                
                                <div class="card-body text-center p-3 d-flex flex-column">
                                    <h6 class="card-title fw-bold mb-1 text-dark">
                                        {{ $prestasi->judul }}
                                    </h6>
                                    <p class="card-text text-muted small mb-2">
                                        {{ Str::limit(strip_tags($prestasi->deskripsi), 50) }}
                                    </p>
                                    <div class="mt-auto">
                                        <small class="text-primary fw-bold">
                                            Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</section>

    {{-- 5. Kontak Section (Tinggalkan Balasan) --}}
    <section id="kontak" class="section-padding animate-on-scroll" style="background: rgba(248, 249, 250, 0.9);">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Tinggalkan Balasan</h2>
                <p class="text-muted">Punya pertanyaan atau masukan? Kami siap mendengar!</p>
            </div>

            <div class="row justify-content-center align-items-center">

                {{-- Ilustrasi --}}
                <div class="col-md-5 d-none d-md-block">
                    <img src="{{ asset('images/hubungikami.png') }}" alt="Ilustrasi Kontak" class="img-fluid">
                </div>

                {{-- Kolom Formulir --}}
                <div class="col-md-7 col-lg-6">
                    <div class="bg-white rounded-4 border p-4 p-md-5 form-panel shadow-lg">

                        {{-- Pesan Sukses (Gunakan session 'success_contact' yang benar) --}}
                        @if (session('success_contact'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success_contact') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- Formulir Utama --}}
                        <form id="contactForm" action="{{ route('contact.store') }}" method="POST"
                            class="contact-form form-fade-in">

                            {{-- WAJIB: Token CSRF untuk keamanan --}}
                            @csrf

                            <div class="mb-3">
                                <label for="contactName" class="form-label fw-bold">Nama Anda</label>
                                <input type="text"
                                    class="form-control form-control-lg rounded-3 @error('contactName') is-invalid @enderror"
                                    id="contactName" name="contactName" value="{{ old('contactName') }}"
                                    placeholder="Masukan nama Anda" required>

                                @error('contactName')
                                    <div class="invalid-feedback d-block fw-bold">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="contactEmail" class="form-label fw-bold">Email Anda</label>
                                <input type="email"
                                    class="form-control form-control-lg rounded-3 @error('contactEmail') is-invalid @enderror"
                                    id="contactEmail" name="contactEmail" value="{{ old('contactEmail') }}"
                                    placeholder="nama@email.com" required>

                                @error('contactEmail')
                                    <div class="invalid-feedback d-block fw-bold">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="contactComment" class="form-label fw-bold">Komentar Anda</label>
                                <textarea class="form-control form-control-lg rounded-3 @error('contactComment') is-invalid @enderror"
                                    id="contactComment" name="contactComment" rows="5" placeholder="Tulis komentar atau pertanyaan Anda..."
                                    required>{{ old('contactComment') }}</textarea>

                                @error('contactComment')
                                    <div class="invalid-feedback d-block fw-bold">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid pt-2">
                                <button type="submit" class="btn btn-dark-blue btn-lg rounded-pill px-5 shadow-sm">
                                    Kirim Pesan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- STYLES AND SCRIPTS (TIDAK DIHILANGKAN UNTUK KEAMANAN) --}}
    <style>
        /* ... CSS yang Anda miliki ... */
        .user-bubble {
            align-self: flex-end;
            background: #f9b233;
            color: #000;
            padding: 8px 12px;
            border-radius: 15px 15px 0 15px;
            margin: 5px 0;
            max-width: 80%;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
            animation: fadeIn 0.3s ease;
        }

        .bot-bubble {
            align-self: flex-start;
            background: #f1f0f0;
            color: #000;
            padding: 8px 12px;
            border-radius: 15px 15px 15px 0;
            margin: 5px 0;
            max-width: 80%;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
            animation: fadeIn 0.3s ease;
        }

        /* Efek tombol */
        #chatbot-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.4);
        }

        /* Animasi pesan muncul */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Animasi buka chatbot */
        .show-popup {
            display: flex !important;
            opacity: 1 !important;
            transform: scale(1) !important;
        }

        /* Animasi tutup chatbot */
        .hide-popup {
            opacity: 0 !important;
            transform: scale(0.7) !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatBtn = document.getElementById('chatbot-btn');
            const chatPopup = document.getElementById('chatbot-popup');
            const closeChat = document.getElementById('close-chatbot');
            const sendBtn = document.getElementById('chatbot-send');
            const input = document.getElementById('chatbot-input');
            const messages = document.getElementById('chatbot-messages');

            // Fungsi animasi buka popup
            chatBtn.addEventListener('click', () => {
                chatPopup.classList.remove('hide-popup');
                chatPopup.classList.add('show-popup');
            });

            // Fungsi animasi tutup popup
            closeChat.addEventListener('click', () => {
                chatPopup.classList.add('hide-popup');
                setTimeout(() => {
                    chatPopup.classList.remove('show-popup');
                    chatPopup.style.display = 'none';
                }, 300);
            });

            // Saat buka pertama kali tampilkan
            const observer = new MutationObserver(() => {
                if (chatPopup.classList.contains('show-popup')) {
                    chatPopup.style.display = 'flex';
                }
            });
            observer.observe(chatPopup, {
                attributes: true
            });

            // Fungsi kirim pesan
            function sendMessage() {
                const text = input.value.trim();
                if (text === '') return;
                const userMsg = document.createElement('div');
                userMsg.classList.add('user-bubble');
                userMsg.textContent = text;
                messages.appendChild(userMsg);
                input.value = '';
                messages.scrollTop = messages.scrollHeight;
                fetch('/chatbot', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            // Menggunakan CSRF token dari global untuk AJAX chatbot
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            message: text
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        const botMsg = document.createElement('div');
                        botMsg.classList.add('bot-bubble');
                        botMsg.textContent = data.reply;
                        messages.appendChild(botMsg);
                        messages.scrollTop = messages.scrollHeight;
                    })
                    .catch(() => {
                        const botMsg = document.createElement('div');
                        botMsg.classList.add('bot-bubble');
                        botMsg.textContent = 'Terjadi kesalahan server 😞';
                        messages.appendChild(botMsg);
                    });
            }
            sendBtn.addEventListener('click', sendMessage);
            input.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') sendMessage();
            });
        });
    </script>

    {{-- MODAL POPUP TRAINER DINAMIS --}}
<div class="modal fade" id="trainerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-blue-modal border-0">
            
            {{-- Tombol Tutup (X) --}}
            <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 z-3" data-bs-dismiss="modal" aria-label="Close"></button>
            
            <div class="modal-body p-0 text-center overflow-hidden position-relative">
                {{-- Hiasan Background Blob --}}
                <div class="blob-modal"></div>
                
                <div class="position-relative z-2 p-4">
                    {{-- Foto Trainer --}}
                    <div class="mb-4 mt-3">
                        <img id="modalTrainerImg" src="" alt="Trainer" 
                             class="rounded-circle shadow-lg object-fit-cover border-white-glass" 
                             style="width: 140px; height: 140px;">
                    </div>
                    
                    {{-- Nama & Spesialisasi --}}
                    <h3 id="modalTrainerName" class="fw-bold text-dark-blue mb-1"></h3>
                    <span id="modalTrainerRole" class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 mb-4"></span>
                    
                    {{-- Kotak Deskripsi --}}
                    <div class="glass-inset p-3 rounded-4 text-start">
                        <h6 class="fw-bold text-muted mb-2"><i class="bi bi-info-circle me-2"></i>Tentang Trainer</h6>
                        <p id="modalTrainerDesc" class="text-muted small mb-0" style="line-height: 1.6;">
                    </div>

                    {{-- Tombol Tutup Bawah --}}
                    <div class="mt-4">
                         <button type="button" class="btn btn-primary rounded-pill px-5 w-100 shadow-sm" data-bs-dismiss="modal">
                            Tutup
                         </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
