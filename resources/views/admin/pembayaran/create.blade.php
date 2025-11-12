@extends('layouts.dashboard')

@section('title', 'Tambah Pembayaran')
@section('page', 'Tambah Pembayaran Baru')

@section('content')
<div class="container mx-auto p-4 md:p-8 min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto">
        
        {{-- Header dan Tombol Kembali --}}
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h1 class="text-3xl font-extrabold text-gray-800">
                <i class="fas fa-credit-card text-green-600 mr-2"></i> Tambah Pembayaran Baru
            </h1>
            <a href="{{ route('admin.pembayaran') }}" class="flex items-center bg-gray-500 hover:bg-gray-600 text-white font-semibold px-4 py-2 rounded-lg transition duration-200 shadow-md">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        {{-- Form Tambah Pembayaran (PENTING: enctype="multipart/form-data") --}}
        <form action="{{ route('admin.pembayaran.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-xl shadow-2xl space-y-6 border border-green-100">
            @csrf

            {{-- Menampilkan Error Validasi --}}
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Perhatian!</strong>
                    <span class="block sm:inline"> Silakan perbaiki kesalahan berikut:</span>
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
                    <select name="kontrak_id" id="kontrak_id" class="w-full border-gray-300 border rounded-lg p-3 bg-gray-50 focus:ring-green-500 focus:border-green-500 transition duration-150" required>
                        <option value="">-- Pilih Kontrak --</option>
                        @foreach($kontrak as $k)
                            {{-- Data attributes dengan informasi Agregat Keuangan --}}
                            <option value="{{ $k->id }}" 
                                    data-kelas="{{ $k->kelas->nama_kelas ?? 'N/A' }}"
                                    data-harga="{{ $k->kelas->harga ?? 0 }}"
                                    data-peserta="{{ $k->jumlah_peserta }}"
                                    data-total-tagihan="{{ $k->total_tagihan }}"
                                    data-total-bayar="{{ $k->total_bayar_masuk }}"
                                    data-sisa-tagihan="{{ $k->sisa_tagihan }}"
                                    {{ old('kontrak_id') == $k->id ? 'selected' : '' }}>
                                 {{ $k->pendaftar->nama ?? 'Nama Tidak Ada' }} ({{ $k->nama_kontrak }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Blok Detail Tagihan (Informasi Non-Input) --}}
                <div id="detail-tagihan" class="md:col-span-2 hidden p-4 rounded-lg border-l-4 border-blue-500 bg-blue-50">
                    <h3 class="font-bold text-lg text-blue-800 mb-3"><i class="fas fa-info-circle mr-2"></i> Status Keuangan Kontrak</h3>
                    
                    <div id="info-status-box" class="mb-3 p-2 rounded-lg text-white font-bold text-center text-sm"></div>

                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                        <div class="col-span-2 lg:col-span-1">
                            <p class="text-gray-500">Kelas</p>
                            <strong id="info-kelas">N/A</strong>
                        </div>
                        <div class="lg:col-span-1">
                            <p class="text-gray-500">Total Tagihan</p>
                            <strong id="info-total" class="text-blue-600">Rp 0</strong>
                        </div>
                        <div class="lg:col-span-1">
                            <p class="text-gray-500">Total Dibayar</p>
                            <strong id="info-dibayar" class="text-green-600">Rp 0</strong>
                        </div>
                        <div class="lg:col-span-1">
                            <p class="text-gray-500">Sisa Tagihan</p>
                            <strong id="info-sisa" class="text-red-600">Rp 0</strong>
                        </div>
                    </div>
                </div>
                
                {{-- Jumlah Bayar --}}
                <div>
                    <label for="jumlah_bayar" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Bayar (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah_bayar" id="jumlah_bayar" class="w-full border-gray-300 border rounded-lg p-3 focus:ring-green-500 focus:border-green-500 transition duration-150" value="{{ old('jumlah_bayar') }}" required min="0">
                </div>

                {{-- Tanggal Bayar --}}
                <div>
                    <label for="tanggal_bayar" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Bayar <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_bayar" id="tanggal_bayar" class="w-full border-gray-300 border rounded-lg p-3 focus:ring-green-500 focus:border-green-500 transition duration-150" value="{{ old('tanggal_bayar', now()->format('Y-m-d')) }}" required>
                </div>

                {{-- Status Pembayaran --}}
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran <span class="text-red-500">*</span></label>
                    <select name="status" id="status" class="w-full border-gray-300 border rounded-lg p-3 bg-gray-50 focus:ring-green-500 focus:border-green-500 transition duration-150" required>
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="lunas" {{ old('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>
                
                {{-- Bukti Pembayaran (MULTI-FILE INPUT) --}}
                <div>
                    <label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700 mb-1">Bukti Pembayaran (Bisa Lebih Dari 1 Foto)</label>
                    <input type="file" name="bukti_pembayaran[]" id="bukti_pembayaran" multiple
                           class="w-full border-gray-300 border rounded-lg p-2 bg-white focus:ring-green-500 focus:border-green-500 transition duration-150">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG/PNG, Maks. 2MB per file.</p>
                </div>
            </div>
            
            {{-- Tombol Aksi --}}
            <div class="pt-4 flex justify-end">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold px-8 py-3 rounded-lg shadow-lg transition duration-300 transform hover:scale-105">
                    <i class="fas fa-save mr-2"></i> Simpan Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const kontrakSelect = document.getElementById('kontrak_id');
    const detailTagihanDiv = document.getElementById('detail-tagihan');
    const infoKelas = document.getElementById('info-kelas');
    const infoTotal = document.getElementById('info-total');
    const infoDibayar = document.getElementById('info-dibayar');
    const infoSisa = document.getElementById('info-sisa');
    const infoStatusBox = document.getElementById('info-status-box');
    const jumlahBayarInput = document.getElementById('jumlah_bayar');

    // Fungsi helper untuk format Rupiah
    const formatRupiah = (number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    };

    function updateTagihan() {
        const selectedOption = kontrakSelect.options[kontrakSelect.selectedIndex];
        
        // Reset state
        infoStatusBox.className = 'mb-3 p-2 rounded-lg text-white font-bold text-center text-sm';
        jumlahBayarInput.value = '';
        jumlahBayarInput.removeAttribute('disabled');
        
        if (selectedOption.value === "") {
            detailTagihanDiv.classList.add('hidden');
            return;
        }

        // Ambil data agregat dari data attributes
        const kelas = selectedOption.getAttribute('data-kelas');
        const totalTagihan = parseFloat(selectedOption.getAttribute('data-total-tagihan')) || 0;
        const totalBayar = parseFloat(selectedOption.getAttribute('data-total-bayar')) || 0;
        let sisa = parseFloat(selectedOption.getAttribute('data-sisa-tagihan')) || 0;
        
        // Pastikan sisa tidak negatif
        sisa = Math.max(0, sisa);

        // Tentukan Status dan Style Box
        let statusText;
        let statusClass;

        if (sisa <= 0) {
            statusText = 'STATUS: LUNAS';
            statusClass = 'bg-green-600';
            jumlahBayarInput.value = 0;
            jumlahBayarInput.setAttribute('disabled', 'disabled');
        } else if (totalBayar > 0) {
            statusText = `STATUS: DP DIBAYAR (SISA: ${formatRupiah(sisa)})`;
            statusClass = 'bg-yellow-600';
            jumlahBayarInput.value = sisa; // Sarankan melunasi sisa
        } else {
            statusText = 'STATUS: BELUM ADA PEMBAYARAN';
            statusClass = 'bg-red-600';
        }
        
        // Update elemen HTML
        infoKelas.textContent = kelas;
        infoTotal.textContent = formatRupiah(totalTagihan);
        infoDibayar.textContent = formatRupiah(totalBayar);
        
        infoSisa.textContent = formatRupiah(sisa);
        infoSisa.classList.toggle('text-red-600', sisa > 0);
        infoSisa.classList.toggle('text-green-600', sisa <= 0);

        // Update Status Box
        infoStatusBox.textContent = statusText;
        infoStatusBox.classList.add(statusClass);
        
        detailTagihanDiv.classList.remove('hidden');
    }

    // Event listener untuk memicu pembaruan saat kontrak diubah
    kontrakSelect.addEventListener('change', updateTagihan);

    // Panggil sekali saat dimuat untuk menampilkan data jika ada old input
    updateTagihan();
});
</script>
@endsection