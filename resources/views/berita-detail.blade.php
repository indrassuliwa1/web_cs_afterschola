@extends('layouts.app')

@section('content')
    {{-- Menggunakan section-padding dan background-color yang konsisten dengan layout Anda --}}
    <section class="section-padding pt-5" style="background-color: #f8f9fa;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">

                    {{-- Tombol Kembali --}}
                    <a href="{{ url('/') }}#berita" class="btn btn-outline-secondary rounded-pill px-4 mb-4 shadow-sm">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Berita
                    </a>

                    {{-- Artikel Berita Utama - Menggunakan shadow dan rounded modern --}}
                    <article class="berita-article bg-white p-lg-5 p-4 rounded-4 shadow-lg border border-primary-subtle">

                        {{-- Judul Berita (MENGGUNAKAN $informasi->judul) --}}
                        <h1 class="display-5 fw-bold mb-3 text-dark">{{ $informasi->judul }}</h1>

                        {{-- Meta Data Berita --}}
                        <div class="berita-meta mb-5 border-bottom pb-3">
                            <div class="row g-3">
                                {{-- Penulis --}}
                                <div class="col-md-6 col-12">
                                    <span
                                        class="d-flex align-items-center bg-primary-subtle text-primary rounded-3 p-2 shadow-sm small">
                                        <i class="bi bi-person-fill fs-5 me-2"></i>
                                        <strong class="me-1">Oleh:</strong>
                                        {{ $informasi->author->name ?? 'Admin AfterSchola' }}
                                    </span>
                                </div>
                                {{-- Tanggal --}}
                                <div class="col-md-6 col-12">
                                    <span
                                        class="d-flex align-items-center bg-primary-subtle text-primary rounded-3 p-2 shadow-sm small">
                                        <i class="bi bi-calendar-event-fill fs-5 me-2"></i>
                                        <strong class="me-1">Diterbitkan:</strong>
                                        {{ \Carbon\Carbon::parse($informasi->created_at)->format('d F Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Gambar Utama Berita (MENGGUNAKAN $informasi->gambar) --}}
                        @if ($informasi->gambar)
                            <figure class="mb-5">
                                {{-- PATH GAMBAR YANG BENAR DENGAN VARIABEL $informasi --}}
                                <img src="{{ asset('uploads/informasi/' . $informasi->gambar) }}"
                                    alt="{{ $informasi->judul }}"
                                    class="img-fluid rounded-4 shadow-lg w-100 object-fit-cover" style="max-height: 450px;">
                                <figcaption class="text-muted small mt-3 text-center">
                                    Sumber Gambar: {{ $informasi->judul }}
                                </figcaption>
                            </figure>
                        @endif

                        {{-- Konten Isi Berita (MENGGUNAKAN $informasi->isi) --}}
                        <div
                            class="fs-5 berita-detail-content lh-lg text-secondary p-4 border-start border-4 border-primary bg-light rounded-end">
                            {!! $informasi->isi !!}
                        </div>

                    </article>

                    {{-- Tombol Kembali di Bawah --}}
                    <div class="text-center mt-5 mb-5">
                        <a href="{{ url('/') }}#berita" class="btn btn-dark-blue btn-lg rounded-pill px-5 shadow-lg">
                            <i class="bi bi-arrow-left me-2"></i> Lihat Semua Berita
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
