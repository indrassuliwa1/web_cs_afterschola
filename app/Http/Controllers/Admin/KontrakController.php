<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kontrak;
use App\Models\Kelas;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File; // Pastikan ini ada

class KontrakController extends Controller
{
    // 🟦 Menampilkan halaman utama kontrak (dengan search & filter)
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $kontrak = Kontrak::with(['kelas', 'pendaftar'])
            ->when($search, function ($query, $search) {
                $query->where('nama_kontrak', 'like', "%{$search}%");
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->appends(['search' => $search, 'status' => $status]);

        $totalKontrak = Kontrak::count();
        $kontrakAktif = Kontrak::where('status', 'aktif')->count();

        return view('admin.kontrak', compact('kontrak', 'totalKontrak', 'kontrakAktif', 'search', 'status'));
    }

    // 🟩 Menampilkan form tambah kontrak
    public function create()
    {
        $kelas = Kelas::all();
        return view('admin.kontrak.create', compact('kelas'));
    }

    // 🟧 Menyimpan data kontrak baru (DENGAN LOGIKA UPLOAD FILE)
    public function store(Request $request)
    {
        $request->validate([
            // Validasi Kontrak
            'kelas_id' => 'required|exists:kelas,id',
            'nama_kontrak' => 'required|string|max:100',
            'status' => 'required|in:aktif,nonaktif',
            'jumlah_peserta' => 'required|integer|min:1',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            
            // Validasi File Data Peserta
            'data_peserta_file' => 'nullable|file|mimes:pdf,xlsx,xls,csv,doc,docx|max:5120', // Maks 5MB
            
            // Validasi Pendaftar/Penanggung Jawab
            'nama_pendaftar' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:pendaftar,email', 
            'no_hp' => 'required|string|max:15', 
            'alamat' => 'required|string|max:255',
            'tipe' => 'required|in:guru,orangtua,siswa',
        ]);

        try {
            DB::beginTransaction();

            // Logika File Upload
            $fileName = null;
            if ($request->hasFile('data_peserta_file')) {
                $file = $request->file('data_peserta_file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                
                // Pastikan folder upload ada
                $uploadPath = public_path('uploads/data_peserta');
                if (!File::isDirectory($uploadPath)) {
                    File::makeDirectory($uploadPath, 0777, true, true);
                }
                
                $file->move($uploadPath, $fileName);
            }
            
            // 1. BUAT PENDAFTAR BARU
            $pendaftar = Pendaftar::create([
                'nama' => $request->nama_pendaftar,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'tipe' => $request->tipe,
            ]);
            
            // 2. BUAT KONTRAK, REFERENSIKAN ID PENDAFTAR + FILE NAME
            Kontrak::create([
                'kelas_id' => $request->kelas_id,
                'pendaftar_id' => $pendaftar->id, 
                'nama_kontrak' => $request->nama_kontrak,
                'status' => $request->status,
                'jumlah_peserta' => $request->jumlah_peserta,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'data_peserta_file' => $fileName, // SIMPAN NAMA FILE
            ]);

            DB::commit();

            return redirect()->route('admin.kontrak')
                ->with('success', 'Kontrak dan data peserta berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Hapus file jika transaksi DB gagal
            if (isset($fileName) && $fileName) {
                $filePath = public_path('uploads/data_peserta/' . $fileName);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan kontrak. Detail: ' . $e->getMessage());
        }
    }
    
    // 🟦 Menampilkan detail kontrak
    public function show($id)
    {
        $kontrak = Kontrak::with(['kelas', 'pendaftar', 'pembayaran'])->findOrFail($id);
        
        // Hitung Statistik Pembayaran
        $totalTagihan = $kontrak->jumlah_peserta * ($kontrak->kelas->harga ?? 0);
        $totalBayarMasuk = $kontrak->pembayaran->sum('jumlah_bayar');
        $sisaTagihan = $totalTagihan - $totalBayarMasuk;

        return view('admin.kontrak-detail', compact('kontrak', 'totalTagihan', 'totalBayarMasuk', 'sisaTagihan'));
    }

    // 🟨 Menampilkan form edit kontrak
    public function edit($id)
    {
        $kontrak = Kontrak::with('pendaftar')->findOrFail($id);
        $kelas = Kelas::all();
        return view('admin.kontrak.edit', compact('kontrak', 'kelas'));
    }

    // 🟩 Memperbarui data kontrak (DENGAN LOGIKA UPDATE FILE)
    public function update(Request $request, $id)
    {
        $kontrak = Kontrak::with('pendaftar')->findOrFail($id);
        
        $request->validate([
            // Validasi Kontrak
            'kelas_id' => 'required|exists:kelas,id',
            'nama_kontrak' => 'required|string|max:100',
            'status' => 'required|in:aktif,nonaktif',
            'jumlah_peserta' => 'required|integer|min:1',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            
            // Validasi FILE BARU (Opsional)
            'data_peserta_file' => 'nullable|file|mimes:pdf,xlsx,xls,csv,doc,docx|max:5120', 

            // Validasi Pendaftar
            'nama_pendaftar' => 'required|string|max:100', 
            'email' => 'required|email|max:100|unique:pendaftar,email,' . $kontrak->pendaftar->id, 
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string|max:255',
            'tipe' => 'required|in:guru,orangtua,siswa', 
        ]);

        try {
            DB::beginTransaction();
            
            // ** LOGIKA FILE UPLOAD/UPDATE **
            $fileName = $kontrak->data_peserta_file; // Pertahankan nama file lama
            $uploadPath = public_path('uploads/data_peserta');

            if ($request->hasFile('data_peserta_file')) {
                $file = $request->file('data_peserta_file');

                // Hapus file lama jika ada
                if ($kontrak->data_peserta_file && File::exists($uploadPath . '/' . $kontrak->data_peserta_file)) {
                    File::delete($uploadPath . '/' . $kontrak->data_peserta_file);
                }

                // Buat nama file baru dan simpan
                $fileName = time() . '_' . $file->getClientOriginalName();
                if (!File::isDirectory($uploadPath)) {
                    File::makeDirectory($uploadPath, 0777, true, true);
                }
                $file->move($uploadPath, $fileName);
            }
            // ** AKHIR LOGIKA FILE UPLOAD/UPDATE **

            // 1. UPDATE DATA PENDAFTAR
            $kontrak->pendaftar->update([
                'nama' => $request->nama_pendaftar,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'tipe' => $request->tipe,
            ]);

            // 2. UPDATE DATA KONTRAK
            $kontrak->update([
                'kelas_id' => $request->kelas_id,
                'nama_kontrak' => $request->nama_kontrak,
                'status' => $request->status,
                'jumlah_peserta' => $request->jumlah_peserta,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'data_peserta_file' => $fileName, // PERBARUI NAMA FILE DI DB
            ]);
            
            DB::commit();

            return redirect()->route('admin.kontrak')
                ->with('success', 'Data kontrak dan pendaftar berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data kontrak. Detail: ' . $e->getMessage());
        }
    }
    
    public function print()
    {
        $kontrak = Kontrak::with('kelas')->get();
        $totalKontrak = $kontrak->count();
        $kontrakAktif = $kontrak->where('status', 'aktif')->count();

        return view('admin.print.kontrak-list', compact('kontrak', 'totalKontrak', 'kontrakAktif'));
    }


    // 🟥 Menghapus data kontrak (DENGAN LOGIKA HAPUS FILE)
    public function destroy($id)
    {
        try {
            $kontrak = Kontrak::findOrFail($id);
            
            // Cek dan hapus file terkait dari server
            if ($kontrak->data_peserta_file) {
                $filePath = public_path('uploads/data_peserta/' . $kontrak->data_peserta_file);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
            }
            
            // Hapus Pendaftar yang terikat
            $kontrak->pendaftar->delete(); 
            
            // Hapus kontrak itu sendiri
            $kontrak->delete(); 

            return redirect()->route('admin.kontrak')
                ->with('success', 'Data kontrak berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->route('admin.kontrak')
                ->with('error', 'Gagal menghapus data kontrak. Error: ' . $e->getMessage());
        }
    }
}