@extends('layouts.dashboard')

@section('title', 'Detail Pembayaran')
@section('page', 'Detail Transaksi')

@section('content')
    <div class="container mx-auto p-4 md:p-8 min-h-screen bg-gray-50">
        <div class="max-w-4xl mx-auto">

            {{-- Header dan Tombol Kembali --}}
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h1 class="text-3xl font-extrabold text-gray-800">
                    <i class="fas fa-eye text-blue-600 mr-2"></i> Detail Pembayaran #{{ $pembayaran->id }}
                </h1>
                <a href="{{ route('admin.pembayaran') }}"
                    class="flex items-center bg-gray-500 hover:bg-gray-600 text-white font-semibold px-4 py-2 rounded-lg transition duration-200 shadow-md">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                </a>
            </div>

            {{-- ✅ BLOK PERHITUNGAN UNTUK DETAIL DESKRIPSI --}}
            @php
                // Data Kontrak
                $kontrak = $pembayaran->kontrak;
                $kelas = $kontrak->kelas;

                // --- LOGIKA BARU: HITUNG DURASI BULAN SECARA AKURAT DARI TANGGAL ---
                $durasi = 1; // Default
                try {
                    $start = \Carbon\Carbon::parse($kontrak->tanggal_mulai);
                    $end = \Carbon\Carbon::parse($kontrak->tanggal_selesai);
                    $diffInDays = $start->diffInDays($end);

                    if ($diffInDays > 0) {
                        // Jika durasi lebih dari 31 hari, hitung selisih bulan
                        if ($diffInDays > 31) {
                            $durasiKontrak = $start->diffInMonths($end) + 1;
                            if ($durasiKontrak > 1 && $end->day < $start->day) {
                                $durasiKontrak -= 1;
                            }
                            $durasi = max(1, (int) round($durasiKontrak));
                        } else {
                            $durasi = 1; // Jika 1-31 hari, anggap 1 bulan
                        }
                    } else {
                        $durasi = 1;
                    }
                } catch (\Exception $e) {
                    $durasi = $kontrak->durasi_bulan ?? 1; // Fallback
                }

                // Variabel inti
                $peserta = $kontrak->jumlah_peserta ?? 1;

                // Data Keuangan DARI CONTROLLER (diasumsikan sudah dikirim: $totalTagihanKontrak, $totalBayarMasukKontrak, $sisaTagihanKontrak)
                $totalTagihanKontrak = $totalTagihanKontrak ?? 0;
                $totalBayarMasukKontrak = $totalBayarMasukKontrak ?? 0;
                $sisaTagihanKontrak = $sisaTagihanKontrak ?? $totalTagihanKontrak - $totalBayarMasukKontrak;

                // Perhitungan Harga Satuan dari Total Tagihan yang DITERIMA dari Controller
                $hargaDasarPerBulan =
                    $totalTagihanKontrak > 0 && $peserta > 0 && $durasi > 0
                        ? $totalTagihanKontrak / $peserta / $durasi
                        : $kontrak->kelas->harga ?? 0;

                // Perhitungan total di view
                $totalHargaPerBulan = $hargaDasarPerBulan * $peserta;
                $hargaKontrakFinal = $totalHargaPerBulan * $durasi;

            @endphp
            {{-- ----------------------------------------- --}}

            {{-- 🟥 KARTU STATUS UTAMA (Agregat Kontrak) --}}
            @if (isset($statusKontrakAgregat))
                @php
                    $isLunasKontrak = $statusKontrakAgregat == 'lunas';
                    $mainStatusClass = $isLunasKontrak ? 'bg-green-600' : 'bg-red-600';

                    $transactionStatusColor =
                        [
                            'lunas' => 'bg-green-600',
                            'pending' => 'bg-yellow-600',
                            'batal' => 'bg-red-600',
                        ][strtolower($pembayaran->status)] ?? 'bg-gray-600';

                    // Ambil data Risiko dari Controller
                    $riskData = $riskData ?? null; // Asumsi dikirim dari Controller
                    $riskClass = $riskData['risk_prediction'] ?? 'N/A';
                    $riskScore = $riskData['risk_score'] ?? null;

                    if ($riskClass === 'Tinggi') {
                        $riskColor = 'border-red-500 bg-red-50 text-red-700';
                    } elseif ($riskClass === 'Rendah') {
                        $riskColor = 'border-green-500 bg-green-50 text-green-700';
                    } else {
                        $riskColor = 'border-gray-500 bg-gray-50 text-gray-700';
                    }
                @endphp

                <div class="mb-8 grid grid-cols-1 md:grid-cols-4 gap-4">

                    {{-- 🟦 KARTU UTAMA AGREGAT --}}
                    <div class="md:col-span-3 p-6 rounded-xl shadow-lg border-t-4 border-yellow-600 bg-yellow-50">

                        {{-- STATUS AGREGAT KONTRAK --}}
                        <h2 class="text-2xl font-bold mb-2 flex items-center text-gray-800">
                            Status Pelunasan Kontrak:
                            <span class="px-3 py-1 rounded-full text-white text-lg ml-3 {{ $mainStatusClass }}">
                                {{ ucfirst($statusKontrakAgregat) }}
                            </span>
                        </h2>

                        <hr class="my-3 border-gray-200">

                        {{-- RINGKASAN KEUANGAN AGREGAT DENGAN DESKRIPSI (Layout DIRAPIKAN) --}}
                        <h3 class="text-lg font-bold mb-2 text-gray-700">Ringkasan Keuangan Kontrak</h3>

                        {{-- ✅ DESKRIPSI HARGA DENGAN LAYOUT RAPI --}}
                        <div class="mb-4 text-sm text-gray-700 border-b pb-3 space-y-1">
                            <p class="text-xs text-gray-500 mb-1 font-semibold">Perhitungan Total Tagihan:</p>

                            {{-- Baris Harga Satuan/Bulan --}}
                            <div class="flex justify-between">
                                <span class="text-sm">Harga Satuan/Bulan:</span>
                                <strong class="text-right text-sm text-gray-800 whitespace-nowrap">
                                    Rp {{ number_format($hargaDasarPerBulan, 0, ',', '.') }}
                                </strong>
                            </div>

                            {{-- Baris Total Harga Per Bulan --}}
                            <div class="flex justify-between">
                                <span class="text-sm">Total Harga Per Bulan (x{{ $peserta }} Peserta):</span>
                                <strong class="text-right text-sm text-gray-800 whitespace-nowrap">
                                    Rp {{ number_format($totalHargaPerBulan, 0, ',', '.') }}
                                </strong>
                            </div>

                            {{-- Baris Total Kontrak --}}
                            <div class="flex justify-between font-bold pt-1 border-t mt-1 border-gray-300">
                                <span class="text-sm">Total Kontrak (x{{ $durasi }} Bulan):</span>
                                <strong class="text-right text-sm text-blue-600 whitespace-nowrap">
                                    Rp {{ number_format($hargaKontrakFinal, 0, ',', '.') }}
                                </strong>
                            </div>
                        </div>
                        {{-- END DESKRIPSI HARGA DETAIL --}}

                        <div class="grid grid-cols-3 text-sm text-gray-700">
                            <div>
                                <p class="text-xs text-gray-500">Total Tagihan</p>
                                <p class="font-bold text-lg">Rp {{ number_format($totalTagihanKontrak ?? 0, 0, ',', '.') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Total Sudah Dibayar</p>
                                <p class="font-bold text-lg text-green-700">Rp
                                    {{ number_format($totalBayarMasukKontrak ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Sisa Tagihan</p>
                                <p
                                    class="font-bold text-lg {{ $sisaTagihanKontrak > 0 ? 'text-red-700' : 'text-green-700' }}">
                                    Rp {{ number_format(max(0, $sisaTagihanKontrak), 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- 🚨 KOLOM ML PREDIKSI RISIKO (BARU) --}}
                    <div class="md:col-span-1 p-6 rounded-xl shadow-lg border-l-4 border-r-4 {{ $riskColor }}">
                        <h3 class="text-sm font-extrabold mb-2 uppercase flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i> Prediksi Risiko
                        </h3>
                        <p class="text-3xl font-extrabold mb-1">
                            {{ $riskClass }}
                        </p>
                        @if ($riskScore !== null)
                            <p class="text-xs font-semibold mt-1">Skor Risiko: {{ number_format($riskScore * 100, 1) }}%
                            </p>
                        @endif
                        <p class="text-xs mt-2 italic">
                            {{ $riskClass === 'Tinggi' ? 'Kontrak ini butuh perhatian ekstra.' : 'Kontrak ini cenderung aman.' }}
                        </p>
                    </div>

                </div>
            @endif
            {{-- END KARTU STATUS KEUANGAN --}}


            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- KOLOM 1 & 2: Detail Kontrak dan Pendaftar & RIWAYAT --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Detail Kontrak Terkait --}}
                    <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-blue-500">
                        <h3 class="text-xl font-semibold mb-4 text-gray-800"><i class="fas fa-file-contract mr-2"></i>
                            Detail Kontrak Terkait</h3>
                        <div class="space-y-2 text-sm text-gray-700">
                            <p><strong>Nama Kontrak:</strong> {{ $pembayaran->kontrak->nama_kontrak ?? 'N/A' }}</p>
                            <p><strong>Kelas:</strong> {{ $pembayaran->kontrak->kelas->nama_kelas ?? 'N/A' }}</p>
                            <p><strong>Harga Satuan:</strong> Rp
                                {{ number_format($pembayaran->kontrak->kelas->harga ?? 0, 0, ',', '.') }}</p>
                            <p><strong>Jumlah Peserta:</strong> {{ $pembayaran->kontrak->jumlah_peserta ?? 'N/A' }}</p>
                            {{-- ✅ FIX DURASI MENGGUNAKAN HASIL PERHITUNGAN DARI ATAS --}}
                            <p><strong>Durasi Kontrak:</strong> **{{ $durasi }}** Bulan</p>
                            <a href="{{ route('admin.kontrak.show', $pembayaran->kontrak->id ?? 0) }}"
                                class="text-xs text-blue-500 hover:underline block mt-3">
                                Lihat Detail Kontrak Lengkap
                            </a>
                        </div>
                    </div>

                    {{-- RIWAYAT TRANSAKSI TERKAIT KONTRAK INI --}}
                    <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-purple-500">
                        <h3 class="text-xl font-semibold mb-4 text-gray-800 flex justify-between items-center">
                            <span><i class="fas fa-history mr-2"></i> Riwayat Transaksi Kontrak </span>
                            {{-- Link Tambah Pembayaran Cepat --}}
                            <a href="{{ route('admin.pembayaran.create', ['kontrak_id' => $pembayaran->kontrak_id]) }}"
                                class="bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold px-3 py-1 rounded-lg transition duration-200 shadow-md">
                                <i class="fas fa-plus-circle mr-1"></i> Transaksi Baru
                            </a>
                        </h3>

                        {{-- Tabel Riwayat Transaksi --}}
                        @if ($pembayaran->kontrak->pembayaran->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Tgl</th>
                                            <th
                                                class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Jumlah</th>
                                            <th
                                                class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status</th>
                                            <th
                                                class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($pembayaran->kontrak->pembayaran->sortByDesc('tanggal_bayar') as $transaksi)
                                            <tr
                                                class="{{ $transaksi->id == $pembayaran->id ? 'bg-yellow-50 font-bold' : 'hover:bg-gray-50' }}">
                                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">
                                                    {{ \Carbon\Carbon::parse($transaksi->tanggal_bayar)->format('d M Y') }}
                                                </td>
                                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-700">
                                                    Rp {{ number_format($transaksi->jumlah_bayar, 0, ',', '.') }}
                                                </td>
                                                <td class="px-3 py-2 whitespace-nowrap">
                                                    @php
                                                        $statusClass = [
                                                            'lunas' => 'bg-green-100 text-green-800',
                                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                                            'batal' => 'bg-red-100 text-red-800',
                                                        ];
                                                        $currentStatus = strtolower($transaksi->status);
                                                    @endphp
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass[$currentStatus] ?? 'bg-gray-100 text-gray-800' }}">
                                                        {{ ucfirst($transaksi->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-3 py-2 whitespace-nowrap text-center">
                                                    {{-- Link ke Detail Pembayaran transaksi ini --}}
                                                    <a href="{{ route('admin.pembayaran.show', $transaksi->id) }}"
                                                        title="Lihat Detail Transaksi"
                                                        class="text-blue-600 hover:text-blue-800 transition duration-150">
                                                        <i class="fas fa-eye text-md"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-sm text-gray-500 italic">Belum ada transaksi pembayaran tercatat untuk kontrak
                                ini.</p>
                        @endif
                    </div>

                    {{-- Detail Pendaftar --}}
                    <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-green-500">
                        <h3 class="text-xl font-semibold mb-4 text-gray-800"><i class="fas fa-user-circle mr-2"></i> Data
                            Pendaftar</h3>
                        <div class="space-y-2 text-sm text-gray-700">
                            <p><strong>Nama:</strong> {{ $pembayaran->pendaftar->nama ?? 'N/A' }}</p>
                            <p><strong>Email:</strong> {{ $pembayaran->pendaftar->email ?? 'N/A' }}</p>
                            <p><strong>No HP:</strong> {{ $pembayaran->pendaftar->no_hp ?? 'N/A' }}</p>
                            <p><strong>Tipe:</strong> {{ ucfirst($pembayaran->pendaftar->tipe ?? 'N/A') }}</p>
                            <p><strong>Alamat:</strong> {{ $pembayaran->pendaftar->alamat ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                {{-- KOLOM 3: Bukti Pembayaran (SLIDER) --}}
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white p-6 rounded-xl shadow-lg border-t-4 border-yellow-500">
                        <h3 class="text-xl font-semibold mb-4 text-gray-800"><i class="fas fa-image mr-2"></i> Bukti
                            Transaksi</h3>

                        @php
                            // Memastikan $pembayaran->bukti_pembayaran adalah array dan memfilter item kosong/null
                            $buktiFiles = is_array($pembayaran->bukti_pembayaran)
                                ? array_filter($pembayaran->bukti_pembayaran)
                                : [];

                            // Menangani kasus data lama yang mungkin masih berupa string tunggal (sebelum array casting)
                            if (!is_array($pembayaran->bukti_pembayaran) && $pembayaran->bukti_pembayaran) {
                                $buktiFiles = [$pembayaran->bukti_pembayaran];
                            }
                        @endphp

                        @if (count($buktiFiles) > 0)
                            <div id="proof-carousel" class="relative w-full overflow-hidden bg-gray-100 rounded-lg">
                                <div id="slider-container" class="flex transition-transform duration-500 ease-in-out">
                                    @foreach ($buktiFiles as $fileName)
                                        @if ($fileName)
                                            <div class="flex-shrink-0 w-full p-2">
                                                @php
                                                    $extension = pathinfo($fileName, PATHINFO_EXTENSION);
                                                    $isImage = in_array(strtolower($extension), [
                                                        'jpg',
                                                        'jpeg',
                                                        'png',
                                                        'gif',
                                                    ]);
                                                @endphp

                                                <div class="text-center">
                                                    @if ($isImage)
                                                        <img src="{{ asset('uploads/bukti_pembayaran/' . $fileName) }}"
                                                            alt="Bukti {{ $loop->iteration }}"
                                                            class="w-full h-auto object-contain rounded-md mb-2 cursor-pointer max-h-60"
                                                            onclick="window.open(this.src)">
                                                    @else
                                                        <div class="text-center py-8 text-gray-500 bg-gray-200 rounded-md">
                                                            <i class="fas fa-file text-4xl mb-2"></i>
                                                            <p class="text-sm">File Non-Gambar
                                                                ({{ strtoupper($extension) }})
                                                            </p>
                                                        </div>
                                                    @endif

                                                    <a href="{{ asset('uploads/bukti_pembayaran/' . $fileName) }}"
                                                        target="_blank"
                                                        class="text-sm text-blue-600 hover:text-blue-800 font-medium transition duration-150 block mt-2">
                                                        <i class="fas fa-download mr-1"></i> Unduh File
                                                        ({{ $loop->iteration }}/{{ count($buktiFiles) }})
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                                @if (count($buktiFiles) > 1)
                                    {{-- Tombol Previous --}}
                                    <button type="button" onclick="prevSlide()"
                                        class="absolute top-1/2 left-0 transform -translate-y-1/2 bg-black bg-opacity-30 text-white p-3 rounded-r-lg hover:bg-opacity-50 transition">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    {{-- Tombol Next --}}
                                    <button type="button" onclick="nextSlide()"
                                        class="absolute top-1/2 right-0 transform -translate-y-1/2 bg-black bg-opacity-30 text-white p-3 rounded-l-lg hover:bg-opacity-50 transition">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                    {{-- Indikator Slide --}}
                                    <div id="slide-indicators"
                                        class="absolute bottom-2 left-0 right-0 flex justify-center space-x-2">
                                        @foreach ($buktiFiles as $index => $fileName)
                                            <div class="h-2 w-2 rounded-full bg-white bg-opacity-70 cursor-pointer"
                                                onclick="goToSlide({{ $index }})"></div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @else
                            <p class="text-gray-500 italic text-sm">Tidak ada bukti pembayaran yang diunggah untuk
                                transaksi
                                ini.</p>
                        @endif
                    </div>

                    {{-- Tombol Aksi Cepat --}}
                    <div class="mt-6 flex flex-col space-y-3">
                        <a href="{{ route('admin.pembayaran.edit', $pembayaran->id) }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg text-center transition duration-150">
                            <i class="fas fa-edit mr-2"></i> Edit Pembayaran
                        </a>

                        {{-- Tombol Hapus (Konfirmasi disesuaikan untuk multiple files) --}}
                        <form action="{{ route('admin.pembayaran.destroy', $pembayaran->id) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus pembayaran ini? Ini akan menghapus {{ count($buktiFiles) }} file bukti terkait.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-lg w-full transition duration-150">
                                <i class="fas fa-trash mr-2"></i> Hapus Pembayaran
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Logika Carousel Slider (Sudah ada di kode Anda, ini untuk memastikan tampilannya berjalan)
        let currentSlide = 0;
        const slides = document.querySelectorAll('#slider-container > div');
        const totalSlides = slides.length;
        const sliderContainer = document.getElementById('slider-container');
        const indicatorsContainer = document.getElementById('slide-indicators');

        function updateCarousel() {
            if (!sliderContainer || totalSlides === 0) return;

            // Hitung perpindahan X
            const offset = -currentSlide * 100;
            sliderContainer.style.transform = `translateX(${offset}%)`;

            // Update indikator
            if (indicatorsContainer) {
                indicatorsContainer.querySelectorAll('div').forEach((indicator, index) => {
                    indicator.classList.remove('bg-opacity-100');
                    indicator.classList.add('bg-opacity-70');
                    if (index === currentSlide) {
                        indicator.classList.add('bg-opacity-100');
                    }
                });
            }
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateCarousel();
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            updateCarousel();
        }

        function goToSlide(n) {
            currentSlide = n;
            updateCarousel();
        }

        // Panggil saat DOM dimuat
        document.addEventListener('DOMContentLoaded', updateCarousel);
    </script>
@endsection
