@extends('layouts.dashboard')

@section('title', 'Detail Informasi')
@section('page', 'Detail Informasi')

@section('content')
    <div class="max-w-4xl mx-auto animate-fade-in">

        {{-- Header dan Tombol Kembali --}}
        <div class="flex justify-between items-center mb-8 border-b-4 border-indigo-100 pb-4">
            <h1 class="text-4xl font-black text-gray-900 flex items-center">
                <i class="fas fa-bullseye text-indigo-600 mr-3"></i> Detail Informasi
            </h1>
            <a href="{{ route('admin.informasi', ['kategori' => $informasi->kategori]) }}"
                class="flex items-center bg-indigo-500 hover:bg-indigo-700 text-white font-bold px-5 py-2.5 rounded-full transition duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
            </a>
        </div>

        {{-- KARTU KONTEN UTAMA --}}
        <div
            class="bg-white p-8 md:p-10 rounded-3xl shadow-3xl border-t-4 border-indigo-600 transition duration-500 hover:shadow-4xl">

            {{-- Logika untuk Trainer vs. Berita/Prestasi --}}
            @if ($informasi->kategori == 'trainer')

                {{-- TAMPILAN KARTU PROFIL TRAINER (Lebih fokus pada visual dan bio) --}}
                <div class="max-w-md mx-auto text-center">

                    {{-- Gambar Profil --}}
                    <div
                        class="mb-6 w-56 h-56 mx-auto rounded-full overflow-hidden shadow-2xl border-6 border-indigo-100 transform hover:scale-105 transition duration-500">
                        @if ($informasi->gambar)
                            <img src="{{ asset('uploads/informasi/' . $informasi->gambar) }}" alt="{{ $informasi->judul }}"
                                class="w-full h-full object-cover">
                        @else
                            <div
                                class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-500 text-xl font-bold">
                                No Photo
                            </div>
                        @endif
                    </div>

                    {{-- Nama Trainer (Menggunakan kolom Judul) --}}
                    <h2 class="text-4xl font-black text-gray-900 mb-2">{{ $informasi->judul }}</h2>

                    {{-- Spesialisasi (Menggunakan baris pertama kolom Isi/Konten) --}}
                    @php
                        $spesialisasi = strip_tags(explode("\n", $informasi->isi)[0]);
                    @endphp
                    <p
                        class="text-xl text-yellow-600 font-extrabold mb-8 inline-block bg-yellow-100 px-4 py-1 rounded-full shadow-inner">
                        {{ $spesialisasi }}</p>

                    {{-- Deskripsi (Konten Isi sisanya) --}}
                    <div class="mt-6 p-6 bg-gray-50 rounded-xl text-left border border-gray-200">
                        <h4 class="font-bold text-xl mb-3 text-gray-800 border-b pb-2 flex items-center">
                            <i class="fas fa-medal mr-2 text-yellow-600"></i> Biografi & Keahlian
                        </h4>
                        <div class="prose max-w-none text-gray-700 leading-relaxed">
                            {{-- Catatan: Jika Anda ingin memotong baris pertama dari isi, Anda perlu memodifikasi logika PHP di atas --}}
                            {!! $informasi->isi !!}
                        </div>
                    </div>
                </div>
            @else
                {{-- TAMPILAN STANDAR (Berita/Prestasi) --}}

                {{-- Judul dan Meta --}}
                <h2 class="text-4xl font-extrabold text-gray-900 mb-5 leading-tight">{{ $informasi->judul }}</h2>

                {{-- Meta Data dengan Ikon --}}
                <div class="text-sm text-gray-500 mb-8 grid grid-cols-1 md:grid-cols-3 gap-3 border-y py-3 border-gray-100">
                    <span class="flex items-center p-2 bg-blue-50 rounded-lg shadow-sm">
                        <i class="fas fa-tag mr-2 text-blue-600 text-lg"></i>
                        **Kategori:** <strong
                            class="ml-1 text-blue-800 font-bold">{{ ucfirst($informasi->kategori) }}</strong>
                    </span>
                    <span class="flex items-center p-2 bg-green-50 rounded-lg shadow-sm">
                        <i class="fas fa-calendar-alt mr-2 text-green-600 text-lg"></i>
                        **Tanggal:**
                        {{ \Carbon\Carbon::parse($informasi->tanggal ?? $informasi->created_at)->format('d F Y') }}
                    </span>
                    <span class="flex items-center p-2 bg-purple-50 rounded-lg shadow-sm">
                        <i class="fas fa-user-edit mr-2 text-purple-600 text-lg"></i>
                        **Penulis:** {{ $informasi->author->name ?? 'Admin' }}
                    </span>
                </div>

                {{-- Gambar Utama --}}
                @if ($informasi->gambar)
                    <div
                        class="mb-8 rounded-2xl overflow-hidden border-2 border-gray-100 shadow-xl transition duration-300 hover:shadow-2xl">
                        <img src="{{ asset('uploads/informasi/' . $informasi->gambar) }}" alt="{{ $informasi->judul }}"
                            class="w-full h-auto object-cover max-h-[450px]">
                    </div>
                @endif

                {{-- Isi Konten --}}
                <div
                    class="prose prose-indigo max-w-none text-gray-700 leading-relaxed text-lg mb-8 p-4 border-l-4 border-indigo-400 bg-indigo-50/50 rounded-r-lg">
                    {!! $informasi->isi !!}
                </div>

            @endif

            {{-- Footer Aksi (Sama untuk semua kategori) --}}
            <div class="flex justify-end space-x-4 pt-6 border-t-2 border-gray-100 mt-6">
                {{-- Tombol Edit --}}
                <a href="{{ route('admin.informasi.edit', [$informasi->id, 'kategori' => $informasi->kategori]) }}"
                    class="flex items-center bg-blue-600 hover:bg-blue-800 text-white font-bold px-6 py-2.5 rounded-xl shadow-lg transition duration-200 transform hover:scale-105 hover:shadow-xl">
                    <i class="fas fa-edit mr-2"></i> Edit Data
                </a>

                {{-- Tombol Hapus --}}
                <form action="{{ route('admin.informasi.destroy', $informasi->id) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus informasi ini secara permanen?');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="flex items-center bg-red-600 hover:bg-red-800 text-white font-bold px-6 py-2.5 rounded-xl shadow-lg transition duration-200 transform hover:scale-105 hover:shadow-xl">
                        <i class="fas fa-trash-alt mr-2"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
