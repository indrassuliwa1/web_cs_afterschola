@extends('admin.print.layouts.print-only')
@section('title', 'Laporan Pembayaran')

@section('content')
    <div class="header"
        style="display: flex; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px;">
        <img src="{{ asset('images/logo.png') }}" alt="Logo">
        <div style="margin-left: 10px;">
            <h2 style="margin:0;">Laporan Pembayaran</h2>
            <p style="margin:0; font-size: 14px;">Sistem Manajemen Kontrak & Keuangan</p>
        </div>
        <div style="margin-left:auto; text-align:right;">
            <p id="realtime-clock" style="font-size:14px; font-weight:bold;"></p>
            <p style="font-size:12px;">Dicetak oleh: {{ Auth::user()->name ?? 'Admin' }}</p>
        </div>
    </div>

    <h3 style="margin-bottom: 10px;">Ringkasan Keuangan</h3>
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
        <tr>
            <th style="text-align:left; padding:5px; width: 30%; border-bottom: 1px dashed #ccc;">Total Pembayaran Masuk
            </th>
            <td style="padding:5px; border-bottom: 1px dashed #ccc; font-weight: bold;">Rp
                {{ number_format($totalPembayaran, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th style="text-align:left; padding:5px; border-bottom: 1px dashed #ccc;">Kontrak Lunas</th>
            <td style="padding:5px; border-bottom: 1px dashed #ccc;">{{ number_format($totalLunas) }}</td>
        </tr>
        <tr>
            <th style="text-align:left; padding:5px; border-bottom: 1px dashed #ccc;">Kontrak Pending</th>
            <td style="padding:5px;">{{ number_format($totalPending) }}</td>
        </tr>
    </table>

    <h3 style="margin-bottom: 10px;">Detail Pembayaran</h3>
    <table style="width:100%; border-collapse: collapse; font-size:13px;">
        <thead>
            <tr style="background:#f2f2f2;">
                <th style="border:1px solid #000; padding:5px;">No</th>
                <th style="border:1px solid #000; padding:5px;">Nama Pendaftar</th>
                <th style="border:1px solid #000; padding:5px;">Nama Kontrak</th>
                <th style="border:1px solid #000; padding:5px;">Kelas</th>
                <th style="border:1px solid #000; padding:5px;">Total Tagihan</th>
                <th style="border:1px solid #000; padding:5px;">Total Bayar</th>
                <th style="border:1px solid #000; padding:5px;">Sisa Tagihan</th>
                <th style="border:1px solid #000; padding:5px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kontrak as $item)
                <tr>
                    <td style="border:1px solid #000; padding:5px;">{{ $loop->iteration }}</td>
                    <td style="border:1px solid #000; padding:5px;">{{ $item->pendaftar->nama ?? '-' }}</td>
                    <td style="border:1px solid #000; padding:5px;">{{ $item->nama_kontrak }}</td>
                    <td style="border:1px solid #000; padding:5px;">{{ $item->kelas->nama_kelas ?? '-' }}</td>
                    <td style="border:1px solid #000; padding:5px;">Rp
                        {{ number_format($item->total_tagihan, 0, ',', '.') }}</td>
                    <td style="border:1px solid #000; padding:5px;">Rp
                        {{ number_format($item->total_bayar_masuk, 0, ',', '.') }}</td>
                    <td style="border:1px solid #000; padding:5px;">Rp
                        {{ number_format($item->sisa_tagihan, 0, ',', '.') }}</td>
                    <td
                        style="border:1px solid #000; padding:5px; text-transform:capitalize; font-weight:bold;
                color: {{ $item->status_pembayaran_agregasi == 'lunas' ? 'green' : 'red' }}">
                        {{ $item->status_pembayaran_agregasi }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        <p style="font-size:13px;">Jumlah data: {{ $kontrak->count() }}</p>
    </div>

@endsection

{{-- Skrip Jam Statis untuk Dokumen Cetak --}}
<script>
    function updateClock() {
        const now = new Date();
        const formatted = now.toLocaleString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        document.getElementById('realtime-clock').textContent = formatted;
    }
    updateClock();
    // Tidak ada interval karena ini dokumen cetak
    // window.print() akan dipanggil manual oleh browser
</script>
