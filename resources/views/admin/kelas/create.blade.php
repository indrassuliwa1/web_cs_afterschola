@extends('layouts.dashboard')

@section('title', 'Tambah Kelas')
@section('page', 'Tambah Kelas')

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-2xl border border-gray-100">
    
    <h1 class="text-3xl font-extrabold text-gray-800 mb-6 border-b pb-2">
        <i class="fas fa-plus mr-2 text-green-600"></i> Tambah Kelas Baru
    </h1>

    <form action="{{ route('admin.kelas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="space-y-6">
            {{-- Field Nama Kelas --}}
            <div>
                <label for="nama_kelas" class="block text-sm font-medium text-gray-700 mb-1">Nama Kelas <span class="text-red-500">*</span></label>
                <input type="text" name="nama_kelas" id="nama_kelas" value="{{ old('nama_kelas') }}" required
                       class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500">
                @error('nama_kelas')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Field Harga --}}
                <div>
                    <label for="harga" class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="harga" id="harga" value="{{ old('harga') }}" required min="0"
                           class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500">
                    @error('harga')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Field Foto Kelas --}}
                <div>
                    <label for="foto_kelas" class="block text-sm font-medium text-gray-700 mb-1">Foto Kelas (Opsional)</label>
                    <input type="file" name="foto_kelas" id="foto_kelas" accept="image/*"
                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    @error('foto_kelas')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Maks. 2MB (jpg, jpeg, png).</p>
                </div>
            </div>

            {{-- Field Deskripsi (Summernote) --}}
            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Kelas <span class="text-red-500">*</span></label>
                <textarea name="deskripsi" id="deskripsi" rows="10" 
                          class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm p-3 focus:ring-blue-500 focus:border-blue-500">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end space-x-4 pt-4 border-t mt-6">
                <a href="{{ route('admin.kelas.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition duration-150">
                    Batal
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150">
                    <i class="fas fa-save mr-2"></i> Simpan Kelas
                </button>
            </div>
        </div>
    </form>
</div>

{{-- SCRIPT SUMMERNOTE --}}
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        $('#deskripsi').summernote({
            placeholder: 'Masukkan deskripsi lengkap tentang kelas ini...',
            tabsize: 2,
            height: 200,
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
