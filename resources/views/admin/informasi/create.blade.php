@extends('layouts.dashboard')

@section('title', 'Tambah Informasi')
@section('page', 'Tambah Informasi')

{{-- Tambahkan CDN untuk Summernote CSS --}}
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

@section('content')
<div class="max-w-4xl mx-auto animate-fade-in">
    {{-- KARTU FORM UTAMA --}}
    <div class="bg-white p-8 rounded-2xl shadow-3xl border-t-4 border-indigo-600">
        
        {{-- HEADER FORM --}}
        <h1 class="text-3xl font-extrabold text-gray-900 mb-6 flex items-center border-b-2 border-indigo-100 pb-3">
            <i class="fas fa-plus-circle text-indigo-600 mr-3"></i> Tambah Data **{{ ucfirst($activeCategory) }}**
        </h1>
        
        <form action="{{ route('admin.informasi.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            {{-- Kategori Hidden (TIDAK DIUBAH) --}}
            <input type="hidden" name="kategori" value="{{ $activeCategory }}">

            <div class="space-y-8">
                {{-- Field Judul --}}
                <div class="field-group">
                    <label for="judul" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-heading mr-2 text-indigo-500"></i> Judul / Nama <span class="text-red-500 ml-1">*</span>
                    </label>
                    <input type="text" name="judul" id="judul" value="{{ old('judul') }}" required
                            class="block w-full border-2 border-gray-200 rounded-xl shadow-md p-3 transition duration-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 hover:border-indigo-400">
                    @error('judul')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Field Isi / Konten (Summernote) --}}
                <div class="field-group">
                    <label for="isi" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-align-left mr-2 text-indigo-500"></i> Isi / Konten <span class="text-red-500 ml-1">*</span>
                    </label>
                    {{-- Style pada Textarea diabaikan karena Summernote akan me-render ulang --}}
                    <textarea name="isi" id="isi" rows="10" 
                                class="block w-full border-2 border-gray-200 rounded-xl shadow-md p-3 transition duration-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 hover:border-indigo-400">{{ old('isi') }}</textarea>
                    @error('isi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- GRID UNTUK GAMBAR & TANGGAL --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 border border-gray-100 rounded-xl bg-gray-50 shadow-inner">
                    
                    {{-- Field Gambar --}}
                    <div class="field-group">
                        <label for="gambar" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                             <i class="fas fa-image mr-2 text-green-500"></i> Gambar (Opsional)
                        </label>
                        <input type="file" name="gambar" id="gambar" accept="image/*"
                                class="block w-full text-sm text-gray-600 
                                       file:mr-4 file:py-2 file:px-4 
                                       file:rounded-full file:border-0 
                                       file:text-sm file:font-semibold 
                                       file:bg-green-100 file:text-green-700 
                                       hover:file:bg-green-200 transition duration-150">
                        @error('gambar')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Maks. 2MB (jpg, jpeg, png). Ukuran ideal 16:9.</p>
                    </div>
                    
                    {{-- Field Tanggal --}}
                    <div class="field-group">
                        <label for="tanggal" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-calendar-alt mr-2 text-purple-500"></i> Tanggal Terbit (Opsional)
                        </label>
                        <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal') ?? now()->format('Y-m-d') }}"
                                class="block w-full border-2 border-gray-200 rounded-xl shadow-md p-3 transition duration-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 hover:border-indigo-400">
                        @error('tanggal')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex justify-end space-x-4 pt-4 border-t-2 border-gray-100 mt-6">
                    {{-- Tombol Batal --}}
                    <a href="{{ route('admin.informasi', ['kategori' => $activeCategory]) }}" 
                       class="inline-flex items-center px-6 py-2.5 border border-gray-300 text-sm font-bold rounded-xl text-gray-700 bg-white hover:bg-gray-100 transition duration-150 shadow-md transform hover:scale-[1.02]">
                        <i class="fas fa-times mr-2"></i> Batal
                    </a>
                    {{-- Tombol Simpan --}}
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-2.5 border border-transparent text-sm font-bold rounded-xl shadow-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 transform hover:scale-[1.02]">
                        <i class="fas fa-save mr-2"></i> Simpan Data
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- SCRIPT SUMMERNOTE (TIDAK DIUBAH) --}}
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        $('#isi').summernote({
            placeholder: 'Masukkan isi/konten informasi di sini...',
            tabsize: 2,
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture']],
                ['view', ['codeview', 'help']]
            ]
        });
    });
</script>
@endsection