<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftar; 
use App\Models\Kontrak; 
use App\Models\Kelas; 
use App\Models\Pembayaran; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\PendaftaranDiterima; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File; 
use Carbon\Carbon; // Digunakan untuk parsing tanggal

class PendaftaranController extends Controller
{
    /**
     * Fungsi helper untuk membersihkan format Rupiah.
     */
    private function cleanRupiah($string)
    {
        if (!$string) return null;
        return preg_replace('/[^0-9]/', '', $string);
    }

    // ----------------------------------------------------------------------
    // 🚀 STEP 1: FORMULIR PENDAFTARAN (Data Diri & Kontrak)
    // ----------------------------------------------------------------------

    public function showStepOne(Request $request)
    {
        $kelasList = Kelas::all(); 
        return view('register', compact('kelasList'));
    }

    /**
     * Menyimpan data pendaftaran (termasuk tanggal dan durasi) ke SESI.
     */
    public function storeStepOne(Request $request)
    {
        $request->merge([
            'harga' => $this->cleanRupiah($request->input('harga')),
        ]);

        $validatedData = $request->validate([
            'nama_pendaftar' => 'required|string|max:255', 
            'tipe' => 'required|in:guru,orangtua,siswa',
            'alamat' => 'required|string|max:500',
            'email' => 'required|email|max:255|unique:pendaftar,email', 
            'no_hp' => 'required|string|max:15', 

            'kelas_id' => 'required|exists:kelas,id',
            'jumlah_peserta' => 'required|integer|min:1',
            'harga' => 'required|numeric|min:0',
            
            // ✅ FIX: MENAMBAHKAN FIELD BARU KE VALIDASI
            'durasi_bulan' => 'required|integer|min:1',
            'tanggal_mulai' => 'required|date|after_or_equal:today', 
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        // ✅ FIX: SEMUA DATA YANG SUDAH DIVALIDASI SEKARANG TERSIMPAN DI SESI
        $request->session()->put('pendaftaran_data', $validatedData);
        return redirect()->route('register.step2.show');
    }

    // ----------------------------------------------------------------------
    // 💸 STEP 2: KONFIRMASI PEMBAYARAN
    // ----------------------------------------------------------------------

    public function showStepTwo(Request $request)
    {
        $dataStep1 = $request->session()->get('pendaftaran_data');

        if (!$dataStep1) {
            return redirect()->route('register.step1.show')->with('error', 'Sesi pendaftaran tidak ditemukan.');
        }

        $kelas = Kelas::find($dataStep1['kelas_id']);

        // ✅ FIX: Pastikan semua key ada, jika ada data lama di sesi yang tidak lengkap, berikan nilai default
        $data = (object) array_merge([
            'durasi_bulan' => 'N/A',
            'tanggal_mulai' => 'N/A',
            'tanggal_selesai' => 'N/A',
            // ... field lainnya ...
        ], $dataStep1, [
            // Tambahkan field yang berasal dari relasi
            'nama_kelas' => $kelas->nama_kelas ?? 'N/A',
        ]);

        return view('payment-confirmation', compact('data'));
    }

    /**
     * Menyimpan data final, dengan status 'lunas' jika jumlah_bayar >= total_harga.
     */
    public function storeStepTwo(Request $request)
    {
        $dataStep1 = $request->session()->get('pendaftaran_data');

        if (!$dataStep1) {
            return redirect()->route('register.step1.show')->with('error', 'Sesi pendaftaran kedaluwarsa.');
        }
        
        // --- AMBIL HARGA TOTAL DARI SESI ---
        $harga_kontrak_total = (float) $dataStep1['harga'];
        
        // 1. Pembersihan & Validasi Step 2
        $request->merge([
            'jumlah_bayar' => $this->cleanRupiah($request->input('jumlah_bayar')), 
        ]);

        $validatedDataStep2 = $request->validate([
            'jumlah_bayar' => 'required|numeric|min:0', 
            'tanggal_bayar' => 'required|date|before_or_equal:today', 
            'bukti_pembayaran' => 'required|file|image|mimes:jpeg,png,jpg|max:2048', // Input file
        ]);
        
        $jumlah_bayar_sekarang = (float) $validatedDataStep2['jumlah_bayar'];

        // --- LOGIKA PENENTU STATUS PEMBAYARAN OTOMATIS ---
        $status_pembayaran = 'pending';
        if ($jumlah_bayar_sekarang >= $harga_kontrak_total) {
            $status_pembayaran = 'lunas';
        }

        // 2. Proses Upload Bukti Pembayaran
        $buktiFileName = null;
        try {
            $uploadPath = public_path('uploads/bukti_pembayaran');
            $image = $validatedDataStep2['bukti_pembayaran']; 

            // Pastikan direktori ada
            if (!File::isDirectory($uploadPath)) {
                File::makeDirectory($uploadPath, 0777, true, true);
            }
            
            // Simpan HANYA nama file (nama file harus unik)
            $imageName = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move($uploadPath, $imageName);
            $buktiFileName = $imageName; // Ini yang akan disimpan ke DB

        } catch (\Exception $e) {
            Log::error('Kegagalan Upload Bukti Pembayaran: ' . $e->getMessage());
            return back()->withInput()->withErrors(['Terjadi kesalahan saat mengunggah bukti pembayaran. Silakan coba lagi.']);
        }

        // 3. INSERT DB: TRANSAKSI FINAL
        try {
            // A. Simpan Pendaftar
            $pendaftar = Pendaftar::create([
                'nama' => $dataStep1['nama_pendaftar'], 
                'tipe' => $dataStep1['tipe'],
                'alamat' => $dataStep1['alamat'],
                'email' => $dataStep1['email'],
                'no_hp' => $dataStep1['no_hp'],
            ]);
            
            // B. Simpan Kontrak
            $kontrak = Kontrak::create([
                'pendaftar_id' => $pendaftar->id, 
                'kelas_id' => $dataStep1['kelas_id'],
                'nama_kontrak' => 'Kontrak - ' . $pendaftar->nama,
                'jumlah_peserta' => $dataStep1['jumlah_peserta'],
                'harga' => $harga_kontrak_total,
                'status' => 'nonaktif', 
                
                // ✅ FIX: MENGGUNAKAN TANGGAL DAN DURASI DARI SESI
                'tanggal_mulai' => $dataStep1['tanggal_mulai'], 
                'tanggal_selesai' => $dataStep1['tanggal_selesai'], 
                'durasi_bulan' => $dataStep1['durasi_bulan'] ?? 6, // Ambil durasi dari sesi (beri default 6 jika entah bagaimana hilang)
                
                'data_peserta_file' => null, 
            ]);

            // C. Simpan Pembayaran
            Pembayaran::create([
                'kontrak_id' => $kontrak->id,
                'pendaftar_id' => $pendaftar->id,
                'jumlah_bayar' => $jumlah_bayar_sekarang, 
                'bukti_pembayaran' => [$buktiFileName], 
                'status' => $status_pembayaran, 
                'tanggal_bayar' => $validatedDataStep2['tanggal_bayar'], 
            ]);

        } catch (\Exception $e) {
            Log::error('Kegagalan TRANSAKSI DB FINAL: ' . $e->getMessage());
            return back()->withInput()->withErrors(['Terjadi kesalahan server saat menyimpan data final. Silakan hubungi admin.']);
        }

        // 4. Bersihkan sesi dan redirect
        $request->session()->forget('pendaftaran_data');
        return redirect()->route('register.success');
    }

    // ----------------------------------------------------------------------
    // 🎉 STEP 3: HALAMAN SUKSES
    // ----------------------------------------------------------------------

    public function showSuccess()
    {
        return view('register-success');
    }
}