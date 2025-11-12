@extends('layouts.dashboard')

@section('title', 'Edit Prestasi')
@section('page', 'Edit Prestasi')

{{-- Tambahkan CDN untuk Summernote CSS --}}
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">

@section('content')
<div class="max-w-4xl mx-auto animate-fade-in">
    {{-- KARTU FORM UTAMA --}}
    <div class="bg-white p-8 rounded-2xl shadow-3xl border-t-4 border-yellow-600">
        
        {{-- HEADER FORM --}}
        <h1 class="text-3xl font-extrabold text-gray-900 mb-6 flex items-center border-b-2 border-yellow-100 pb-3">
            <i class="fas fa-trophy text-yellow-600 mr-3"></i> Edit Data Prestasi: **{{ $prestasi->judul }}**
        </h1>

        <form action="{{ route('admin.prestasi.update', $prestasi->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="space-y-8">
                {{-- Field Judul Prestasi --}}
                <div class="field-group">
                    <label for="judul" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-heading mr-2 text-indigo-500"></i> Judul Prestasi <span class="text-red-500 ml-1">*</span>
                    </label>
                    <input type="text" name="judul" id="judul" value="{{ old('judul', $prestasi->judul) }}" required
                           class="block w-full border-2 border-gray-200 rounded-xl shadow-md p-3 transition duration-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 hover:border-indigo-400">
                    @error('judul')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 border border-gray-100 rounded-xl bg-gray-50 shadow-inner">
                    
                    {{-- Field Tanggal Dicapai --}}
                    <div class="field-group">
                        <label for="tanggal_dicapai" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-calendar-check mr-2 text-purple-500"></i> Tanggal Dicapai <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="date" name="tanggal_dicapai" id="tanggal_dicapai" 
                               value="{{ old('tanggal_dicapai', \Carbon\Carbon::parse($prestasi->tanggal_dicapai)->format('Y-m-d')) }}" required
                               class="block w-full border-2 border-gray-200 rounded-xl shadow-md p-3 transition duration-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 hover:border-indigo-400">
                        @error('tanggal_dicapai')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Field Bukti Foto (UTAMA) --}}
                    <div class="field-group">
                        <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                            <i class="fas fa-image mr-2 text-green-500"></i> Foto Utama Saat Ini
                        </label>
                        @if ($prestasi->bukti_foto)
                            <img src="{{ asset('uploads/prestasi/' . $prestasi->bukti_foto) }}" alt="{{ $prestasi->judul }}" 
                                 class="h-24 w-24 object-cover rounded-xl mb-3 border-2 border-gray-300 shadow-md">
                        @else
                            <div class="h-10 flex items-center text-xs text-gray-500 mb-3 p-2 border rounded-md bg-white">
                                <i class="fas fa-minus-circle mr-2"></i> Tidak ada foto utama terpasang.
                            </div>
                        @endif

                        <label for="bukti_foto" class="block text-sm font-medium text-gray-700 mb-2">Ganti Foto Utama (Opsional)</label>
                        <input type="file" name="bukti_foto" id="bukti_foto" accept="image/*"
                               class="block w-full text-sm text-gray-600 
                                       file:mr-4 file:py-2 file:px-4 
                                       file:rounded-full file:border-0 
                                       file:text-sm file:font-semibold 
                                       file:bg-green-100 file:text-green-700 
                                       hover:file:bg-green-200 transition duration-150">
                        @error('bukti_foto')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Foto utama untuk ditampilkan di daftar. Maks. 2MB.</p>
                    </div>
                </div>
            
                {{-- Field Dokumentasi Tambahan (Multi File) --}}
                <div class="field-group p-4 border border-gray-100 rounded-xl bg-gray-50 shadow-inner">
                    <label class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-images mr-2 text-teal-600"></i> Dokumentasi Tambahan Saat Ini
                    </label>
                    
                    {{-- Tampilkan Dokumentasi Lama --}}
                    @if ($prestasi->dokumentasi && count($prestasi->dokumentasi) > 0)
                        <div class="flex flex-wrap gap-3 mb-4">
                            @foreach ($prestasi->dokumentasi as $file)
                                <div class="w-24 h-24 relative rounded-xl overflow-hidden border-2 border-gray-300 shadow-md">
                                    <img src="{{ asset('uploads/prestasi/dokumentasi/' . $file) }}" class="w-full h-full object-cover" alt="Dokumen Lama">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-gray-500 mb-4">Belum ada dokumentasi tambahan.</p>
                    @endif


                    <label for="dokumentasi" class="block text-sm font-medium text-gray-700 mb-2 border-t pt-3">Tambahkan Dokumentasi Baru (Opsional)</label>
                    {{-- Menggunakan name="dokumentasi[]" dan multiple (TIDAK DIUBAH) --}}
                    <input type="file" name="dokumentasi[]" id="dokumentasi" multiple accept="image/*"
                           class="block w-full text-sm text-gray-600 
                                   file:mr-4 file:py-2 file:px-4 
                                   file:rounded-full file:border-0 
                                   file:text-sm file:font-semibold 
                                   file:bg-teal-100 file:text-teal-700 
                                   hover:file:bg-teal-200 transition duration-150">
                    @error('dokumentasi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    @error('dokumentasi.*')
                        <p class="text-red-500 text-xs mt-1">Ada kesalahan pada salah satu file dokumentasi.</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Anda dapat memilih beberapa file untuk ditambahkan (Maks. 2MB per file).</p>
                </div>


                {{-- Field Deskripsi (Summernote) --}}
                <div class="field-group">
                    <label for="deskripsi" class="block text-sm font-bold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-align-left mr-2 text-indigo-500"></i> Deskripsi Prestasi <span class="text-red-500 ml-1">*</span>
                    </label>
                    {{-- Style pada Textarea diabaikan karena Summernote akan me-render ulang --}}
                    <textarea name="deskripsi" id="deskripsi" rows="10" required
                              class="block w-full border-2 border-gray-200 rounded-xl shadow-md p-3 transition duration-300 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 hover:border-indigo-400">{{ old('deskripsi', $prestasi->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex justify-end space-x-4 pt-4 border-t-2 border-gray-100 mt-6">
                    {{-- Tombol Batal --}}
                    <a href="{{ route('admin.informasi', ['kategori' => 'prestasi']) }}" 
                       class="inline-flex items-center px-6 py-2.5 border border-gray-300 text-sm font-bold rounded-xl text-gray-700 bg-white hover:bg-gray-100 transition duration-150 shadow-md transform hover:scale-[1.02]">
                        <i class="fas fa-times mr-2"></i> Batal
                    </a>
                    {{-- Tombol Perbarui (Warna Biru Tua) --}}
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-2.5 border border-transparent text-sm font-bold rounded-xl shadow-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 transform hover:scale-[1.02]">
                        <i class="fas fa-save mr-2"></i> Perbarui Prestasi
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
            placeholder: 'Masukkan detail dan deskripsi prestasi...',
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