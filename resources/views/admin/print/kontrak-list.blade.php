@extends('admin.print.layouts.print-only')
@section('title', 'Daftar Kontrak')

{{-- 📄 Tambahan Gaya Spesifik A4 --}}
<style>
    /* Mengatur ukuran kertas dan orientasi (jika diperlukan) */
    @page {
        size: A4;
        margin: 1cm 1.5cm; /* Margin 1 cm di atas/bawah, 1.5 cm di kiri/kanan */
    }

    /* Mengatur font dasar untuk dokumen */
    body {
        font-family: Arial, sans-serif;
        color: #333;
    }

    /* Memastikan elemen div/table/img tidak melebihi lebar A4 */
    .kop-surat, table {
        max-width: 100%;
        page-break-inside: auto; /* Memastikan tabel tidak terpotong di tengah halaman */
    }

    /* Hanya mencetak waktu cetak, tidak perlu interval update */
    #current-time, #current-print-time {
        white-space: nowrap; /* Mencegah waktu terpotong */
    }
</style>

@section('content')
    {{-- 🟦 Kop Surat --}}
    <div class="kop-surat" style="text-align: center; margin-bottom: 25px; border-bottom: 4px double #333; padding-bottom: 10px;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 80px; text-align: left; vertical-align: top; padding-right: 15px;">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 70px; height: auto;">
                </td>
                <td style="text-align: center; vertical-align: middle;">
                    <h1 style="margin: 0; font-size: 20px; font-weight: bold; text-transform: uppercase;">Laporan Daftar Kontrak</h1>
                    <p style="margin: 3px 0; font-size: 14px; color: #555;">Sistem Informasi Administrasi</p>
                    <p style="margin: 0; font-size: 12px; color: #777; font-style: italic;">Dicetak secara otomatis melalui sistem</p>
                </td>
                <td style="width: 150px; text-align: right; vertical-align: top;">
                    <p id="current-time" style="font-size: 11px; color: #333; margin-top: 0;"></p>
                </td>
            </tr>
        </table>
    </div>

    {{-- 🟨 Ringkasan --}}
    <h3 style="margin-top: 5px; margin-bottom: 10px; font-size: 16px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">Ringkasan Data</h3>
    <table style="width: 50%; border-collapse: collapse; margin-bottom: 25px; font-size: 14px;">
        <tbody>
            <tr>
                <th style="text-align: left; padding: 6px 10px; background-color: #f8f8f8; border: 1px solid #eee; width: 60%; font-weight: normal;">Total Kontrak Keseluruhan</th>
                <td style="padding: 6px 10px; border: 1px solid #eee; font-weight: bold; text-align: right;">{{ number_format($totalKontrak) }}</td>
            </tr>
            <tr>
                <th style="text-align: left; padding: 6px 10px; background-color: #f8f8f8; border: 1px solid #eee; font-weight: normal;">Kontrak yang Sedang Aktif</th>
                <td style="padding: 6px 10px; border: 1px solid #eee; font-weight: bold; text-align: right;">{{ number_format($kontrakAktif) }}</td>
            </tr>
        </tbody>
    </table>

    {{-- 🟩 Daftar Kontrak --}}
    <h3 style="margin-top: 15px; margin-bottom: 10px; font-size: 16px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">Detail Daftar Kontrak</h3>
    <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
        <thead>
            <tr style="background-color: #e9ecef; border: 1px solid #ccc; font-weight: bold; text-transform: uppercase;">
                <th style="padding: 8px 5px; border: 1px solid #ccc; width: 3%;">No</th>
                <th style="padding: 8px 10px; border: 1px solid #ccc; text-align: left;">Nama Kontrak</th>
                <th style="padding: 8px 10px; border: 1px solid #ccc; width: 15%;">Kelas</th>
                <th style="padding: 8px 10px; border: 1px solid #ccc; width: 8%;">Status</th>
                <th style="padding: 8px 10px; border: 1px solid #ccc; width: 10%;">Peserta</th>
                <th style="padding: 8px 10px; border: 1px solid #ccc; width: 10%;">Mulai</th>
                <th style="padding: 8px 10px; border: 1px solid #ccc; width: 10%;">Selesai</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kontrak as $item)
                <tr>
                    <td style="padding: 6px 5px; border: 1px solid #ccc; text-align: center;">{{ $loop->iteration }}</td>
                    <td style="padding: 6px 10px; border: 1px solid #ccc;">{{ $item->nama_kontrak }}</td>
                    <td style="padding: 6px 10px; border: 1px solid #ccc; text-align: center;">{{ $item->kelas->nama_kelas ?? '-' }}</td>
                    <td style="padding: 6px 10px; border: 1px solid #ccc; text-align: center;">
                        @if ($item->status == 'aktif')
                            <span style="color: #198754; font-weight: bold;">Aktif</span>
                        @else
                            <span style="color: #dc3545; font-weight: bold;">Nonaktif</span>
                        @endif
                    </td>
                    <td style="padding: 6px 10px; border: 1px solid #ccc; text-align: center;">{{ number_format($item->jumlah_peserta) }}</td>
                    <td style="padding: 6px 10px; border: 1px solid #ccc; text-align: center;">
                        {{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d M Y') }}
                    </td>
                    <td style="padding: 6px 10px; border: 1px solid #ccc; text-align: center;">
                        {{ \Carbon\Carbon::parse($item->tanggal_selesai)->translatedFormat('d M Y') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="document-footer" style="margin-top: 30px; font-size: 11px; color: #777;">
        <p style="margin: 2px 0;">**Total Data yang Ditampilkan:** {{ number_format($kontrak->count()) }} kontrak</p>
        <p style="font-style: italic; margin-top: 15px;">Laporan ini dicetak secara otomatis oleh sistem pada <span id="current-print-time"></span>. Tidak memerlukan tanda tangan basah.</p>
    </div>

    {{-- ⏰ Script Jam Realtime (Disesuaikan untuk Cetak) --}}
    <script>
        // Fungsi ini hanya dipanggil sekali saat halaman dimuat/dicetak
        function setPrintTime() {
            const now = new Date();
            const options = { 
                weekday: 'long', year: 'numeric', month: 'long', 
                day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false
            };
            const timeString = now.toLocaleString('id-ID', options);
            
            // Untuk kop surat
            const currentTimeElement = document.getElementById('current-time');
            if (currentTimeElement) {
                currentTimeElement.innerHTML = `**Waktu Cetak:**<br>${timeString}`;
            }

            // Untuk footer
            const currentPrintTimeElement = document.getElementById('current-print-time');
            if (currentPrintTimeElement) {
                currentPrintTimeElement.textContent = timeString;
            }
        }
        
        setPrintTime(); 
    </script>
@endsection