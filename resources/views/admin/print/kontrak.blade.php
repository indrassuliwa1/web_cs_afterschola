@extends('admin.print.layouts.print-only')

@section('title', 'Cetak Detail Kontrak') {{-- Judul tetap Cetak Detail Kontrak --}}

@section('content')
<div class="document-container">
    {{-- KOP SURAT --}}
    <div class="header" style="display: flex; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px;">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 50px; margin-right: 20px;">
        <div>
            <h2 style="margin:0;">DETAIL KONTRAK</h2>
            <p style="margin:0; font-size: 14px;">Atas Nama: {{ $kontrak->nama_kontrak }}</p>
        </div>
        <div style="margin-left:auto; text-align:right;">
            <p style="font-size:14px; font-weight:bold; margin-bottom: 5px;">
                Dicetak pada:
            </p>
            <p id="print-time" style="font-size:14px; font-weight:normal; margin:0;"></p>
        </div>
    </div>

    {{-- RINGKASAN KEUANGAN --}}
    <h3 style="margin-bottom: 10px;">📊 Ringkasan Keuangan</h3>
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 14px;">
        <tr>
            <th style="text-align:left; padding:5px; border: 1px solid #ddd; background-color: #f9f9f9; width: 33%;">Total Tagihan</th>
            <td style="padding:5px; border: 1px solid #ddd; font-weight: bold;">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th style="text-align:left; padding:5px; border: 1px solid #ddd; background-color: #f9f9f9; width: 33%;">Total Pembayaran Masuk</th>
            <td style="padding:5px; border: 1px solid #ddd; font-weight: bold;">Rp {{ number_format($totalBayarMasuk, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th style="text-align:left; padding:5px; border: 1px solid #ddd; background-color: #f9f9f9; width: 33%;">{{ $sisaTagihan <= 0 ? 'Status' : 'Sisa Tagihan' }}</th>
            <td style="padding:5px; border: 1px solid #ddd; font-weight: bold; color: {{ $sisaTagihan <= 0 ? 'green' : 'red' }};">
                {{ $sisaTagihan <= 0 ? 'LUNAS' : 'Rp ' . number_format($sisaTagihan, 0, ',', '.') }}
            </td>
        </tr>
    </table>
    
    <div style="display: flex; justify-content: space-between; gap: 20px;">
        {{-- INFORMASI KONTRAK --}}
        <div style="width: 50%;">
            <h3 style="margin-bottom: 10px;">🧾 Informasi Kontrak</h3>
            <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                <tr>
                    <th style="text-align:left; padding:5px; border: 1px solid #ddd; background-color: #f9f9f9; width: 35%;">Nama Kontrak</th>
                    <td style="padding:5px; border: 1px solid #ddd;">{{ $kontrak->nama_kontrak }}</td>
                </tr>
                <tr>
                    <th style="text-align:left; padding:5px; border: 1px solid #ddd; background-color: #f9f9f9;">Kelas</th>
                    <td style="padding:5px; border: 1px solid #ddd;">{{ $kontrak->kelas->nama_kelas ?? '-' }}</td>
                </tr>
                <tr>
                    <th style="text-align:left; padding:5px; border: 1px solid #ddd; background-color: #f9f9f9;">Jumlah Peserta</th>
                    <td style="padding:5px; border: 1px solid #ddd;">{{ $kontrak->jumlah_peserta }} Orang</td>
                </tr>
                <tr>
                    <th style="text-align:left; padding:5px; border: 1px solid #ddd; background-color: #f9f9f9;">Periode</th>
                    <td style="padding:5px; border: 1px solid #ddd;">
                        {{ \Carbon\Carbon::parse($kontrak->tanggal_mulai)->translatedFormat('d F Y') }} 
                        s/d {{ \Carbon\Carbon::parse($kontrak->tanggal_selesai)->translatedFormat('d F Y') }}
                    </td>
                </tr>
                <tr>
                    <th style="text-align:left; padding:5px; border: 1px solid #ddd; background-color: #f9f9f9;">Status Kontrak</th>
                    <td style="padding:5px; border: 1px solid #ddd; text-transform:capitalize;">{{ ucfirst($kontrak->status) }}</td>
                </tr>
            </table>
        </div>

        {{-- DATA PENDAFTAR --}}
        <div style="width: 50%;">
            <h3 style="margin-bottom: 10px;">👤 Data Pendaftar</h3>
            <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                <tr>
                    <th style="text-align:left; padding:5px; border: 1px solid #ddd; background-color: #f9f9f9; width: 35%;">Nama</th>
                    <td style="padding:5px; border: 1px solid #ddd;">{{ $kontrak->pendaftar->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <th style="text-align:left; padding:5px; border: 1px solid #ddd; background-color: #f9f9f9;">Email</th>
                    <td style="padding:5px; border: 1px solid #ddd;">{{ $kontrak->pendaftar->email ?? '-' }}</td>
                </tr>
                <tr>
                    <th style="text-align:left; padding:5px; border: 1px solid #ddd; background-color: #f9f9f9;">No. HP</th>
                    <td style="padding:5px; border: 1px solid #ddd;">{{ $kontrak->pendaftar->no_hp ?? '-' }}</td>
                </tr>
                <tr>
                    <th style="text-align:left; padding:5px; border: 1px solid #ddd; background-color: #f9f9f9;">Alamat</th>
                    <td style="padding:5px; border: 1px solid #ddd;">{{ $kontrak->pendaftar->alamat ?? '-' }}</td>
                </tr>
                {{-- Tambahan baris kosong agar tinggi tabel sama dengan Informasi Kontrak --}}
                <tr style="height: 38px;">
                    <th style="text-align:left; padding:5px; border: 1px solid #ddd; background-color: #f9f9f9;"></th>
                    <td style="padding:5px; border: 1px solid #ddd;"></td>
                </tr>
            </table>
        </div>
    </div>
    
    {{---
    
    --}}
    
    {{-- RIWAYAT PEMBAYARAN --}}
    <div style="margin-top: 20px;">
        <h3 style="margin-bottom: 10px;">📜 Riwayat Pembayaran</h3>
        <table style="width:100%; border-collapse: collapse; font-size:13px;">
            <thead>
                <tr style="background:#f2f2f2;">
                    <th style="border:1px solid #000; padding:5px; width: 5%;">No</th>
                    <th style="border:1px solid #000; padding:5px; width: 30%;">Jumlah Bayar</th>
                    <th style="border:1px solid #000; padding:5px; width: 35%;">Tanggal Bayar</th>
                    <th style="border:1px solid #000; padding:5px; width: 30%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kontrak->pembayaran as $p)
                <tr>
                    <td style="border:1px solid #000; padding:5px; text-align: center;">{{ $loop->iteration }}</td>
                    <td style="border:1px solid #000; padding:5px;">Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</td>
                    <td style="border:1px solid #000; padding:5px;">{{ \Carbon\Carbon::parse($p->tanggal_bayar)->translatedFormat('d F Y') }}</td>
                    <td style="border:1px solid #000; padding:5px; text-transform:capitalize; font-weight:bold; color: {{ strtolower($p->status) == 'berhasil' ? 'green' : 'orange' }};">
                        {{ ucfirst($p->status) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center; color:#888; border:1px solid #000; padding:5px;">Belum ada data pembayaran.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div style="margin-top: 10px;">
            <p style="font-size:13px;">Jumlah transaksi: {{ $kontrak->pembayaran->count() }}</p>
        </div>
    </div>

</div>

<script>
    // Memperbarui waktu cetak ke elemen #print-time
    function updatePrintTime() {
        const now = new Date();
        const formatted = now.toLocaleString('id-ID', {
            weekday: 'long', year: 'numeric', month: 'long',
            day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit'
        });
        document.getElementById('print-time').textContent = formatted;
    }
    updatePrintTime();
</script>
@endsection