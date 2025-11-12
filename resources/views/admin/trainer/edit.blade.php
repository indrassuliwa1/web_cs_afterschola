@extends('layouts.dashboard')

@section('title', 'Edit Trainer')
@section('page', 'Edit Trainer')

{{-- Tambahkan CDN untuk Summernote CSS --}}
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

@section('content')
<div class="max-w-4xl mx-auto animate-fade-in">
    {{-- KARTU FORM UTAMA --}}
    <div class="bg-white p-8 rounded-2xl shadow-3xl border-t-4 border-indigo-600">
        
        {{-- HEADER FORM --}}
        <h1 class="text-3xl font-extrabold text-gray-900 mb-6 flex items-center border-b-2 border-indigo-100 pb-3">
            <i class="fas fa-user-edit text-indigo-600 mr-3"></i> Edit Data Trainer: **{{ $trainer->nama }}**
        </h1>

        <form action="{{ route('admin.trainer.update', $trainer->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="space-y-8">
                
                {{-- Field Nama --}}
                <div class="field-group">
                    <label for="nama" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-id-badge mr-2 text-indigo-500"></i> Nama Trainer <span class="text-red-500 ml-1">*</span>
                    </label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $trainer->nama) }}" required
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
                    <input type="text" name="spesialisasi" id="spesialisasi" value="{{ old('spesialisasi', $trainer->spesialisasi) }}" required
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
                                class="block w-full border-2 border-gray-200 rounded-xl shadow-md p-3 transition duration-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 hover:border-indigo-400">{{ old('deskripsi', $trainer->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Field Foto --}}
                <div class="field-group p-4 border border-gray-100 rounded-xl bg-gray-50 shadow-inner">
                    <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-camera-retro mr-2 text-green-500"></i> Foto Saat Ini
                    </label>
@if ($trainer->foto)
    <img src="{{ asset('uploads/trainer_photos/' . $trainer->foto) }}" alt="{{ $trainer->nama }}" 
         class="h-24 w-24 object-cover rounded-xl mb-3 border-2 border-gray-300 shadow-md">
@else
...
                        <div class="h-10 flex items-center text-xs text-gray-500 mb-3 p-2 border rounded-md bg-white">
                            <i class="fas fa-minus-circle mr-2"></i> Tidak ada foto terpasang.
                        </div>
                    @endif
                    
                    <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">Ganti Foto (Opsional)</label>
                    <input type="file" name="foto" id="foto" accept="image/*"
                            class="block w-full text-sm text-gray-600 
                                   file:mr-4 file:py-2 file:px-4 
                                   file:rounded-full file:border-0 
                                   file:text-sm file:font-semibold 
                                   file:bg-green-100 file:text-green-700 
                                   hover:file:bg-green-200 transition duration-150">
                    @error('foto')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Maks. 2MB (jpg, jpeg, png). Akan menggantikan foto lama.</p>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex justify-end space-x-4 pt-4 border-t-2 border-gray-100 mt-6">
                    {{-- Tombol Batal --}}
                    <a href="{{ route('admin.informasi', ['kategori' => 'trainer']) }}" 
                       class="inline-flex items-center px-6 py-2.5 border border-gray-300 text-sm font-bold rounded-xl text-gray-700 bg-white hover:bg-gray-100 transition duration-150 shadow-md transform hover:scale-[1.02]">
                        <i class="fas fa-times mr-2"></i> Batal
                    </a>
                    {{-- Tombol Perbarui (Warna Biru Tua) --}}
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-2.5 border border-transparent text-sm font-bold rounded-xl shadow-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 transform hover:scale-[1.02]">
                        <i class="fas fa-save mr-2"></i> Perbarui Trainer
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