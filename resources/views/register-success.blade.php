@extends('layouts.app')

@section('content')
    <section class="section-padding py-5" style="background-color: #f8f9fa;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">

                    <div class="bg-white shadow-sm rounded-4 border p-4 p-md-5 form-panel fade-in-up">
                        <!-- Ikon Sukses -->
                        <div class="mb-4">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                        </div>

                        <!-- Judul -->
                        <h1 class="display-6 fw-bold text-dark-blue">Pendaftaran Berhasil!</h1>

                        <!-- Pesan -->
                        <p class="lead text-muted mt-3">
                            Terima kasih telah melakukan pendaftaran. <br>
                            Bukti pembayaran Anda telah kami terima dan akan segera kami proses. <br>
                            Kami akan menghubungi Anda melalui WhatsApp atau email jika diperlukan.
                        </p>

                        <!-- Tombol Kembali -->
                        <a href="{{ url('/') }}" class="btn btn-dark-blue rounded-pill btn-lg px-4 mt-4">
                            <i class="bi bi-house-door me-2"></i> Kembali ke Beranda
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <style>
        .btn-dark-blue {
            background-color: #003366;
            color: #fff;
            transition: 0.3s ease;
        }

        .btn-dark-blue:hover {
            background-color: #001f4d;
            color: #fff;
        }

        .form-panel {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .text-dark-blue {
            color: #003366 !important;
        }

        /* Animasi Fade In */
        .fade-in-up {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.8s ease forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection
