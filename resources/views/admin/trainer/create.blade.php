@extends('layouts.dashboard')

@section('title', 'Tambah Trainer')
@section('page', 'Tambah Trainer')

{{-- Tambahkan CDN untuk Summernote CSS --}}
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

@section('content')
<div class="max-w-4xl mx-auto animate-fade-in">
    {{-- KARTU FORM UTAMA --}}
    <div class="bg-white p-8 rounded-2xl shadow-3xl border-t-4 border-indigo-600">
        
        {{-- HEADER FORM --}}
        <h1 class="text-3xl font-extrabold text-gray-900 mb-6 flex items-center border-b-2 border-indigo-100 pb-3">
            <i class="fas fa-user-plus text-indigo-600 mr-3"></i> Tambah Data Trainer
        </h1>

        <form action="{{ route('admin.trainer.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-8">
                
                {{-- Field Nama --}}
                <div class="field-group">
                    <label for="nama" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-id-badge mr-2 text-indigo-500"></i> Nama Trainer <span class="text-red-500 ml-1">*</span>
                    </label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                            class="block w-full border-2 border-gray-200 rounded-xl shadow-md p-3 transition duration-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 hover:border-indigo-400">
                    @error('nama')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Field Spesialisasi --}}
                <div class="field-group">
                    <label for="spesialisasi" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-star mr-2 text-indigo-500"></i> Spesialisasi <span class="text-red-500 ml-1">*</span>
                    </label>
                    <input type="text" name="spesialisasi" id="spesialisasi" value="{{ old('spesialisasi') }}" required
                            class="block w-full border-2 border-gray-200 rounded-xl shadow-md p-3 transition duration-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 hover:border-indigo-400">
                    @error('spesialisasi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Field Deskripsi (Summernote) --}}
                <div class="field-group">
                    <label for="deskripsi" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-pencil-alt mr-2 text-indigo-500"></i> Deskripsi & Pengalaman <span class="text-red-500 ml-1">*</span>
                    </label>
                    {{-- Style pada Textarea diabaikan karena Summernote akan me-render ulang --}}
                    <textarea name="deskripsi" id="deskripsi" rows="10" required
                                class="block w-full border-2 border-gray-200 rounded-xl shadow-md p-3 transition duration-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 hover:border-indigo-400">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Field Foto --}}
                <div class="field-group p-4 border border-gray-100 rounded-xl bg-gray-50 shadow-inner">
                    <label for="foto" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-camera-retro mr-2 text-green-500"></i> Foto Trainer <span class="text-red-500 ml-1">*</span>
                    </label>
                    <input type="file" name="foto" id="foto" accept="image/*" required
                            class="block w-full text-sm text-gray-600 
                                   file:mr-4 file:py-2 file:px-4 
                                   file:rounded-full file:border-0 
                                   file:text-sm file:font-semibold 
                                   file:bg-green-100 file:text-green-700 
                                   hover:file:bg-green-200 transition duration-150">
                    @error('foto')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Maks. 2MB (jpg, jpeg, png).</p>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex justify-end space-x-4 pt-4 border-t-2 border-gray-100 mt-6">
                    {{-- Tombol Batal --}}
                    <a href="{{ route('admin.informasi', ['kategori' => 'trainer']) }}" 
                       class="inline-flex items-center px-6 py-2.5 border border-gray-300 text-sm font-bold rounded-xl text-gray-700 bg-white hover:bg-gray-100 transition duration-150 shadow-md transform hover:scale-[1.02]">
                        <i class="fas fa-times mr-2"></i> Batal
                    </a>
                    {{-- Tombol Simpan --}}
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-2.5 border border-transparent text-sm font-bold rounded-xl shadow-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 transform hover:scale-[1.02]">
                        <i class="fas fa-save mr-2"></i> Simpan Trainer
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
        $('#deskripsi').summernote({
            placeholder: 'Masukkan deskripsi dan pengalaman Trainer...',
            tabsize: 2,
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']],
                ['view', ['codeview', 'help']]
            ]
        });
    });
</script>
@endsection