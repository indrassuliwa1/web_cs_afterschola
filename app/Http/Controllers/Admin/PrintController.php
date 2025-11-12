<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kontrak;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    public function printKontrak($id)
    {
        try {
            // Ambil data kontrak + semua relasi yang diperlukan
            $kontrak = Kontrak::with(['kelas', 'pendaftar', 'pembayaran'])->findOrFail($id);

            // 🔹 Ambil harga kelas (default 0 kalau null)
            $hargaKelas = $kontrak->kelas->harga ?? 0;

            // 🔹 Hitung jumlah total tagihan = jumlah peserta x harga kelas
            $totalTagihan = ($kontrak->jumlah_peserta ?? 0) * $hargaKelas;

            // 🔹 Total pembayaran masuk (sum kolom jumlah_bayar)
            $totalBayarMasuk = $kontrak->pembayaran->sum('jumlah_bayar');

            // 🔹 Sisa tagihan
            $sisaTagihan = $totalTagihan - $totalBayarMasuk;

            // 🔹 Status (otomatis lunas bila sisaTagihan <= 0)
            $statusTagihan = $sisaTagihan <= 0 ? 'LUNAS' : 'BELUM LUNAS';

            return view('admin.print.kontrak', compact(
                'kontrak',
                'totalTagihan',
                'totalBayarMasuk',
                'sisaTagihan',
                'statusTagihan'
            ));
        } catch (\Exception $e) {
            return response()->view('errors.404', [
                'message' => 'Gagal memuat data kontrak untuk dicetak: ' . $e->getMessage()
            ], 404);
        }
    }
}
