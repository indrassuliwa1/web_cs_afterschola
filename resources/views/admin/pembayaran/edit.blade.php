@extends('layouts.dashboard')

@section('title', 'Edit Pembayaran')
@section('page', 'Edit Pembayaran')

@section('content')
<div class="container mx-auto p-4 md:p-8 min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto">
        
        {{-- Header dan Tombol Kembali --}}
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h1 class="text-3xl font-extrabold text-gray-800">
                <i class="fas fa-edit text-blue-600 mr-2"></i> Edit Pembayaran #{{ $pembayaran->id }}
            </h1>
            <a href="{{ route('admin.pembayaran') }}" class="flex items-center bg-gray-500 hover:bg-gray-600 text-white font-semibold px-4 py-2 rounded-lg transition duration-200 shadow-md">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        {{-- Form Edit Pembayaran (PENTING: enctype="multipart/form-data" untuk upload file) --}}
        <form action="{{ route('admin.pembayaran.update', $pembayaran->id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-xl shadow-2xl space-y-6 border border-blue-100">
            @csrf
            @method('PUT')

            {{-- Menampilkan Error Validasi --}}
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Oops!</strong>
                    <span class="block sm:inline"> Ada beberapa kesalahan input:</span>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Pilih Kontrak --}}
                <div class="md:col-span-2">
                    <label for="kontrak_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Kontrak (Pendaftar) <span class="text-red-500">*</span></label>
                    <select name="kontrak_id" id="kontrak_id" class="w-full border-gray-300 border rounded-lg p-3 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 transition duration-150" required>
                        <option value="">-- Pilih Kontrak --</option>
                        @foreach($kontrak as $k)
                            @php $selectedKontrakId = old('kontrak_id', $pembayaran->kontrak_id); @endphp
                            <option value="{{ $k->id }}" {{ $selectedKontrakId == $k->id ? 'selected' : '' }}>
                                [#{{ $k->id }}] {{ $k->pendaftar->nama ?? 'Nama Tidak Ada' }} - {{ $k->nama_kontrak }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                {{-- Jumlah Bayar --}}
                <div>
                    <label for="jumlah_bayar" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Bayar (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah_bayar" id="jumlah_bayar" class="w-full border-gray-300 border rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500 transition duration-150" value="{{ old('jumlah_bayar', $pembayaran->jumlah_bayar) }}" required min="1000">
                </div>

                {{-- Tanggal Bayar --}}
                <div>
                    <label for="tanggal_bayar" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Bayar <span class="text-red-500">*</span></label>
                    @php 
                        $tanggalBayar = old('tanggal_bayar', \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('Y-m-d')); 
                    @endphp
                    <input type="date" name="tanggal_bayar" id="tanggal_bayar" class="w-full border-gray-300 border rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500 transition duration-150" value="{{ $tanggalBayar }}" required>
                </div>

                {{-- Status Pembayaran --}}
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran <span class="text-red-500">*</span></label>
                    <select name="status" id="status" class="w-full border-gray-300 border rounded-lg p-3 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 transition duration-150" required>
                        @php $currentStatus = old('status', $pembayaran->status); @endphp
                        <option value="pending" {{ $currentStatus == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="lunas" {{ $currentStatus == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="batal" {{ $currentStatus == 'batal' ? 'selected' : '' }}>Batal</option>
                    </select>
                </div>
                
            </div>

            {{-- **BAGIAN MULTI-FILE BUKTI PEMBAYARAN** --}}
            <div class="md:col-span-2 border-t pt-4 mt-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Bukti Pembayaran</h2>
                
                {{-- 🖼️ BUKTI TERSIMPAN SAAT INI --}}
                @php
                    // Defensif check dan filter data lama/kosong
                    $buktiFiles = is_array($pembayaran->bukti_pembayaran) ? array_filter($pembayaran->bukti_pembayaran) : [];
                    // Jika data lama adalah single file string, masukkan ke array
                    if (!is_array($pembayaran->bukti_pembayaran) && $pembayaran->bukti_pembayaran) {
                        $buktiFiles = [$pembayaran->bukti_pembayaran];
                    }
                @endphp

                @if (count($buktiFiles) > 0)
                    <div class="mb-4 p-4 rounded-lg bg-blue-50 border border-blue-200">
                        <p class="text-sm font-medium text-gray-700 mb-2"><i class="fas fa-images mr-1"></i> Bukti Tersimpan Saat Ini ({{ count($buktiFiles) }} File):</p>
                        <div class="flex flex-wrap gap-3">
                            @foreach ($buktiFiles as $fileName)
                                @if ($fileName)
                                    <a href="{{ asset('uploads/bukti_pembayaran/' . $fileName) }}" target="_blank" title="Lihat: {{ $fileName }}"
                                       class="text-sm px-3 py-1 bg-white border border-gray-300 rounded-full text-blue-600 hover:bg-gray-100 transition duration-150">
                                        <i class="fas fa-file-image mr-1"></i> {{ substr($fileName, -15) }}...
                                    </a>
                                @endif
                            @endforeach
                        </div>
                        <p class="text-xs text-red-500 mt-3">**CATATAN:** File baru yang diunggah di bawah ini akan **ditambahkan** ke daftar bukti.</p>
                    </div>
                @else
                    <p class="text-sm text-gray-500 mb-4 italic">Belum ada bukti pembayaran tersimpan.</p>
                @endif
                
                {{-- 💾 INPUT UNTUK MENAMBAH BUKTI PEMBAYARAN BARU --}}
                <label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700 mb-1">Tambahkan Bukti Baru (Opsional)</label>
                <input type="file" name="bukti_pembayaran[]" id="bukti_pembayaran" multiple
                       class="w-full border-gray-300 border rounded-lg p-2 bg-white focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                <p class="text-xs text-gray-500 mt-1">Format: JPG/PNG, Maks. 2MB per file. Kosongkan jika tidak ada tambahan.</p>
                @error('bukti_pembayaran')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                @error('bukti_pembayaran.*')
                    <p class="text-red-500 text-xs mt-1">Error file: {{ $message }}</p>
                @enderror
            </div>
            
            {{-- Tombol Simpan --}}
            <div class="pt-4 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-3 rounded-lg shadow-lg transition duration-300 transform hover:scale-105">
                    <i class="fas fa-sync-alt mr-2"></i> Perbarui Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection