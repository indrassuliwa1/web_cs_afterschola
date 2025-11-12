@extends('layouts.dashboard')

@section('title', 'Detail Kelas')
@section('page', 'Detail Kelas')

@section('content')
<div class="max-w-5xl mx-auto">
    
    {{-- Header dan Tombol Kembali --}}
    <div class="flex justify-between items-center mb-6 border-b pb-4">
        <h1 class="text-3xl font-extrabold text-gray-800">
            <i class="fas fa-chalkboard text-blue-600 mr-2"></i> Detail Kelas
        </h1>
        <a href="{{ route('admin.kelas.index') }}" 
           class="flex items-center bg-gray-500 hover:bg-gray-600 text-white font-semibold px-4 py-2 rounded-lg transition duration-200 shadow-md">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
        </a>
    </div>

    {{-- Kartu Konten Utama --}}
    <div class="bg-white p-8 rounded-xl shadow-2xl border border-gray-100">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Kolom Kiri: Foto Utama dan Meta --}}
            <div class="lg:col-span-1 space-y-6">
                
                {{-- Foto Kelas --}}
                <div class="rounded-xl overflow-hidden shadow-lg border border-gray-200 bg-gray-50 p-4">
                    <h3 class="text-lg font-bold text-gray-800 mb-3 text-center">Foto Utama</h3>
                    @if ($kelas->foto_kelas)
                        <img src="{{ asset('uploads/kelas/' . $kelas->foto_kelas) }}" 
                             alt="{{ $kelas->nama_kelas }}" 
                             class="w-full h-48 object-cover rounded-lg shadow-md">
                    @else
                        <div class="w-full h-48 flex items-center justify-center bg-gray-200 text-gray-600 rounded-lg">
                            Tidak Ada Foto
                        </div>
                    @endif
                </div>

                {{-- Ringkasan Kontrak --}}
                <div class="bg-indigo-50 p-4 rounded-xl shadow-inner border border-indigo-200">
                    <p class="text-sm text-indigo-800 font-semibold mb-1">Total Kontrak Terkait:</p>
                    <p class="text-3xl font-extrabold text-indigo-700">
                        {{ $kelas->kontrak()->count() }}
                    </p>
                    <span class="text-xs text-indigo-600">Kontrak menggunakan kelas ini.</span>
                </div>
            </div>

            {{-- Kolom Kanan: Detail & Deskripsi --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Judul dan Harga --}}
                <div class="border-b pb-4">
                    <h2 class="text-4xl font-extrabold text-gray-900 mb-2">{{ $kelas->nama_kelas }}</h2>
                    <p class="text-2xl font-bold text-green-600">
                        Rp {{ number_format($kelas->harga, 0, ',', '.') }}
                    </p>
                </div>

                {{-- Detail Metadata --}}
                <div class="grid grid-cols-2 gap-4 text-sm text-gray-700">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-id-badge text-blue-500"></i>
                        <span class="font-semibold">ID Kelas: {{ $kelas->id }}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-calendar-alt text-gray-500"></i>
                        <span>Dibuat: {{ \Carbon\Carbon::parse($kelas->created_at)->format('d M Y') }}</span>
                    </div>
                </div>

                {{-- Deskripsi Konten --}}
                <div class="pt-4 border-t border-gray-200">
                    <h3 class="text-xl font-bold mb-3 text-gray-800">Deskripsi Kelas:</h3>
                    <div class="prose max-w-none text-gray-700 leading-relaxed">
                        {{-- Menggunakan {!! !!} karena deskripsi dari Summernote --}}
                        {!! $kelas->deskripsi !!} 
                    </div>
                </div>
            </div>
            
        </div>
        
        {{-- Footer Aksi --}}
        <div class="flex justify-end space-x-3 pt-4 border-t mt-8">
             {{-- Tombol Edit --}}
            <a href="{{ route('admin.kelas.edit', $kelas->id) }}" 
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded-lg shadow-md transition duration-150">
                <i class="fas fa-edit mr-2"></i> Edit Data
            </a>
            
            {{-- Tombol Hapus --}}
            <form action="{{ route('admin.kelas.destroy', $kelas->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kelas ini? Tindakan ini akan mempengaruhi kontrak terkait.');" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold px-4 py-2 rounded-lg shadow-md transition duration-150">
                    <i class="fas fa-trash-alt mr-2"></i> Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
