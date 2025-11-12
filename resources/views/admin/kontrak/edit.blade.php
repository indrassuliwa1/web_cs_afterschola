@extends('layouts.dashboard')

@section('title', 'Edit Kontrak')

@section('content')
    <div class="container mx-auto p-4 md:p-8 min-h-screen bg-gray-50">
        <div class="max-w-4xl mx-auto">

            {{-- Header dan Tombol Kembali --}}
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h1 class="text-3xl font-extrabold text-gray-800">
                    <i class="fas fa-edit text-blue-600 mr-2"></i> Edit Kontrak <span
                        class="text-blue-600">{{ $kontrak->nama_kontrak }}</span>
                </h1>
                <a href="{{ route('admin.kontrak') }}"
                    class="flex items-center bg-gray-500 hover:bg-gray-600 text-white font-semibold px-4 py-2 rounded-lg transition duration-200 shadow-md">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>

            {{-- Form Edit Kontrak (PENTING: Tambahkan enctype="multipart/form-data" untuk upload file) --}}
            <form action="{{ route('admin.kontrak.update', $kontrak->id) }}" method="POST" enctype="multipart/form-data"
                class="bg-white p-8 rounded-xl shadow-2xl space-y-6 border border-blue-100">
                @csrf
                @method('PUT')

                {{-- Menampilkan Error Validasi --}}
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Oops!</strong>
                        <span class="block sm:inline"> Ada beberapa kesalahan input:</span>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Grup Input Pendaftar --}}
                <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Data Penanggung Jawab</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Nama Pendaftar --}}
                    <div>
                        <label for="nama_pendaftar" class="block text-sm font-medium text-gray-700 mb-1">Nama Pendaftar
                            <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_pendaftar" id="nama_pendaftar"
                            value="{{ old('nama_pendaftar', $kontrak->pendaftar->nama) }}"
                            class="w-full border-gray-300 border rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                            required>
                    </div>

                    {{-- Email Pendaftar --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Pendaftar <span
                                class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email"
                            value="{{ old('email', $kontrak->pendaftar->email) }}"
                            class="w-full border-gray-300 border rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                            required>
                    </div>

                    {{-- No HP Pendaftar --}}
                    <div>
                        <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">No HP <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="no_hp" id="no_hp"
                            value="{{ old('no_hp', $kontrak->pendaftar->no_hp) }}"
                            class="w-full border-gray-300 border rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                            required>
                    </div>

                    {{-- Tipe Pendaftar --}}
                    <div>
                        <label for="tipe" class="block text-sm font-medium text-gray-700 mb-1">Tipe Pendaftar <span
                                class="text-red-500">*</span></label>
                        <select name="tipe" id="tipe"
                            class="w-full border-gray-300 border rounded-lg p-3 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                            required>
                            <option value="">-- Pilih Tipe --</option>
                            @php $tipe_lama = old('tipe', $kontrak->pendaftar->tipe); @endphp
                            <option value="guru" {{ $tipe_lama == 'guru' ? 'selected' : '' }}>Guru</option>
                            <option value="orangtua" {{ $tipe_lama == 'orangtua' ? 'selected' : '' }}>Orang Tua</option>
                            <option value="siswa" {{ $tipe_lama == 'siswa' ? 'selected' : '' }}>Siswa</option>
                        </select>
                    </div>

                </div>

                {{-- Alamat (Full Width) --}}
                <div>
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat Pendaftar <span
                            class="text-red-500">*</span></label>
                    {{-- Memuat data Alamat dari relasi Pendaftar --}}
                    <textarea name="alamat" id="alamat" rows="3"
                        class="w-full border-gray-300 border rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                        required>{{ old('alamat', $kontrak->pendaftar->alamat) }}</textarea>
                </div>

                <hr class="border-gray-200">
                <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Detail Kontrak</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Nama Kontrak --}}
                    <div>
                        <label for="nama_kontrak" class="block text-sm font-medium text-gray-700 mb-1">Nama Kontrak <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="nama_kontrak" id="nama_kontrak"
                            value="{{ old('nama_kontrak', $kontrak->nama_kontrak) }}"
                            class="w-full border-gray-300 border rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                            required>
                    </div>

                    {{-- Status Kontrak --}}
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span
                                class="text-red-500">*</span></label>
                        <select name="status" id="status"
                            class="w-full border-gray-300 border rounded-lg p-3 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                            required>
                            <option value="">-- Pilih Status --</option>
                            <option value="aktif" {{ old('status', $kontrak->status) == 'aktif' ? 'selected' : '' }}>Aktif
                            </option>
                            <option value="nonaktif" {{ old('status', $kontrak->status) == 'nonaktif' ? 'selected' : '' }}>
                                Nonaktif</option>
                        </select>
                    </div>

                    {{-- Kelas --}}
                    <div>
                        <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Kelas <span
                                class="text-red-500">*</span></label>
                        <select name="kelas_id" id="kelas_id"
                            class="w-full border-gray-300 border rounded-lg p-3 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                            required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($kelas as $k)
                                <option value="{{ $k->id }}" data-harga="{{ $k->harga }}"
                                    {{ old('kelas_id', $kontrak->kelas_id) == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }} (Harga: Rp. {{ number_format($k->harga, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Jumlah Peserta --}}
                    <div>
                        <label for="jumlah_peserta" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Peserta
                            <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah_peserta" id="jumlah_peserta"
                            value="{{ old('jumlah_peserta', $kontrak->jumlah_peserta) }}"
                            class="w-full border-gray-300 border rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                            required min="1">
                    </div>

                    {{-- ✅ Durasi Kontrak (Bulan) --}}
                    <div>
                        <label for="durasi_bulan" class="block text-sm font-medium text-gray-700 mb-1">Durasi Kontrak
                            (Bulan) <span class="text-red-500">*</span></label>
                        <input type="number" name="durasi_bulan" id="durasi_bulan" placeholder="Misal: 6"
                            min="1" value="{{ old('durasi_bulan', $kontrak->durasi_bulan) }}"
                            class="w-full border-gray-300 border rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                            required>
                    </div>

                    {{-- ✅ Total Harga Kontrak (READONLY) --}}
                    <div>
                        <label for="harga_display" class="block text-sm font-medium text-gray-700 mb-1">Total Harga
                            Kontrak <span class="text-red-500">*</span></label>
                        <input type="text" name="harga_display" id="harga_display" readonly
                            placeholder="Akan dihitung otomatis"
                            class="w-full border-gray-300 border rounded-lg p-3 bg-gray-100 focus:border-blue-500 transition duration-150 font-bold text-gray-700">
                        {{-- Hidden field untuk mengirim nilai numerik ke backend --}}
                        <input type="hidden" name="harga" id="harga_hidden"
                            value="{{ old('harga', $kontrak->harga) }}">
                    </div>

                    {{-- Tanggal Mulai --}}
                    <div>
                        <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai
                            <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                            value="{{ old('tanggal_mulai', $kontrak->tanggal_mulai) }}"
                            class="w-full border-gray-300 border rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                            required>
                    </div>

                    {{-- Tanggal Selesai --}}
                    <div>
                        <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai
                            <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                            value="{{ old('tanggal_selesai', $kontrak->tanggal_selesai) }}"
                            class="w-full border-gray-300 border rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                            required>
                    </div>
                </div>

                {{-- **BAGIAN UPLOAD FILE DATA PESERTA** --}}
                <div class="border-t pt-6 mt-6">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">File Data Peserta</h2>

                    {{-- File yang sudah ada --}}
                    @if ($kontrak->data_peserta_file)
                        <div class="mb-4 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                            <p class="text-sm font-medium text-gray-700 mb-2">File Saat Ini:</p>
                            <a href="{{ asset('uploads/data_peserta/' . $kontrak->data_peserta_file) }}" target="_blank"
                                class="text-blue-600 hover:text-blue-800 font-semibold flex items-center">
                                <i class="fas fa-file-alt mr-2"></i> {{ $kontrak->data_peserta_file }}
                            </a>
                            <p class="text-xs text-gray-500 mt-1">Unggah file baru di bawah jika Anda ingin menggantinya.
                            </p>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 mb-4 italic">Belum ada file data peserta diunggah untuk kontrak
                            ini.</p>
                    @endif

                    {{-- Input untuk unggah file baru (opsional) --}}
                    <label for="data_peserta_file" class="block text-sm font-medium text-gray-700 mb-1">Unggah File Baru
                        (Opsional)</label>
                    <input type="file" name="data_peserta_file" id="data_peserta_file"
                        accept=".pdf,.xlsx,.xls,.csv,.doc,.docx"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-gray-500 mt-1">Format: PDF, Excel, Word, CSV. Maks. 5MB. Kosongkan jika tidak
                        ingin mengubah.</p>
                    @error('data_peserta_file')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <hr class="border-gray-200">

                {{-- Tombol Simpan --}}
                <div class="pt-4 flex justify-end">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-3 rounded-lg shadow-lg transition duration-300 transform hover:scale-105">
                        <i class="fas fa-sync-alt mr-2"></i> Perbarui Kontrak
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
                // NOTE: Di form edit, data-harga ada di <option>, sama seperti di form create.
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
                    // Biarkan tanggal selesai sesuai nilai database jika hanya tanggal mulai yang berubah
                    // (tidak perlu dikosongkan di form edit kecuali jika tanggal yang baru tidak valid)
                }
            };

            const setTanggalSelesaiMin = (minDate) => {
                if (minDate) {
                    tanggalSelesaiInput.setAttribute('min', minDate);
                }
            };

            // --- LOGIKA UTAMA DURASI BERUBAH ---
            const handleDurationChange = () => {
                // NOTE: Di form edit, kita tidak memaksa tanggal mulai menjadi 'hari ini', 
                // tetapi kita pastikan minimum tanggal selesai konsisten.

                updateHarga(); // Update harga setiap ada perubahan pemicu

                const selectedMulaiDate = tanggalMulaiInput.value;
                const duration = parseInt(durasiInput.value);

                // 1. Atur tanggal mulai minimum (agar tidak mundur drastis dari hari ini)
                tanggalMulaiInput.setAttribute('min', getTodayDate());

                // 2. Hitung Tanggal Selesai
                if (selectedMulaiDate && duration > 0) {
                    calculateEndDate(selectedMulaiDate);
                } else {
                    setTanggalSelesaiMin(selectedMulaiDate || getTodayDate());
                }
            };

            // --- EVENT LISTENERS & INISIALISASI ---

            // A. Sinkronisasi Nama Kontrak (untuk form edit)
            // NOTE: Di form edit, kita biasanya TIDAK sinkronisasi nama, karena kontrak sudah ada. 
            // Saya hapus logika sinkronisasi pendaftar/kontrak, tetapi mempertahankan variabel.

            // B. Pemicu utama perhitungan
            kelasDropdown.addEventListener('change', handleDurationChange);
            durasiInput.addEventListener('input', handleDurationChange);
            jumlahPesertaInput.addEventListener('input', handleDurationChange);
            tanggalMulaiInput.addEventListener('change', handleDurationChange);

            // C. Jalankan inisialisasi pada saat load untuk memuat harga awal
            // Ini WAJIB dipanggil saat load agar harga display terisi dari nilai $kontrak->harga
            handleDurationChange();
        });
    </script>
@endsection
