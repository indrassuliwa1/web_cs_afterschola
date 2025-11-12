@extends('layouts.app')
@section('content')
    <section class="hero-section" style="background-color: #f8f9fa;">
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
                        <img src="{{ asset('images/trainer.png') }}" alt="Trainer" class="img-fluid rounded-4 shadow-lg">
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
    <section class="section-padding animate-on-scroll" style="background-color: #f8f9fa;">
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
                                class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden berita-card text-decoration-none text-dark hover-shadow-lg"
                                style="transition: all 0.3s ease; display: block;">

                                {{-- KOREKSI PATH GAMBAR --}}
                                <img src="{{ asset('uploads/informasi/' . $item->gambar) }}"
                                    class="card-img-top object-fit-cover" alt="{{ $item->judul }}" style="height: 200px;">

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
    <section class="section-padding trainer-section animate-on-scroll">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Trainer</h2>
                <p class="text-muted">We have been working with some Fortune 500+ clients</p>
            </div>
            <div class="trainer-scroll-container">
                <div class="row g-4 flex-nowrap">
                    @forelse ($trainers as $trainer)
                        <div class="col-lg-3 col-md-4 col-sm-6 col-9">
                            <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                                <img src="{{ asset('uploads/trainer_photos/' . $trainer->foto) }}"
                                    class="card-img-top object-fit-cover" alt="{{ $trainer->nama }}"
                                    style="height: 300px;">
                                <div class="card-body text-center p-4">
                                    <h5 class="card-title fw-bold mb-1">
                                        {{ $trainer->nama }}</h5>
                                    <p class="card-text text-muted">
                                        {{ $trainer->spesialisasi }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-center text-muted">Belum ada data trainer.</p>
                        </div>
                    @endforelse
                    {{-- Duplikasi untuk animasi agar looping tanpa putus --}}
                    @foreach ($trainers as $trainer)
                        <div class="col-lg-3 col-md-4 col-sm-6 col-9">
                            <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                                <img src="{{ asset('uploads/trainer_photos/' . $trainer->foto) }}"
                                    class="card-img-top object-fit-cover" alt="{{ $trainer->nama }}"
                                    style="height: 300px;">
                                <div class="card-body text-center p-4">
                                    <h5 class="card-title fw-bold mb-1">
                                        {{ $trainer->nama }}</h5>
                                    <p class="card-text text-muted">
                                        {{ $trainer->spesialisasi }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    // ... (Bagian atas kode Anda) ...

    {{-- 3. Kelas Section --}}
    <section class="section-padding kelas-section animate-on-scroll">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Pilihan Kelas</h2>
                <p class="text-muted">We have been working with some Fortune 500+ clients</p>
            </div>
            <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3">
                @forelse ($kelasList as $kelas)
                    <div class="col">
                        <div
                            class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden {{ $loop->first ? 'highlighted' : '' }}">
                            <img src="{{ asset('uploads/kelas/' . $kelas->foto_kelas) }}"
                                class="card-img-top object-fit-cover" alt="{{ $kelas->nama_kelas }}"
                                style="height: 220px;">
                            <div class="card-body p-4 d-flex flex-column">
                                <h5 class="card-title fw-bold mb-2">
                                    {{ $kelas->nama_kelas }}</h5>
                                <p class="card-text text-muted small mb-3">
                                    {{ $kelas->deskripsi_singkat }}
                                </p>
                                <h6 class="fw-bold text-dark-blue mb-3">Rp
                                    {{ number_format($kelas->harga, 0, ',', '.') }}
                                </h6>
                                {{-- ✅ KODE YANG BENAR: Menggunakan ID, tanpa pengecekan slug --}}
                                <a href="{{ route('kelas.show', ['id' => $kelas->id]) }}"
                                    class="btn {{ $loop->first ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill px-4 mt-auto">
                                    Lihat Detail <i class="bi bi-arrow-right-short"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty <div class="col-12">
                        <p class="text-center text-muted">Belum ada kelas yang tersedia.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>


    {{-- 4. Prestasi Section --}}
    <section class="section-padding prestasi-section animate-on-scroll">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Prestasi</h2>
                <p class="text-muted">We have been working with some Fortune 500+ clients</p>
            </div>
            <div class="prestasi-scroll-container">
                <div class="row g-4 flex-nowrap">
                    @forelse ($prestasis as $prestasi)
                        <div class="col-lg-3 col-md-4 col-sm-6 col-9">
                            <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                                <img src="{{ asset('uploads/prestasi/' . $prestasi->bukti_foto) }}"
                                    class="card-img-top object-fit-cover" alt="{{ $prestasi->judul }}"
                                    style="height: 250px;">
                                <div class="card-body text-center p-3">
                                    <h6 class="card-title fw-bold mb-1">
                                        {{ $prestasi->judul }}</h6>
                                    <p class="card-text text-muted small">
                                        {{ $prestasi->deskripsi }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-center text-muted">Belum ada data prestasi.</p>
                        </div>
                    @endforelse
                    {{-- Duplikasi biar looping scroll halus --}}
                    @foreach ($prestasis as $prestasi)
                        <div class="col-lg-3 col-md-4 col-sm-6 col-9">
                            <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                                <img src="{{ asset('uploads/prestasi/' . $prestasi->bukti_foto) }}"
                                    class="card-img-top object-fit-cover" alt="{{ $prestasi->judul }}"
                                    style="height: 250px;">
                                <div class="card-body text-center p-3">
                                    <h6 class="card-title fw-bold mb-1">
                                        {{ $prestasi->judul }}</h6>
                                    <p class="card-text text-muted small">
                                        {{ $prestasi->deskripsi }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- 5. Kontak Section (Tinggalkan Balasan) --}}
    <section id="kontak" class="section-padding animate-on-scroll" style="background-color: #f8f9fa;">
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
@endsection
