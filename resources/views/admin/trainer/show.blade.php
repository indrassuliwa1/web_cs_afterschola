@extends('layouts.dashboard')

@section('title', 'Detail Trainer')
@section('page', 'Detail Trainer')

@section('content')
<div class="max-w-4xl mx-auto animate-fade-in">
    
    {{-- Header dan Tombol Kembali --}}
    <div class="flex justify-between items-center mb-8 border-b-4 border-indigo-100 pb-4">
        <h1 class="text-4xl font-black text-gray-900 flex items-center">
            <i class="fas fa-id-card-alt text-indigo-600 mr-3"></i> Detail Profil Trainer
        </h1>
        {{-- Kembali ke tab Informasi Trainer (LINK ASLI ANDA) --}}
        <a href="{{ route('admin.informasi', ['kategori' => 'trainer']) }}" 
           class="flex items-center bg-gray-500 hover:bg-gray-700 text-white font-bold px-5 py-2.5 rounded-full transition duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
        </a>
    </div>

    {{-- KARTU PROFIL TRAINER (Lebih Dinamis) --}}
    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-3xl shadow-3xl border-t-8 border-indigo-600 border-b border-gray-100 overflow-hidden transition duration-500 hover:shadow-4xl">
            
            {{-- Gambar Profil (Circular Style dalam Card) --}}
            <div class="p-8 pb-4 flex justify-center">
                <div class="w-64 h-64 rounded-full overflow-hidden shadow-2xl border-6 border-indigo-200 transform hover:scale-105 transition duration-500">
@if ($trainer->foto)
    <img src="{{ asset('uploads/trainer_photos/' . $trainer->foto) }}" 
         alt="{{ $trainer->nama }}" 
         class="w-full h-full object-cover">
@else
...
                        <div class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-500 text-xl font-bold">
                            <i class="fas fa-camera text-4xl"></i>
                        </div>
                    @endif
                </div>
            </div>

            <div class="text-center p-6 pt-0">
                {{-- Nama Trainer --}}
                <h2 class="text-4xl font-extrabold text-gray-900 mb-2 mt-4">{{ $trainer->nama }}</h2>
                
                {{-- Spesialisasi (Badge Dinamis) --}}
                <p class="text-xl text-yellow-600 font-extrabold mb-8 inline-block bg-yellow-100 px-4 py-1 rounded-full shadow-inner border border-yellow-300">
                    <i class="fas fa-chalkboard-teacher mr-1"></i> {{ $trainer->spesialisasi }}
                </p>

                {{-- Deskripsi/Pengalaman --}}
                <div class="mt-4 text-sm text-gray-700 text-left border-t pt-6 px-4 bg-gray-50 rounded-xl">
                    <h4 class="font-bold mb-4 text-xl text-gray-800 flex items-center border-b pb-2">
                        <i class="fas fa-clipboard-list mr-2 text-indigo-600"></i> Deskripsi & Pengalaman
                    </h4>
                    <div class="prose max-w-none text-gray-700 leading-relaxed">
                        {!! $trainer->deskripsi !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Footer Aksi (Style yang Konsisten dan Dinamis) --}}
    <div class="flex justify-center space-x-4 mt-10">
        {{-- Tombol Edit (Warna Kuning/Amber sesuai permintaan sebelumnya) --}}
        <a href="{{ route('admin.trainer.edit', $trainer->id) }}" 
            class="flex items-center bg-yellow-600 hover:bg-yellow-700 text-white font-bold px-6 py-2.5 rounded-xl shadow-lg transition duration-200 transform hover:scale-105 hover:shadow-xl">
            <i class="fas fa-pen-to-square mr-2"></i> Edit Data
        </a>
        
        {{-- Tombol Hapus (Warna Merah) --}}
        <form action="{{ route('admin.trainer.destroy', $trainer->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus Trainer ini?');" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="flex items-center bg-red-600 hover:bg-red-700 text-white font-bold px-6 py-2.5 rounded-xl shadow-lg transition duration-200 transform hover:scale-105 hover:shadow-xl">
                <i class="fas fa-trash-can mr-2"></i> Hapus Trainer
            </button>
        </form>
    </div>
</div>

{{-- Memastikan jQuery dan Summernote dimuat untuk rendering deskripsi yang aman (TIDAK DIUBAH) --}}
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
@endsection