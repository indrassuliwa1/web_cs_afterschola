@extends('layouts.dashboard')

@section('title', 'Tambah Kontrak')
@section('page', 'Tambah Kontrak Baru')

@section('content')
    <div class="container mx-auto p-4 md:p-8 min-h-screen bg-gray-50">
        <div class="max-w-4xl mx-auto">

            {{-- Header dan Tombol Kembali --}}
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h1 class="text-3xl font-extrabold text-gray-800">
                    <i class="fas fa-file-contract text-green-600 mr-2"></i> Tambah Kontrak Baru
                </h1>
                <a href="{{ route('admin.kontrak') }}"
                    class="flex items-center bg-gray-500 hover:bg-gray-600 text-white font-semibold px-4 py-2 rounded-lg transition duration-200 shadow-md">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>

            {{-- Form Tambah Kontrak (PENTING: Tambahkan enctype="multipart/form-data") --}}
            <form action="{{ route('admin.kontrak.store') }}" method="POST" enctype="multipart/form-data"
                class="bg-white p-8 rounded-xl shadow-2xl space-y-6 border border-green-100">
                @csrf

                {{-- Menampilkan Error Validasi --}}
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Perhatian!</strong>
                        <span class="block sm:inline"> Silakan perbaiki kesalahan berikut:</span>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Data Penanggung Jawab --}}
                <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Data Penanggung Jawab</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Nama Pendaftar --}}
                    <div>
                        <label for="nama_pendaftar" class="block text-sm font-medium text-gray-700 mb-1">Nama Pendaftar
                            <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_pendaftar" id="nama_pendaftar"
                            placeholder="Masukkan nama penanggung jawab"
                            class="w-full border-gray-300 border rounded-lg p-3 focus:ring-green-500 focus:border-green-500 transition duration-150"
                            value="{{ old('nama_pendaftar') }}" required>
                    </div>

                    {{-- Email Pendaftar --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Pendaftar <span
                                class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" placeholder="Contoh: nama@domain.com"
                            class="w-full border-gray-300 border rounded-lg p-3 focus:ring-green-500 focus:border-green-500 transition duration-150"
                            value="{{ old('email') }}" required>
                    </div>

                    {{-- No HP --}}
                    <div>
                        <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">No HP <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="no_hp" id="no_hp" placeholder="Contoh: 081234..."
                            class="w-full border-gray-300 border rounded-lg p-3 focus:ring-green-500 focus:border-green-500 transition duration-150"
                            value="{{ old('no_hp') }}" required>
                    </div>

                    {{-- Tipe Pendaftar --}}
                    <div>
                        <label for="tipe" class="block text-sm font-medium text-gray-700 mb-1">Tipe Pendaftar <span
                                class="text-red-500">*</span></label>
                        <select name="tipe" id="tipe"
                            class="w-full border-gray-300 border rounded-lg p-3 bg-gray-50 focus:ring-green-500 focus:border-green-500 transition duration-150"
                            required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="guru" {{ old('tipe') == 'guru' ? 'selected' : '' }}>Guru</option>
                            <option value="orangtua" {{ old('tipe') == 'orangtua' ? 'selected' : '' }}>Orang Tua</option>
                            <option value="siswa" {{ old('tipe') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                        </select>
                    </div>
                </div>

                {{-- Alamat (Full Width) --}}
                <div>
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat Pendaftar <span
                            class="text-red-500">*</span></label>
                    <textarea name="alamat" id="alamat" rows="2" placeholder="Masukkan alamat lengkap pendaftar"
                        class="w-full border-gray-300 border rounded-lg p-3 focus:ring-green-500 focus:border-green-500 transition duration-150"
                        required>{{ old('alamat') }}</textarea>
                </div>

                <hr class="border-gray-200">
                <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Detail Kontrak</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Nama Kontrak (Visible Input) --}}
                    <div>
                        <label for="nama_kontrak" class="block text-sm font-medium text-gray-700 mb-1">Nama Kontrak <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="nama_kontrak" id="nama_kontrak"
                            placeholder="Contoh: Kelas Bimbingan SMA 1"
                            class="w-full border-gray-300 border rounded-lg p-3 focus:ring-green-500 focus:border-green-500 transition duration-150"
                            value="{{ old('nama_kontrak') }}" required>
                    </div>

                    {{-- Pilih Kelas --}}
                    <div>
                        <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Kelas <span
                                class="text-red-500">*</span></label>
                        <select name="kelas_id" id="kelas_id"
                            class="w-full border-gray-300 border rounded-lg p-3 bg-gray-50 focus:ring-green-500 focus:border-green-500 transition duration-150"
                            required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($kelas as $k)
                                <option value="{{ $k->id }}" data-harga="{{ $k->harga }}"
                                    {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }} (Rp. {{ number_format($k->harga, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Jumlah Peserta --}}
                    <div>
                        <label for="jumlah_peserta" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Peserta
                            <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah_peserta" id="jumlah_peserta" placeholder="Minimal 1"
                            class="w-full border-gray-300 border rounded-lg p-3 focus:ring-green-500 focus:border-green-500 transition duration-150"
                            value="{{ old('jumlah_peserta') ?? 1 }}" required min="1">
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status Kontrak <span
                                class="text-red-500">*</span></label>
                        <select name="status" id="status"
                            class="w-full border-gray-300 border rounded-lg p-3 bg-gray-50 focus:ring-green-500 focus:border-green-500 transition duration-150"
                            required>
                            <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>

                    {{-- Durasi Kontrak (Bulan) --}}
                    <div>
                        <label for="durasi_bulan" class="block text-sm font-medium text-gray-700 mb-1">Durasi Kontrak
                            (Bulan) <span class="text-red-500">*</span></label>
                        <input type="number" name="durasi_bulan" id="durasi_bulan" placeholder="Misal: 6"
                            min="1"
                            class="w-full border-gray-300 border rounded-lg p-3 focus:ring-green-500 focus:border-green-500 transition duration-150"
                            value="{{ old('durasi_bulan') ?? 6 }}" required>
                    </div>

                    {{-- ✅ FIELD BARU: Total Harga Kontrak (READONLY) --}}
                    <div>
                        <label for="harga" class="block text-sm font-medium text-gray-700 mb-1">Total Harga Kontrak
                            <span class="text-red-500">*</span></label>
                        <input type="text" name="harga_display" id="harga_display" readonly
                            placeholder="Akan dihitung otomatis"
                            class="w-full border-gray-300 border rounded-lg p-3 bg-gray-100 focus:border-green-500 transition duration-150 font-bold text-gray-700">
                        {{-- Hidden field untuk mengirim nilai numerik ke backend --}}
                        <input type="hidden" name="harga" id="harga_hidden" value="{{ old('harga') }}">
                    </div>

                    {{-- Tanggal Mulai --}}
                    <div>
                        <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai
                            <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                            class="w-full border-gray-300 border rounded-lg p-3 focus:ring-green-500 focus:border-green-500 transition duration-150"
                            value="{{ old('tanggal_mulai') }}" required>
                    </div>

                    {{-- Tanggal Selesai --}}
                    <div>
                        <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai
                            <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                            class="w-full border-gray-300 border rounded-lg p-3 focus:ring-green-500 focus:border-green-500 transition duration-150"
                            value="{{ old('tanggal_selesai') }}" required>
                    </div>

                    {{-- FIELD BARU: Data Peserta File (Full Width) --}}
                    <div class="md:col-span-2 border-t pt-4">
                        <label for="data_peserta_file" class="block text-sm font-medium text-gray-700 mb-1">Upload Data
                            Peserta (PDF, Excel, dll.)</label>
                        <input type="file" name="data_peserta_file" id="data_peserta_file"
                            accept=".pdf,.xlsx,.xls,.csv,.doc,.docx"
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-xs text-gray-500 mt-1">Format: PDF, Excel (xlsx, xls), CSV, Word (doc, docx). Maks.
                            5MB.</p>
                        @error('data_peserta_file')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="pt-4 flex justify-end space-x-3 border-t mt-6">
                    <a href="{{ route('admin.kontrak') }}"
                        class="bg-gray-400 hover:bg-gray-500 text-white font-bold px-8 py-3 rounded-lg shadow-lg transition duration-300 transform hover:scale-105 flex items-center">
                        <i class="fas fa-times-circle mr-2"></i> Batal
                    </a>
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold px-8 py-3 rounded-lg shadow-lg transition duration-300 transform hover:scale-105">
                        <i class="fas fa-save mr-2"></i> Simpan Kontrak
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const namaPendaftarInput = document.getElementById('nama_pendaftar');
            const namaKontrakInput = document.getElementById('nama_kontrak');

            // Kontrak/Harga Elemen
            const kelasDropdown = document.getElementById('kelas_id');
            const durasiInput = document.getElementById('durasi_bulan');
            const jumlahPesertaInput = document.getElementById('jumlah_peserta');
            const hargaDisplay = document.getElementById('harga_display');
            const hargaHidden = document.getElementById('harga_hidden');

            // Tanggal Elemen
            const tanggalMulaiInput = document.getElementById('tanggal_mulai');
            const tanggalSelesaiInput = document.getElementById('tanggal_selesai');

            // --- UTILITIES ---
            const getTodayDate = () => {
                const today = new Date();
                const year = today.getFullYear();
                const month = String(today.getMonth() + 1).padStart(2, '0');
                const day = String(today.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            };

            const formatRupiah = (number) => {
                return 'Rp ' + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            };

            // --- LOGIKA HARGA BARU ---
            const updateHarga = () => {
                const selectedOption = kelasDropdown.options[kelasDropdown.selectedIndex];

                if (!selectedOption || selectedOption.value === "") {
                    hargaDisplay.value = 'Rp 0';
                    hargaHidden.value = 0;
                    return;
                }

                // Ambil harga dasar per bulan dari data-harga
                const hargaDasarPerBulan = parseFloat(selectedOption.getAttribute('data-harga')) || 0;

                const jumlahPeserta = parseInt(jumlahPesertaInput.value) || 1;
                const durasiBulan = parseInt(durasiInput.value) || 1;

                // Rumus: Harga Dasar x Jumlah Peserta x Durasi Bulan
                const totalHarga = hargaDasarPerBulan * jumlahPeserta * durasiBulan;

                hargaDisplay.value = formatRupiah(totalHarga);
                hargaHidden.value = totalHarga; // Simpan nilai numerik untuk backend
            };

            // --- LOGIKA TANGGAL ---
            const calculateEndDate = (startDateString) => {
                const duration = parseInt(durasiInput.value);

                if (startDateString && duration > 0) {
                    const startDate = new Date(startDateString + 'T00:00:00');
                    const endDate = new Date(startDate);
                    endDate.setMonth(endDate.getMonth() + duration);
                    endDate.setDate(endDate.getDate() - 1); // Kurangi satu hari

                    const year = endDate.getFullYear();
                    const month = String(endDate.getMonth() + 1).padStart(2, '0');
                    const day = String(endDate.getDate()).padStart(2, '0');

                    tanggalSelesaiInput.value = `${year}-${month}-${day}`;
                    setTanggalSelesaiMin(startDateString);

                } else if (startDateString) {
                    setTanggalSelesaiMin(startDateString);
                    tanggalSelesaiInput.value = '';
                }
            };

            const setTanggalSelesaiMin = (minDate) => {
                if (minDate) {
                    tanggalSelesaiInput.setAttribute('min', minDate);
                }
            };

            // --- LOGIKA UTAMA DURASI BERUBAH ---
            const handleDurationChange = () => {
                const duration = parseInt(durasiInput.value);
                const todayDate = getTodayDate();

                updateHarga(); // Update harga setiap durasi berubah

                // 1. Atur Tanggal Mulai minimum hari ini
                tanggalMulaiInput.setAttribute('min', todayDate);

                // 2. Jika durasi valid (>0)
                if (duration > 0) {
                    // Jika tanggal mulai kosong, isi otomatis ke hari ini
                    if (!tanggalMulaiInput.value || tanggalMulaiInput.value < todayDate) {
                        tanggalMulaiInput.value = todayDate;
                    }

                    // 3. Hitung Tanggal Selesai
                    calculateEndDate(tanggalMulaiInput.value);
                } else {
                    // Jika durasi tidak valid, kosongkan tanggal selesai dan atur min date
                    tanggalSelesaiInput.value = '';
                    setTanggalSelesaiMin(tanggalMulaiInput.value || todayDate);
                }
            };

            // --- EVENT LISTENERS & INISIALISASI ---

            // A. Sinkronisasi Nama Kontrak (Kode Anda Sebelumnya)
            namaPendaftarInput.oldValue = namaPendaftarInput.value;
            if (!namaKontrakInput.value && namaPendaftarInput.value) {
                namaKontrakInput.value = namaPendaftarInput.value;
            }
            namaPendaftarInput.addEventListener('input', function() {
                if (namaKontrakInput.value === '' || namaKontrakInput.value === this.oldValue || !
                    namaKontrakInput.oldValue) {
                    namaKontrakInput.value = this.value;
                }
                this.oldValue = this.value;
            });

            // B. Pemicu utama perhitungan
            kelasDropdown.addEventListener('change', handleDurationChange);
            durasiInput.addEventListener('input', handleDurationChange);
            jumlahPesertaInput.addEventListener('input', handleDurationChange);
            tanggalMulaiInput.addEventListener('change', handleDurationChange); // Tanggal mulai berubah

            // C. Jalankan inisialisasi pada saat load
            handleDurationChange();
        });
    </script>
@endsection
