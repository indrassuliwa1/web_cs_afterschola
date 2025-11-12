<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kontrak;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SupervisorModulesController extends Controller
{
    /**
     * Menampilkan daftar Kontrak (Read-Only) untuk Supervisor.
     * Menggunakan logika agregasi yang sama dengan Admin Pembayaran Index.
     */
    public function kontrakIndex(Request $request)
    {
        $search = $request->input('search');

        // Mengambil kontrak yang difilter dan dipaginate
        $query = Kontrak::with(['pendaftar', 'kelas', 'pembayaran'])
            ->latest();

        $query->when($search, function ($q, $search) {
            $q->where('nama_kontrak', 'like', "%{$search}%")
                ->orWhereHas('pendaftar', function ($subQ) use ($search) {
                    $subQ->where('nama', 'like', "%{$search}%");
                });
        });
        
        $kontrakPaginated = $query->paginate(10)->appends($request->query());
        
        // Agregasi data untuk ditampilkan di tabel
        $kontrak = $kontrakPaginated->through(function ($kontrak) {
            $durasi = $kontrak->durasi_bulan ?? 1;
            $totalTagihan = $kontrak->jumlah_peserta * ($kontrak->kelas->harga ?? 0) * $durasi;
            $totalBayarMasuk = $kontrak->pembayaran->sum('jumlah_bayar');
            $sisaTagihan = $totalTagihan - $totalBayarMasuk;

            $kontrak->total_bayar_masuk = $totalBayarMasuk;
            $kontrak->status_pembayaran_agregasi = ($sisaTagihan <= 0) ? 'lunas' : 'pending';
            
            return $kontrak;
        });

        return view('supervisor.kontrak', compact('kontrak', 'search'));
    }

    /**
     * Menampilkan daftar Pembayaran (Read-Only) untuk Supervisor.
     * Logika ini harus sama dengan PembayaranController@index, tetapi tanpa ML Risk API call (disederhanakan).
     */
    public function pembayaranIndex(Request $request)
    {
        $search = $request->input('search');

        // Mengambil data kontrak yang difilter dan dipaginate (Basis Pembayaran adalah Kontrak)
        $query = Kontrak::with(['pendaftar', 'kelas', 'pembayaran'])
            ->latest();

        $query->when($search, function ($q, $search) {
            $q->where('nama_kontrak', 'like', "%{$search}%")
                ->orWhereHas('pendaftar', function ($subQ) use ($search) {
                    $subQ->where('nama', 'like', "%{$search}%");
                });
        });
        
        $kontrakPaginated = $query->paginate(10)->appends($request->query());

        // Agregasi Statistik & Notifikasi (Disederhanakan)
        $totalPembayaran = Pembayaran::sum('jumlah_bayar');
        $totalLunas = 0;
        $totalPending = 0;

        // Agregasi data untuk tampilan tabel
        $pembayaran = $kontrakPaginated->through(function ($kontrak) use (&$totalLunas, &$totalPending) {
            $durasi = $kontrak->durasi_bulan ?? 1;
            $totalTagihan = $kontrak->jumlah_peserta * ($kontrak->kelas->harga ?? 0) * $durasi;
            $totalBayarMasuk = $kontrak->pembayaran->sum('jumlah_bayar');
            $sisaTagihan = $totalTagihan - $totalBayarMasuk;

            $kontrak->total_bayar_masuk = $totalBayarMasuk;
            $kontrak->status_pembayaran_agregasi = ($sisaTagihan <= 0) ? 'lunas' : 'pending';
            $kontrak->tanggal_terakhir_bayar = $kontrak->pembayaran->max('tanggal_bayar');
            
            if ($sisaTagihan <= 0) {
                $totalLunas++;
            } else {
                $totalPending++;
            }
            
            return $kontrak;
        });
        
        // Catatan: Supervisor tidak perlu notifikasi unpaidContracts
        
        return view('supervisor.pembayaran', compact('pembayaran', 'totalPembayaran', 'totalLunas', 'totalPending', 'search'));
    }
}