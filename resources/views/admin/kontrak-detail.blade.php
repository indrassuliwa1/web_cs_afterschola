@extends('layouts.dashboard')

@section('title', 'Detail Kontrak')

@section('content')
    <div class="container mx-auto p-4 md:p-8 min-h-screen bg-gray-50">
        <div class="max-w-5xl mx-auto">

            {{-- Header dan Tombol Aksi --}}
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h1 class="text-3xl font-extrabold text-gray-800">
                    <i class="fas fa-file-alt text-blue-600 mr-2"></i> Detail Kontrak
                    {{-- Mengubah nama_kontrak menjadi warna biru --}}
                    <span class="text-blue-600 font-bold">{{ $kontrak->nama_kontrak }}</span>
                </h1>
                <div class="flex space-x-3">

                    {{-- Tombol Cetak Data (TETAP DIPERTAHANKAN) --}}
                    <a href="{{ route('admin.kontrak.print', $kontrak->id) }}" target="_blank"
                        class="bg-gray-600 hover:bg-gray-700 text-white font-semibold px-4 py-2 rounded-lg transition duration-200 shadow-md">
                        <i class="fas fa-print mr-2"></i> Cetak Data
                    </a>

                    {{-- Tombol Kembali --}}
                    <a href="{{ route('admin.kontrak') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-4 py-2 rounded-lg transition duration-200 shadow-md">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>

            {{-- Konten Utama --}}
            <div>

                {{-- ✅ PERHITUNGAN HARGA DIBERSIHKAN --}}
                @php
                    // Pastikan $totalTagihan, $totalBayarMasuk, dan $sisaTagihan DIKIRIM dari Controller.

                    // --- LOGIKA BARU: HITUNG DURASI BULAN SECARA AKURAT DARI TANGGAL ---
                    try {
                        $start = \Carbon\Carbon::parse($kontrak->tanggal_mulai);
                        $end = \Carbon\Carbon::parse($kontrak->tanggal_selesai);
                        // Hitung selisih bulan. Menambahkan 1 hari agar 02 Nov - 30 Nov dianggap 1 bulan
                        // Atau jika kontrak persis 1 tahun (12 bulan)
                        $diffInDays = $start->diffInDays($end);

                        if ($diffInDays <= 31) {
                            $durasiKontrak = 1; // Jika kurang dari sebulan penuh, anggap 1 bulan
                        } else {
                            // Jika lebih dari sebulan, hitung selisih bulan dan bulatkan ke atas
                            $durasiKontrak = $start->diffInMonths($end) + 1;
                            if ($durasiKontrak > 1 && $end->day < $start->day) {
                                // Penyesuaian jika tanggal akhir lebih kecil dari tanggal awal (misal 5 Jan - 4 Feb)
                                $durasiKontrak -= 1;
                            }
                        }
                    } catch (\Exception $e) {
                        $durasiKontrak = $kontrak->durasi_bulan ?? 1; // Fallback jika tanggal tidak valid
                    }
                    // Kita gunakan nilai durasi yang DIBULATKAN ke atas untuk perhitungan harga
                    $durasi = max(1, (int) round($durasiKontrak));
                    // ------------------------------------------------------------------

                    $peserta = $kontrak->jumlah_peserta ?? 1;

                    // Kita menggunakan $totalTagihan yang DIKIRIM dari Controller.
                    // Asumsi: $totalTagihan sudah mencakup harga total kontrak.
                    $hargaPerUnit =
                        $totalTagihan ?? 0 > 0 && $peserta > 0 && $durasi > 0
                            ? ($totalTagihan ?? 0) / $peserta / $durasi
                            : 0;

                @endphp
                {{-- ----------------------------- --}}

                {{-- RINGKASAN KEUANGAN ATAS --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    {{-- Total Tagihan --}}
                    <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-blue-500">
                        <p class="text-sm text-gray-500">Total Tagihan Kontrak</p>
                        <h3 class="text-2xl font-bold text-blue-600">Rp {{ number_format($totalTagihan ?? 0, 0, ',', '.') }}
                        </h3>

                        {{-- ✅ DESKRIPSI PERHITUNGAN --}}
                        <div class="text-xs text-gray-500 mt-2 space-y-0.5">
                            <div class="flex justify-between border-b pb-1">
                                <span>Harga Satuan/Bulan:</span>
                                {{-- Menggunakan hargaPerUnit yang dihitung dari totalTagihan yang DIHARAPKAN dari Controller --}}
                                <strong class="font-semibold">Rp
                                    {{ number_format($hargaPerUnit, 0, ',', '.') }}</strong>
                            </div>
                            <div class="flex justify-between">
                                <span>Total Perhitungan:</span>
                                <strong class="font-semibold">{{ $peserta }} Peserta x {{ $durasi }}
                                    Bulan</strong>
                            </div>
                        </div>
                        {{-- ------------------------- --}}
                    </div>
                    {{-- Bayar Masuk --}}
                    <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-green-500">
                        <p class="text-sm text-gray-500">Total Bayar Masuk</p>
                        {{-- Menggunakan $totalBayarMasuk yang DIKIRIM dari Controller --}}
                        <h3 class="text-2xl font-bold text-green-600">Rp
                            {{ number_format($totalBayarMasuk ?? 0, 0, ',', '.') }}
                        </h3>
                    </div>
                    {{-- Sisa Tagihan --}}
                    <div
                        class="bg-white p-6 rounded-xl shadow-md border-l-4 
                    @if (($sisaTagihan ?? 1) <= 0) border-green-500 @else border-red-500 @endif">
                        <p class="text-sm text-gray-500">
                            @if (($sisaTagihan ?? 1) <= 0)
                                Status: LUNAS
                            @else
                                Sisa Tagihan
                            @endif
                        </p>
                        {{-- Menggunakan $sisaTagihan yang DIKIRIM dari Controller --}}
                        <h3
                            class="text-2xl font-bold @if (($sisaTagihan ?? 1) <= 0) text-green-600 @else text-red-600 @endif">
                            Rp {{ number_format(max(0, $sisaTagihan ?? 0), 0, ',', '.') }}
                        </h3>
                    </div>
                </div>

                {{-- Kartu Detail Kontrak (Kontrak, Pendaftar, Jadwal, FILE PESERTA) --}}
                <div class="bg-white rounded-xl shadow-2xl overflow-hidden border border-blue-100 mb-8">
                    <div class="bg-blue-600 text-white p-4">
                        <h2 class="text-xl font-bold"><i class="fas fa-info-circle mr-2"></i> Informasi Dasar Kontrak</h2>
                    </div>

                    <div class="p-6 md:p-8">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                            {{-- 🟦 KOLOM INFORMASI KONTRAK UTAMA --}}
                            <div class="lg:col-span-1 border-r border-gray-200 lg:pr-8">
                                <div class="mb-5 pb-3 border-b">
                                    <p class="text-xs text-gray-500 mb-1 uppercase font-medium">ID Kontrak</p>
                                    <h3 class="text-2xl font-extrabold text-blue-600">{{ $kontrak->id }}</h3>
                                </div>

                                <div class="mb-5 pb-3 border-b">
                                    <p class="text-xs text-gray-500 mb-1 uppercase font-medium">Nama Kontrak</p>
                                    <h4 class="text-lg font-semibold text-gray-800">{{ $kontrak->nama_kontrak }}</h4>
                                </div>

                                <div class="mb-5">
                                    <p class="text-xs text-gray-500 mb-1 uppercase font-medium">Status Kontrak</p>
                                    <div>
                                        @if ($kontrak->status === 'aktif')
                                            <span
                                                class="inline-flex items-center bg-green-100 text-green-700 text-sm font-bold px-3 py-1.5 rounded-full border border-green-500">
                                                <i class="fas fa-check-circle mr-2"></i> Aktif
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center bg-red-100 text-red-700 text-sm font-bold px-3 py-1.5 rounded-full border border-red-500">
                                                <i class="fas fa-times-circle mr-2"></i> Nonaktif
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <hr class="my-4 border-gray-200">

                                {{-- 🔶 INFO FILE DATA PESERTA BARU --}}
                                <h4 class="text-base font-bold text-gray-800 mb-3">
                                    <i class="fas fa-paperclip text-orange-500 mr-2"></i> Data Peserta File
                                </h4>
                                @if ($kontrak->data_peserta_file)
                                    @php
                                        $filePath = asset('uploads/data_peserta/' . $kontrak->data_peserta_file);
                                        $fileExtension = pathinfo($kontrak->data_peserta_file, PATHINFO_EXTENSION);
                                        $fileIcon =
                                            [
                                                'pdf' => 'fa-file-pdf text-red-600',
                                                'xlsx' => 'fa-file-excel text-green-600',
                                                'xls' => 'fa-file-excel text-green-600',
                                                'csv' => 'fa-file-csv text-blue-600',
                                                'doc' => 'fa-file-word text-blue-600',
                                                'docx' => 'fa-file-word text-blue-600',
                                            ][$fileExtension] ?? 'fa-file text-gray-500';
                                    @endphp
                                    <a href="{{ $filePath }}" target="_blank"
                                        class="flex items-center text-sm text-blue-600 hover:text-blue-800 font-semibold transition duration-150">
                                        <i class="fas {{ $fileIcon }} mr-2"></i>
                                        <span>{{ $kontrak->data_peserta_file }}</span>
                                    </a>
                                    <p class="text-xs text-gray-500 mt-1">Klik untuk mengunduh/melihat file.</p>
                                @else
                                    <p class="text-sm text-gray-500 italic">Tidak ada file data peserta diunggah.</p>
                                @endif
                            </div>

                            {{-- 🟩 KOLOM INFORMASI PENDAFTAR --}}
                            @if ($kontrak->pendaftar)
                                <div class="lg:col-span-1 border-r border-gray-200 lg:pr-8">
                                    <h4 class="text-base font-bold text-gray-800 mb-4 pb-2 border-b border-blue-200">
                                        <i class="fas fa-user-circle text-blue-500 mr-2"></i> Data Pendaftar
                                    </h4>

                                    <div class="space-y-3">
                                        <p class="text-sm font-medium">
                                            <i class="fas fa-user mr-2 text-gray-500"></i> Nama:
                                            <strong>{{ $kontrak->pendaftar->nama ?? 'N/A' }}</strong>
                                        </p>
                                        <p class="text-sm font-medium">
                                            <i class="fas fa-envelope mr-2 text-gray-500"></i> Email:
                                            <strong>{{ $kontrak->pendaftar->email ?? 'N/A' }}</strong>
                                        </p>
                                        <p class="text-sm font-medium">
                                            <i class="fas fa-phone-alt mr-2 text-green-500"></i> No HP:
                                            <strong>{{ $kontrak->pendaftar->no_hp ?? 'N/A' }}</strong>
                                        </p>
                                        <p class="text-sm font-medium">
                                            <i class="fas fa-id-badge mr-2 text-purple-500"></i> Tipe:
                                            <strong>{{ ucfirst($kontrak->pendaftar->tipe ?? 'N/A') }}</strong>
                                        </p>
                                        <p class="text-sm font-medium leading-relaxed">
                                            <i class="fas fa-map-marker-alt mr-2 text-red-500"></i> Alamat:
                                            <strong>{{ $kontrak->pendaftar->alamat ?? 'N/A' }}</strong>
                                        </p>
                                    </div>
                                </div>
                            @else
                                <div class="lg:col-span-1 border-r border-gray-200 pr-8 flex items-center justify-center">
                                    <p class="text-gray-500 italic">Data Pendaftar Tidak Ditemukan</p>
                                </div>
                            @endif

                            {{-- 🟧 KOLOM JADWAL & KELAS --}}
                            <div class="lg:col-span-1">
                                <h4 class="text-base font-bold text-gray-800 mb-4 pb-2 border-b border-blue-200">
                                    <i class="fas fa-calendar-alt text-blue-500 mr-2"></i> Jadwal & Kelas
                                </h4>

                                <div class="space-y-3">
                                    <p class="text-sm font-medium">
                                        <i class="fas fa-book-open mr-2 text-yellow-500"></i> Kelas:
                                        <strong>{{ $kontrak->kelas->nama_kelas ?? 'N/A' }}</strong>
                                    </p>
                                    <p class="text-sm font-medium">
                                        <i class="fas fa-users mr-2 text-blue-500"></i> Peserta:
                                        <strong>{{ $kontrak->jumlah_peserta }} Orang</strong>
                                    </p>
                                    {{-- ✅ TAMBAHKAN DURASI KONTRAK (SUDAH DIHITUNG DARI TANGGAL) --}}
                                    <p class="text-sm font-medium">
                                        <i class="fas fa-clock mr-2 text-indigo-500"></i> Durasi:
                                        <strong>{{ $durasi }} Bulan</strong>
                                    </p>
                                    <p class="text-sm font-medium">
                                        <i class="fas fa-calendar-check mr-2 text-green-500"></i> Mulai:
                                        <strong>{{ \Carbon\Carbon::parse($kontrak->tanggal_mulai)->translatedFormat('d F Y') }}</strong>
                                    </p>
                                    <p class="text-sm font-medium">
                                        <i class="fas fa-calendar-times mr-2 text-red-500"></i> Selesai:
                                        <strong>{{ \Carbon\Carbon::parse($kontrak->tanggal_selesai)->translatedFormat('d F Y') }}</strong>
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- RIWAYAT PEMBAYARAN --}}
                <div class="bg-white p-6 rounded-xl shadow-2xl border border-yellow-100 mt-6">
                    <div class="flex justify-between items-center mb-4 pb-2 border-b">
                        <h3 class="text-xl font-bold text-gray-800"><i class="fas fa-history mr-2 text-yellow-600"></i>
                            Riwayat Pembayaran</h3>
                        {{-- Tombol Tambah Pembayaran Cepat --}}
                        <a href="{{ route('admin.pembayaran.create', ['kontrak_id' => $kontrak->id]) }}"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-3 py-1.5 rounded-lg text-sm transition duration-200 shadow-md">
                            <i class="fas fa-plus mr-1"></i> Tambah Pembayaran
                        </a>
                    </div>

                    @if ($kontrak->pembayaran->isEmpty())
                        <p class="text-gray-500 text-center py-4 italic">Belum ada riwayat pembayaran untuk kontrak ini.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            No</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Jumlah Bayar</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal Bayar</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($kontrak->pembayaran as $p)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $loop->iteration }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">Rp
                                                {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                {{ \Carbon\Carbon::parse($p->tanggal_bayar)->translatedFormat('d M Y') }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                @php
                                                    $statusClass = [
                                                        'lunas' => 'bg-green-100 text-green-800',
                                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                                    ];
                                                    $currentStatus = strtolower($p->status);
                                                @endphp
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass[$currentStatus] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ ucfirst($p->status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                                <div class="flex justify-center space-x-2">
                                                    <a href="{{ route('admin.pembayaran.show', $p->id) }}"
                                                        title="Lihat Detail Bukti"
                                                        class="text-blue-500 hover:text-blue-700 transition duration-150">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.pembayaran.edit', $p->id) }}"
                                                        title="Edit Pembayaran"
                                                        class="text-yellow-500 hover:text-yellow-700 transition duration-150">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    {{-- Anda bisa menambahkan form DELETE pembayaran di sini jika diperlukan --}}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                {{-- Footer Aksi --}}
                <div class="flex justify-end space-x-3 mt-6">
                    <a href="{{ route('admin.kontrak.edit', $kontrak->id) }}"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold px-6 py-2 rounded-lg shadow-lg transition duration-300 transform hover:scale-105">
                        <i class="fas fa-edit mr-2"></i> Edit Kontrak
                    </a>

                    {{-- Tombol Hapus (Contoh menggunakan form) --}}
                    <form action="{{ route('admin.kontrak.destroy', $kontrak->id) }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus kontrak ini? Tindakan ini akan menghapus data pendaftar dan file peserta juga!');"
                        class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white font-bold px-6 py-2 rounded-lg shadow-lg transition duration-300 transform hover:scale-105">
                            <i class="fas fa-trash-alt mr-2"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
