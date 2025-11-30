@extends('layouts.app')

@section('content')

    {{-- Section Padding disamakan dengan layout Anda --}}
  <section class="section-padding py-5" style="margin-top: 120px; background-color: #f8f9fa;">



        <div class="container">
            <div class="row g-5 justify-content-center">

                {{-- Kolom Kanan: Detail & Deskripsi --}}
                <div class="col-lg-7 order-lg-2">

                    {{-- Judul dan Deskripsi Singkat --}}
                    <h1 class="display-5 fw-bold mb-3 text-dark">{{ $kelas->nama_kelas }}</h1>
                    <p class="text-muted lead mb-4">{{ $kelas->deskripsi_singkat }}</p>

                    {{-- Detail Metadata --}}
                    <div class="row g-3 mb-4 small text-muted">
                        <div class="col-md-6 d-flex align-items-center">
                            <i class="bi bi-person-badge text-primary me-2 fs-5"></i>
                            <span class="fw-semibold">ID Kelas:</span> {{ $kelas->id }}
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <i class="bi bi-calendar-event text-primary me-2 fs-5"></i>
                            <span class="fw-semibold">Dibuat:</span>
                            {{ \Carbon\Carbon::parse($kelas->created_at)->format('d M Y') }}
                        </div>
                        @if (isset($kelas->durasi))
                            <div class="col-md-6 d-flex align-items-center">
                                <i class="bi bi-clock-fill text-primary me-2 fs-5"></i>
                                <span class="fw-semibold">Durasi:</span> {{ $kelas->durasi }}
                            </div>
                        @endif
                    </div>

                    <hr class="my-4">

                    {{-- Deskripsi Konten --}}
                    <div class="pt-4">
                        <h4 class="fw-bold mb-3 text-dark">Deskripsi Lengkap:</h4>
                        {{-- Menggunakan {!! !!} karena deskripsi dari rich editor --}}
                        <div class="text-secondary lh-lg p-4 bg-light rounded-3 border border-gray-200">
                            {{-- Menggunakan $kelas->deskripsi aslinya (asumsi tidak ada kolom deskripsi_lengkap) --}}
                            {!! $kelas->deskripsi !!}
                        </div>
                    </div>

                    {{-- Materi yang Dipelajari --}}
                    @if (isset($kelas->materi))
                        <div class="pt-4 mt-4 border-top">
                            <h4 class="fw-bold mb-3 text-dark">Materi yang Dipelajari</h4>
                            {{-- Mengasumsikan materi dipisahkan oleh baris baru (\n) --}}
                            <ul class="list-unstyled text-secondary">
                                @foreach (explode("\n", $kelas->materi) as $materiItem)
                                    @if (trim($materiItem))
                                        <li class="mb-2 d-flex align-items-start">
                                            <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                                            <span>{{ trim($materiItem) }}</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </div>

                {{-- Kolom Kiri: Foto Utama, Harga, dan Tombol --}}
                <div class="col-lg-4 order-lg-1">
                    <div class="card p-4 shadow-lg rounded-4 border border-primary-subtle sticky-top" style="top: 20px;">

                        {{-- Foto Kelas --}}
                        <div class="mb-4 rounded-3 overflow-hidden shadow-md">
                            @if ($kelas->foto_kelas)
                                <img src="{{ asset('uploads/kelas/' . $kelas->foto_kelas) }}" alt="{{ $kelas->nama_kelas }}"
                                    class="w-100 h-64 object-cover">
                            @else
                                <div
                                    class="w-100 h-64 flex items-center justify-center bg-gray-200 text-gray-600 rounded-lg">
                                    Tidak Ada Foto
                                </div>
                            @endif
                        </div>

                        {{-- Harga --}}
                        <h3 class="fw-bold text-dark-blue mb-4 text-center fs-3">
                            Rp {{ number_format($kelas->harga, 0, ',', '.') }}
                        </h3>

                        {{-- Tombol Daftar mengarah ke halaman registrasi --}}
                        <a href="{{ route('register.step1.show') }}?kelas={{ $kelas->id }}" {{-- Menggunakan ID di URL --}}
                            class="btn btn-dark-blue btn-lg rounded-pill px-4 w-100 mb-3 shadow">
                            Daftar Kelas Ini
                        </a>
                        <a href="{{ url('/') }}#kelas" class="btn btn-outline-secondary rounded-pill px-4 w-100">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Kelas
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection

{{-- Optional: Script untuk mengisi form registrasi secara otomatis (Jika Anda memerlukannya) --}}
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Script ini berfungsi untuk meneruskan ID kelas ke formulir pendaftaran
            const urlParams = new URLSearchParams(window.location.search);
            const kelasId = urlParams.get('kelas');

            // Logika lebih lanjut diperlukan di halaman register.step1.show untuk mengambil data kelas berdasarkan ID
            // dan mengisi field yang sesuai.
            if (kelasId) {
                console.log('Class ID Passed to Register: ' + kelasId);
            }
        });
    </script>
@endpush
