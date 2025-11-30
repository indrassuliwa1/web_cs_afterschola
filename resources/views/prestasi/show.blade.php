@extends('layouts.app')

@section('content')
{{-- Background Gradient Soft --}}
<section class="section-padding" style="background: linear-gradient(120deg, #e0f7fa 0%, #f0f4f8 100%); min-height: 100vh; padding-top: 100px;">
    <div class="container">
        
        {{-- Breadcrumb / Tombol Kembali --}}
        <div class="mb-4 animate-on-scroll">
            <a href="{{ url('/#prestasi') }}" class="btn btn-light rounded-pill px-4 shadow-sm text-primary fw-bold hover-scale">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Beranda
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                {{-- GLASS CARD WRAPPER --}}
                <div class="glass-card p-0 overflow-hidden animate-on-scroll">
                    
                    {{-- 1. Header Gambar Besar --}}
                    <div class="position-relative" style="height: 400px;">
                        {{-- Mengambil Foto dari Database --}}
                        <img src="{{ asset('uploads/prestasi/' . $prestasi->bukti_foto) }}" 
                             class="w-100 h-100 object-fit-cover" 
                             alt="{{ $prestasi->judul }}">
                        
                        {{-- Overlay Gradient --}}
                        <div class="position-absolute bottom-0 start-0 w-100 p-4 p-md-5" 
                             style="background: linear-gradient(to top, rgba(13, 42, 84, 0.9), transparent);">
                            
                            <span class="badge bg-warning text-dark mb-2 px-3 py-2 rounded-pill shadow-sm">
                                <i class="bi bi-trophy-fill me-1"></i> Achievement
                            </span>
                            <h1 class="text-white fw-bold display-5 text-shadow">{{ $prestasi->judul }}</h1>
                        </div>
                    </div>

                    {{-- 2. Konten Detail --}}
                    <div class="p-4 p-md-5 bg-white bg-opacity-60">
                        
                        {{-- Meta Data --}}
                        <div class="d-flex align-items-center gap-3 text-muted mb-4 border-bottom pb-3">
                            <small><i class="bi bi-calendar-check me-1 text-primary"></i> Diposting pada: {{ $prestasi->created_at->format('d M Y') }}</small>
                            <small class="ms-auto"><i class="bi bi-eye me-1"></i> Dilihat oleh Admin</small>
                        </div>

                        {{-- Isi Deskripsi (Render HTML) --}}
                        <div class="prestasi-content text-dark-blue" style="font-size: 1.1rem; line-height: 1.8;">
                            {!! $prestasi->deskripsi !!}
                        </div>

                    </div>
                    
                    {{-- Footer Card --}}
                    <div class="p-4 bg-light bg-opacity-50 border-top border-white text-center">
                        <p class="text-muted small mb-2">Bagikan prestasi ini:</p>
                        <div class="d-flex justify-content-center gap-2">
                            <button class="btn btn-sm btn-outline-primary rounded-circle" style="width: 40px; height: 40px;"><i class="bi bi-facebook"></i></button>
                            <button class="btn btn-sm btn-outline-info rounded-circle" style="width: 40px; height: 40px;"><i class="bi bi-twitter"></i></button>
                            <button class="btn btn-sm btn-outline-success rounded-circle" style="width: 40px; height: 40px;"><i class="bi bi-whatsapp"></i></button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

{{-- Style Khusus Halaman Ini --}}
<style>
    /* Styling Glass Card Detail */
    .glass-card {
        background: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        box-shadow: 0 25px 50px -12px rgba(31, 38, 135, 0.15);
        border-radius: 24px;
        transition: transform 0.3s ease;
    }
    
    .text-dark-blue {
        color: #0d2a54;
    }
    
    .text-shadow {
        text-shadow: 0 2px 10px rgba(0,0,0,0.5);
    }

    /* Animasi tombol kembali */
    .hover-scale {
        transition: transform 0.2s;
    }
    .hover-scale:hover {
        transform: translateX(-5px);
    }

    /* Animasi Masuk */
    .animate-on-scroll {
        animation: fadeInUp 0.8s ease-out forwards;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection