@extends('layouts.app')

@section('content')
    <section id="payment-confirmation-section" class="py-5"
        style="background: linear-gradient(135deg, #ffffff 0%, #f0f6ff 100%);">
        <div class="container">

            {{-- Header & Progress Indicator --}}
            <div class="text-center mb-5">
                <h1 class="fw-bold text-primary display-6 mb-2">Konfirmasi Pembayaran</h1>
                <p class="lead text-muted">Langkah 2 dari 2: Unggah bukti transfer Anda.</p>

                {{-- Indikator Langkah (Visual yang Profesional) --}}
                <div class="d-flex justify-content-center mt-3">
                    <div class="step-indicator me-3 p-2 border-bottom border-light border-4 text-muted">
                        1. Data Pendaftar
                    </div>
                    <div class="step-indicator active p-2 border-bottom border-primary border-4 fw-bold text-primary">
                        2. Konfirmasi Pembayaran
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-9">
                    <div class="bg-white rounded-4 border p-4 p-md-5 form-panel shadow-lg">

                        @if (isset($data))
                            {{-- Mendefinisikan variabel dan perhitungan --}}
                            @php
                                $totalHargaKontrak = $data->harga ?? 0;
                                $durasi = $data->durasi_bulan ?? 1;
                                $peserta = $data->jumlah_peserta ?? 1;

                                // Asumsi: Harga dasar per bulan = Total Harga / Durasi / Peserta
                                $hargaDasarPerBulan =
                                    $durasi > 0 && $peserta > 0 ? $totalHargaKontrak / $durasi / $peserta : 0;
                                $totalHargaPerBulan = $hargaDasarPerBulan * $peserta;

                                $sisaTagihan = $totalHargaKontrak;
                                $sisaClass = $sisaTagihan > 0 ? 'text-danger' : 'text-success';
                            @endphp

                            <div class="row g-4 g-lg-5">

                                {{-- Kolom Kiri: Detail Tagihan & Bank --}}
                                <div class="col-md-5">
                                    <h4 class="fw-bold mb-3 text-dark"><i class="bi bi-wallet-fill me-2"></i> Detail Tagihan
                                        & Transfer</h4>

                                    {{-- Rincian Kontrak --}}
                                    <div class="card bg-light border-0 rounded-3 mb-4 p-3 shadow-sm">
                                        <h5 class="fw-bold mb-3 text-primary">Rincian Kontrak</h5>

                                        <p class="mb-1 small">
                                            <strong class="text-dark">Penanggung Jawab:</strong>
                                            {{ $data->nama_pendaftar ?? 'N/A' }}
                                        </p>
                                        <p class="mb-1 small">
                                            <strong class="text-dark">Kelas:</strong>
                                            {{ $data->nama_kelas ?? 'N/A' }}
                                        </p>
                                        <p class="mb-2 small">
                                            <strong class="text-dark">Jumlah Peserta:</strong>
                                            {{ $peserta }} Orang
                                        </p>

                                        @if (isset($data->durasi_bulan) && isset($data->tanggal_mulai) && isset($data->tanggal_selesai))
                                            <hr class="my-2 border-secondary-subtle">
                                            <p class="mb-1 small">
                                                <strong class="text-dark">Durasi Kontrak:</strong>
                                                {{ $durasi }} Bulan
                                            </p>
                                            <p class="mb-1 small">
                                                <strong class="text-dark">Tanggal Mulai:</strong>
                                                {{ \Carbon\Carbon::parse($data->tanggal_mulai)->format('d M Y') }}
                                            </p>
                                            <p class="mb-0 small">
                                                <strong class="text-dark">Tanggal Selesai:</strong>
                                                {{ \Carbon\Carbon::parse($data->tanggal_selesai)->format('d M Y') }}
                                            </p>
                                        @else
                                            <hr class="my-2 border-secondary-subtle">
                                            <p class="mb-0 small text-danger">
                                                Detail Durasi/Tanggal tidak ditemukan. Silakan ulangi Langkah 1.
                                            </p>
                                        @endif
                                    </div>

                                    {{-- Status Keuangan (Diperbarui dengan Deskripsi Detail) --}}
                                    <div
                                        class="card bg-warning-subtle border-l-4 border-l-yellow-600 rounded-3 mb-4 p-3 shadow-sm">
                                        <h5 class="fw-bold mb-3 text-dark">Total Tagihan</h5>

                                        {{-- ✅ RINCIAN HARGA YANG SUDAH DIRAPIKAN --}}
                                        <div class="small text-gray-700 mb-3">

                                            {{-- Baris 1: Harga Satuan/Bulan --}}
                                            <div class="d-flex justify-content-between mb-1">
                                                <span>Harga Kelas Satuan/Bulan:</span>
                                                <strong class="text-end" style="white-space: nowrap;">
                                                    Rp&nbsp;{{ number_format($hargaDasarPerBulan, 0, ',', '.') }}
                                                </strong>
                                            </div>

                                            {{-- Baris 2: Total Per Bulan --}}
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Total Harga Per Bulan (x{{ $peserta }} Peserta):</span>
                                                <strong class="text-end text-dark" style="white-space: nowrap;">
                                                    Rp&nbsp;{{ number_format($totalHargaPerBulan, 0, ',', '.') }}
                                                </strong>
                                            </div>

                                            <hr class="my-2 border-secondary-subtle">

                                            {{-- Baris 3: Total Biaya Kontrak --}}
                                            <div class="d-flex justify-content-between fw-bold">
                                                <span>Total Biaya Kontrak (x{{ $durasi }} Bulan):</span>
                                                <strong class="text-end text-dark" style="white-space: nowrap;">
                                                    Rp&nbsp;{{ number_format($totalHargaKontrak, 0, ',', '.') }}
                                                </strong>
                                            </div>
                                        </div>
                                        {{-- -------------------------- --}}

                                        <div class="d-flex justify-content-between border-top mt-2 pt-2">
                                            <p class="fw-bold text-dark mb-0">Wajib Dibayar Saat Ini:</p>
                                            <strong class="text-end fw-bold {{ $sisaClass }} fs-5">
                                                Rp {{ number_format(max(0, $sisaTagihan), 0, ',', '.') }}
                                            </strong>
                                        </div>
                                    </div>

                                    {{-- Info Rekening Bank --}}
                                    <p class="small text-muted mb-3">
                                        Lakukan transfer ke rekening resmi di bawah ini:
                                    </p>
                                    <div class="mb-4 small p-3 border border-primary rounded-3 bg-primary-subtle">
                                        <p class="mb-1 fw-bold text-dark">Bank Tujuan: Bank ABC (Kode: 013)</p>
                                        <p class="mb-1 fw-bold fs-5 text-dark">No. Rekening: 123-456-7890</p>
                                        <p class="mb-0 text-muted">Atas Nama: PT AfterSchola Edukasi</p>
                                    </div>

                                    <a href="https://api.whatsapp.com/send?phone=62895339549424&text=Halo%20Admin%20AfterSchola%2C%20saya%20sudah%20mengirimkan%20bukti%20pembayaran."
                                        target="_blank"
                                        class="btn btn-success btn-lg rounded-pill px-3 py-2 w-100 shadow-sm">
                                        <i class="bi bi-whatsapp me-2"></i> Konfirmasi Manual (Jika ada kendala)
                                    </a>
                                </div>

                                {{-- Kolom Kanan: Formulir Unggah Bukti --}}
                                <div class="col-md-7 border-start-md ps-md-5 form-fade-in">
                                    <h4 class="fw-bold mb-4 text-center text-md-start text-primary"><i
                                            class="bi bi-cloud-arrow-up-fill me-2"></i> Unggah Bukti Transfer</h4>

                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <form action="{{ route('register.step2.store') }}" method="POST" class="contact-form"
                                        enctype="multipart/form-data">
                                        @csrf

                                        {{-- HIDDEN INPUTS --}}
                                        <input type="hidden" name="tanggal_bayar" value="{{ now()->format('Y-m-d') }}">
                                        <input type="hidden" name="status" value="pending"> {{-- KRITIS: Menggunakan 'pending' --}}

                                        {{-- FIELD JUMLAH TRANSFER (jumlah_bayar) --}}
                                        <div class="mb-4">
                                            <label for="jumlahBayarKonfirmasi" class="form-label fw-bold">Jumlah yang Anda
                                                Transfer</label>
                                            <div class="input-group input-group-lg shadow-sm">
                                                <span class="input-group-text bg-light fw-bold">Rp</span>
                                                <input type="number"
                                                    class="form-control rounded-end-3 @error('jumlah_bayar') is-invalid @enderror"
                                                    id="jumlahBayarKonfirmasi" name="jumlah_bayar"
                                                    placeholder="Contoh: {{ number_format($totalHargaKontrak, 0, '', '') }}"
                                                    required>
                                            </div>
                                            <div class="form-text">Masukkan jumlah persis yang Anda transfer ke rekening di
                                                samping.</div>

                                            @error('jumlah_bayar')
                                                <div class="invalid-feedback d-block fw-bold">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- FIELD BUKTI PEMBAYARAN (bukti_pembayaran) --}}
                                        <div class="mb-5">
                                            <label for="buktiPembayaran" class="form-label fw-bold">Bukti Transfer
                                                (Image)</label>
                                            <input
                                                class="form-control form-control-lg rounded-3 @error('bukti_pembayaran') is-invalid @enderror"
                                                type="file" id="buktiPembayaran" name="bukti_pembayaran" required>

                                            <div class="form-text">Format: .jpg, .jpeg, .png (Maksimal 2MB).</div>
                                            @error('bukti_pembayaran')
                                                <div class="invalid-feedback d-block fw-bold">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center pt-3">
                                            <a href="{{ route('register.step1.show') }}"
                                                class="btn btn-outline-secondary btn-lg rounded-pill px-4">
                                                <i class="bi bi-arrow-left"></i> Kembali
                                            </a>
                                            <button type="submit"
                                                class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm">
                                                Selesaikan Pendaftaran
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning text-center">
                                <h4 class="alert-heading">Sesi Kedaluwarsa</h4>
                                <p>Detail pesanan tidak ditemukan. Silakan mulai pendaftaran dari awal.</p>
                                <a href="{{ route('register.step1.show') }}" class="btn btn-warning mt-2">Mulai
                                    Pendaftaran</a>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
