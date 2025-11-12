@extends('layouts.app')

@section('content')
    <section class="py-5 form-background-section" style="background: linear-gradient(135deg, #f0f6ff 0%, #ffffff 100%);">
        <div class="container">

            {{-- Header & Progress Indicator --}}
            <div class="text-center mb-5">
                <h1 class="fw-bold text-primary display-6 mb-2">Formulir Pendaftaran Kelas</h1>
                <p class="text-muted fs-5">Langkah 1 dari 2: Lengkapi Data Diri & Pesanan</p>

                {{-- Indikator Langkah (Visual yang Profesional) --}}
                <div class="d-flex justify-content-center mt-3">
                    <div class="step-indicator active me-3 p-2 border-bottom border-primary border-4 fw-bold text-primary">
                        1. Data Pendaftar
                    </div>
                    <div class="step-indicator p-2 border-bottom border-light border-4 text-muted">
                        2. Konfirmasi Pembayaran
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden form-panel">
                        <div class="card-body p-5">

                            @if ($errors->any())
                                <div class="alert alert-danger rounded-3">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- FORM START --}}
                            <form id="paymentForm" action="{{ route('register.step1.store') }}" method="POST"
                                class="form-fade-in">
                                @csrf

                                {{-- 1. GROUP DATA DIRI --}}
                                <h4 class="fw-bold text-dark mb-4 border-bottom pb-2"><i
                                        class="bi bi-person-lines-fill me-2"></i> Data Penanggung Jawab</h4>

                                {{-- NAMA PENANGGUNG JAWAB (nama_pendaftar) --}}
                                <div class="mb-4">
                                    <label for="nama_pendaftar" class="form-label fw-semibold">Nama Penanggung Jawab /
                                        Sekolah</label>
                                    <input type="text" id="nama_pendaftar" name="nama_pendaftar"
                                        class="form-control form-control-lg rounded-3 shadow-sm @error('nama_pendaftar') is-invalid @enderror"
                                        value="{{ old('nama_pendaftar') }}" placeholder="Cth: Budi Santoso / SMA Negeri 1"
                                        required>
                                    @error('nama_pendaftar')
                                        <div class="invalid-feedback d-block fw-bold">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    {{-- EMAIL --}}
                                    <div class="col-md-6 mb-4">
                                        <label for="email" class="form-label fw-semibold">Email</label>
                                        <input type="email" id="email" name="email"
                                            class="form-control form-control-lg rounded-3 shadow-sm @error('email') is-invalid @enderror"
                                            value="{{ old('email') }}" placeholder="Cth: budi@email.com" required>
                                        @error('email')
                                            <div class="invalid-feedback d-block fw-bold">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- NO WHATSAPP (no_hp) --}}
                                    <div class="col-md-6 mb-4">
                                        <label for="no_hp" class="form-label fw-semibold">No WhatsApp (Aktif)</label>
                                        <input type="tel" id="no_hp" name="no_hp"
                                            class="form-control form-control-lg rounded-3 shadow-sm @error('no_hp') is-invalid @enderror"
                                            value="{{ old('no_hp') }}" placeholder="Cth: 081234567890" required>
                                        @error('no_hp')
                                            <div class="invalid-feedback d-block fw-bold">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- TIPE PENDAFTAR (tipe) --}}
                                <div class="mb-4">
                                    <label for="tipe" class="form-label fw-semibold">Tipe Pendaftar</label>
                                    <select name="tipe" id="tipe"
                                        class="form-select form-select-lg rounded-3 shadow-sm @error('tipe') is-invalid @enderror"
                                        required>
                                        <option value="" disabled selected>-- Pilih Tipe --</option>
                                        <option value="guru" {{ old('tipe') == 'guru' ? 'selected' : '' }}>Guru</option>
                                        <option value="orangtua" {{ old('tipe') == 'orangtua' ? 'selected' : '' }}>Orang Tua
                                        </option>
                                        <option value="siswa" {{ old('tipe') == 'siswa' ? 'selected' : '' }}>Siswa
                                        </option>
                                    </select>
                                    @error('tipe')
                                        <div class="invalid-feedback d-block fw-bold">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Alamat --}}
                                <div class="mb-5">
                                    <label for="alamat" class="form-label fw-semibold">Alamat Lengkap</label>
                                    <textarea id="alamat" name="alamat" rows="3"
                                        class="form-control form-control-lg rounded-3 shadow-sm @error('alamat') is-invalid @enderror"
                                        placeholder="Masukkan Alamat Lengkap" required>{{ old('alamat') }}</textarea>
                                    @error('alamat')
                                        <div class="invalid-feedback d-block fw-bold">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- 2. GROUP DATA PESANAN --}}
                                <h4 class="fw-bold text-dark mb-4 border-bottom pb-2"><i
                                        class="bi bi-cart-check-fill me-2"></i> Detail Pesanan Kelas</h4>

                                <div class="row">
                                    {{-- KELAS YANG DIAMBIL (kelas_id) --}}
                                    <div class="col-md-6 mb-4">
                                        <label for="kelas_id" class="form-label fw-semibold">Pilih Kelas</label>
                                        <select id="kelas_id" name="kelas_id"
                                            class="form-select form-select-lg rounded-3 shadow-sm @error('kelas_id') is-invalid @enderror"
                                            required>
                                            <option value="" disabled selected>-- Pilih Kelas --</option>
                                            @isset($kelasList)
                                                @foreach ($kelasList as $kelas)
                                                    <option value="{{ $kelas->id }}" data-harga="{{ $kelas->harga }}"
                                                        {{ old('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                                        {{ $kelas->nama_kelas }} (Rp
                                                        {{ number_format($kelas->harga, 0, ',', '.') }})
                                                    </option>
                                                @endforeach
                                            @else
                                                <option value="" disabled>Error: Data kelas tidak tersedia.</option>
                                            @endisset
                                        </select>
                                        @error('kelas_id')
                                            <div class="invalid-feedback d-block fw-bold">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- JUMLAH PESERTA (jumlah_peserta) --}}
                                    <div class="col-md-6 mb-4">
                                        <label for="jumlah_peserta" class="form-label fw-semibold">Jumlah Peserta</label>
                                        <input type="number" id="jumlah_peserta" name="jumlah_peserta"
                                            class="form-control form-control-lg rounded-3 shadow-sm @error('jumlah_peserta') is-invalid @enderror"
                                            value="{{ old('jumlah_peserta') ?? 1 }}" placeholder="Cth: 10" required
                                            min="1">
                                        @error('jumlah_peserta')
                                            <div class="invalid-feedback d-block fw-bold">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- FIELD DURASI KONTRAK (durasi_bulan) --}}
                                    <div class="col-md-6 mb-4">
                                        <label for="durasi_bulan" class="form-label fw-semibold">Durasi Kontrak
                                            (Bulan)</label>
                                        <input type="number" id="durasi_bulan" name="durasi_bulan"
                                            class="form-control form-control-lg rounded-3 shadow-sm @error('durasi_bulan') is-invalid @enderror"
                                            value="{{ old('durasi_bulan') ?? 6 }}" placeholder="Cth: 6" required
                                            min="1">
                                        @error('durasi_bulan')
                                            <div class="invalid-feedback d-block fw-bold">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Kosongkan kolom untuk menjaga grid --}}
                                    <div class="col-md-6 mb-4"></div>
                                </div>

                                {{-- 🚨 HIDDEN FIELDS UNTUK TANGGAL MULAI DAN SELESAI --}}
                                <input type="hidden" name="tanggal_mulai" id="tanggal_mulai"
                                    value="{{ old('tanggal_mulai') }}" required>
                                <input type="hidden" name="tanggal_selesai" id="tanggal_selesai"
                                    value="{{ old('tanggal_selesai') }}" required>
                                {{-- ------------------------------------------------ --}}


                                {{-- HARGA KELAS (Dihitung Otomatis - Total) --}}
                                <div class="mb-5 p-3 bg-light rounded-3 border">
                                    <label for="harga" class="form-label fw-bold text-dark mb-2">Total Biaya
                                        Kontrak</label>
                                    <div class="input-group input-group-lg shadow-sm">
                                        <span
                                            class="input-group-text bg-primary text-white fw-bold border-0 rounded-start-3">Rp</span>
                                        <input type="number" id="harga" name="harga"
                                            class="form-control rounded-end-3 fs-5 @error('harga') is-invalid @enderror"
                                            value="{{ old('harga') }}" placeholder="Harga Otomatis Terisi" readonly
                                            required>
                                    </div>
                                    @error('harga')
                                        <div class="invalid-feedback d-block fw-bold">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text mt-2 text-muted">Total harga ini wajib diisi, dihitung dari
                                        Harga/Bulan x Jumlah Peserta x Durasi Bulan.</div>
                                </div>

                                {{-- Tombol Aksi --}}
                                <div class="d-flex justify-content-end gap-2 pt-3">
                                    <a href="{{ url('/') }}"
                                        class="btn btn-outline-secondary btn-lg rounded-pill px-4">
                                        Batal
                                    </a>
                                    <button type="submit" id="lanjutButton"
                                        class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm">
                                        Lanjut ke Pembayaran <i class="bi bi-arrow-right-short"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Footer Info --}}
                    <div class="text-center mt-4">
                        <small class="text-muted">AfterSchola © {{ date('Y') }}. Semua hak cipta dilindungi.</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const kelasDropdown = document.getElementById('kelas_id');
            const hargaInput = document.getElementById('harga');
            const jumlahPesertaInput = document.getElementById('jumlah_peserta');

            // Elemen untuk durasi dan tanggal
            const durasiInput = document.getElementById('durasi_bulan');
            const tanggalMulaiInput = document.getElementById('tanggal_mulai');
            const tanggalSelesaiInput = document.getElementById('tanggal_selesai');

            // --- UTILITIES ---
            // Mendapatkan tanggal hari ini dalam format YYYY-MM-DD
            const getTodayDate = () => {
                const today = new Date();
                const year = today.getFullYear();
                const month = String(today.getMonth() + 1).padStart(2, '0');
                const day = String(today.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            };

            // Fungsi untuk menghitung Tanggal Selesai dan mengisi hidden field
            const calculateDates = () => {
                // Gunakan nilai durasi dari input, default ke 6 jika kosong/tidak valid
                const duration = parseInt(durasiInput.value) || 6;
                const todayDate = getTodayDate();

                // 1. Set Tanggal Mulai (hidden field) ke Hari Ini
                tanggalMulaiInput.value = todayDate;

                if (duration > 0) {
                    // Parsing tanggal sebagai UTC untuk konsistensi
                    const startDate = new Date(todayDate + 'T00:00:00');

                    const endDate = new Date(startDate);
                    endDate.setMonth(endDate.getMonth() + duration);

                    // Kurangi satu hari untuk tanggal berakhir yang tepat
                    endDate.setDate(endDate.getDate() - 1);

                    const year = endDate.getFullYear();
                    const month = String(endDate.getMonth() + 1).padStart(2, '0');
                    const day = String(endDate.getDate()).padStart(2, '0');

                    // 2. Set Tanggal Selesai (hidden field)
                    tanggalSelesaiInput.value = `${year}-${month}-${day}`;

                } else {
                    // Jika durasi tidak valid, gunakan tanggal hari ini untuk tanggal selesai juga
                    tanggalSelesaiInput.value = todayDate;
                }
            };

            // ✅ FUNGSI HARGA BARU (Harga Dasar * Peserta * Durasi)
            function updateHarga() {
                const selectedOption = kelasDropdown.options[kelasDropdown.selectedIndex];

                if (!selectedOption || selectedOption.value === "") {
                    hargaInput.value = 0;
                    return;
                }

                const hargaDasar = parseFloat(selectedOption.getAttribute('data-harga')) || 0;
                const jumlahPeserta = parseInt(jumlahPesertaInput.value) || 1;
                // Ambil durasi, pastikan minimal 1 agar perhitungan tidak 0
                const durasiBulan = parseInt(durasiInput.value) || 1;

                // LOGIKA BARU: Harga Dasar * Jumlah Peserta * Durasi Bulan
                const totalHarga = hargaDasar * jumlahPeserta * durasiBulan;

                hargaInput.value = totalHarga;
            }

            // --- EVENT LISTENERS ---

            // Pemicu 1: Kelas, Peserta, atau Durasi berubah -> Hitung Harga dan Tanggal
            kelasDropdown.addEventListener('change', updateHarga);
            jumlahPesertaInput.addEventListener('input', updateHarga);

            // ✅ TAMBAH EVENT LISTENER UNTUK DURASI AGAR HARGA BERUBAH
            durasiInput.addEventListener('input', function() {
                updateHarga();
                calculateDates(); // Juga hitung ulang tanggal saat durasi berubah
            });

            // --- INISIALISASI AKHIR ---
            updateHarga();
            calculateDates(); // PASTIKAN TANGGAL DIHITUNG TERAKHIR KALI SAAT LOAD
        });
    </script>
@endsection
