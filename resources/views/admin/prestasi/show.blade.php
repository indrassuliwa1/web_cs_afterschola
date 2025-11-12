@extends('layouts.dashboard')

@section('title', 'Detail Prestasi')
@section('page', 'Detail Prestasi')

@section('content')
<div class="max-w-4xl mx-auto animate-fade-in">
    
    {{-- Header dan Tombol Kembali --}}
    <div class="flex justify-between items-center mb-8 border-b-4 border-yellow-100 pb-4">
        <h1 class="text-4xl font-black text-gray-900 flex items-center">
            <i class="fas fa-medal text-yellow-600 mr-3"></i> Detail Prestasi
        </h1>
        <a href="{{ route('admin.informasi', ['kategori' => 'prestasi']) }}" 
           class="flex items-center bg-gray-500 hover:bg-gray-700 text-white font-bold px-5 py-2.5 rounded-full transition duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    {{-- Kartu Konten Utama --}}
    <div class="bg-white p-8 rounded-2xl shadow-3xl border-t-4 border-yellow-600">
        
        {{-- Judul --}}
        <h2 class="text-4xl font-extrabold text-gray-900 mb-5 leading-tight">{{ $prestasi->judul }}</h2>
        
        {{-- Meta Data --}}
        <div class="text-sm text-gray-500 mb-8 flex items-center space-x-6 border-b pb-3">
            <span class="flex items-center p-2 bg-green-50 rounded-lg shadow-sm">
                <i class="fas fa-calendar-check mr-2 text-green-600 text-lg"></i>
                Tanggal Dicapai: <strong class="ml-1 text-green-800 font-bold">{{ \Carbon\Carbon::parse($prestasi->tanggal_dicapai)->format('d F Y') }}</strong>
            </span>
            <span class="flex items-center p-2 bg-purple-50 rounded-lg shadow-sm">
                <i class="fas fa-user-edit mr-2 text-purple-600 text-lg"></i>
                Penulis: <strong class="ml-1 text-purple-800 font-bold">{{ $prestasi->author->name ?? 'Admin' }}</strong>
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            {{-- KOLOM FOTO UTAMA (1/3) --}}
            <div class="md:col-span-1 order-2 md:order-1">
                <div class="bg-white p-4 rounded-xl shadow-xl border-2 border-gray-100 transition duration-300 hover:shadow-2xl">
                    <h3 class="text-xl font-bold mb-3 text-gray-800 text-center border-b pb-2">Bukti Foto Utama</h3>
                    
                    @if ($prestasi->bukti_foto)
                        <div class="rounded-lg overflow-hidden shadow-lg border border-gray-100 text-center">
                            <img src="{{ asset('uploads/prestasi/' . $prestasi->bukti_foto) }}" 
                                 alt="Foto Utama Prestasi" 
                                 class="w-full h-auto object-cover max-h-72 transform hover:scale-[1.05] transition duration-500">
                        </div>
                        <a href="{{ asset('uploads/prestasi/' . $prestasi->bukti_foto) }}" target="_blank" 
                           class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 hover:underline block mt-3 text-center">
                            <i class="fas fa-external-link-alt mr-1"></i> Lihat Ukuran Penuh
                        </a>
                    @else
                        <div class="h-32 flex items-center justify-center bg-gray-100 rounded-lg">
                            <p class="text-gray-500 italic text-sm">Tidak ada foto utama.</p>
                        </div>
                    @endif
                </div>
            </div>
            
            {{-- KOLOM DESKRIPSI & DOKUMENTASI (2/3) --}}
            <div class="md:col-span-2 order-1 md:order-2">
                
                {{-- Deskripsi Konten (Dari Summernote) --}}
                <div class="prose prose-indigo max-w-none text-gray-700 leading-relaxed mb-8 p-4 border-l-4 border-yellow-400 bg-yellow-50/50 rounded-r-lg">
                    <h3 class="text-2xl font-extrabold mb-3 text-gray-900 flex items-center">
                        <i class="fas fa-file-alt mr-2"></i> Detail Deskripsi
                    </h3>
                    {!! $prestasi->deskripsi !!} 
                </div>

                {{-- Dokumentasi Tambahan --}}
                @if ($prestasi->dokumentasi && count($prestasi->dokumentasi) > 0)
                <div class="mt-4 p-6 bg-gray-50 rounded-xl border border-gray-200 shadow-inner">
                    <h3 class="text-xl font-bold mb-4 text-gray-800 flex items-center">
                        <i class="fas fa-images mr-2 text-indigo-600"></i> Dokumentasi Pendukung
                    </h3>
                    <div class="flex flex-wrap gap-4">
                        @foreach ($prestasi->dokumentasi as $file)
                            <div class="w-32 h-32 relative rounded-lg overflow-hidden border-2 border-gray-200 shadow-md transition duration-200 hover:shadow-lg transform hover:scale-[1.05]">
                                <a href="{{ asset('uploads/prestasi/dokumentasi/' . $file) }}" target="_blank" title="Lihat Dokumen">
                                    <img src="{{ asset('uploads/prestasi/dokumentasi/' . $file) }}" class="w-full h-full object-cover" alt="Dokumen">
                                    {{-- Overlay pada gambar kecil --}}
                                    <div class="absolute inset-0 bg-black bg-opacity-10 flex items-center justify-center opacity-0 hover:opacity-100 transition duration-200">
                                        <i class="fas fa-search-plus text-white text-xl"></i>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            
        </div>
        
        {{-- Footer Aksi --}}
        <div class="flex justify-end space-x-4 pt-6 border-t-2 border-gray-100 mt-8">
            {{-- Tombol Edit (Kuning/Amber sesuai konsistensi) --}}
            <a href="{{ route('admin.prestasi.edit', $prestasi->id) }}" 
                class="flex items-center bg-yellow-600 hover:bg-yellow-700 text-white font-bold px-6 py-2.5 rounded-xl shadow-lg transition duration-200 transform hover:scale-105 hover:shadow-xl">
                <i class="fas fa-pen-to-square mr-2"></i> Edit Data
            </a>
            
            {{-- Tombol Hapus (Merah) --}}
            <form action="{{ route('admin.prestasi.destroy', $prestasi->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus prestasi ini?');" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="flex items-center bg-red-600 hover:bg-red-700 text-white font-bold px-6 py-2.5 rounded-xl shadow-lg transition duration-200 transform hover:scale-105 hover:shadow-xl">
                    <i class="fas fa-trash-can mr-2"></i> Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection