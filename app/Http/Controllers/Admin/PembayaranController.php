<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kontrak;
use App\Models\Pendaftar; 
use App\Models\Pembayaran; 
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http; 
use Carbon\Carbon;

class PembayaranController extends Controller
{
    /**
     * Helper untuk memanggil API Prediksi Risiko ML.
     * Mengembalikan array hasil prediksi atau array error.
     */
    private function callMlRiskApi($contract)
    {
        $durasi = $contract->durasi_bulan ?? 1;
        $hargaKelas = $contract->kelas->harga ?? 0;
        
        $totalTagihan = $contract->jumlah_peserta * $hargaKelas * $durasi; 

        $hargaJt = $totalTagihan / 1000000;
        
        $payload = [
            'harga_kontrak_jt' => (float) round($hargaJt, 2),
            'durasi_bulan' => (int) $durasi,
            'riwayat_terlambat' => 0, 
            'tipe_pendaftar' => strtolower($contract->pendaftar->tipe ?? 'orangtua'),
        ];

        $mlApiUrl = 'http://127.0.0.1:8080/risk/predict';
        
        try {
            $response = Http::timeout(3)->post($mlApiUrl, $payload);

            if ($response->successful()) {
                return $response->json();
            } else {
                return [
                    'error' => true,
                    'risk_prediction' => 'ML Error',
                    'risk_score' => null,
                    'message' => 'API Status: ' . $response->status(),
                ];
            }
        } catch (\Exception $e) {
            return [
                'error' => true,
                'risk_prediction' => 'Server Down',
                'risk_score' => null,
                'message' => 'Connection Refused/Timeout',
            ];
        }
    }

    // 📌 Tampilkan semua data pembayaran + fitur search
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Statistik
        $totalPembayaran = Pembayaran::sum('jumlah_bayar');
        
        $query = Kontrak::with(['pendaftar', 'kelas', 'pembayaran'])
            ->latest();

        // Apply search based on Pendaftar name or Kontrak status
        $query->when($search, function ($q, $search) {
            $q->where('nama_kontrak', 'like', "%{$search}%")
                ->orWhereHas('pendaftar', function ($subQ) use ($search) {
                    $subQ->where('nama', 'like', "%{$search}%");
                });
        });
        
        $kontrakPaginated = $query->paginate(10)->appends($request->query());

        // Koleksi semua kontrak untuk agregasi total statistik
        $allContracts = Kontrak::with(['kelas', 'pembayaran', 'pendaftar'])->get();
        $unpaidContracts = collect([]); // Ini untuk notifikasi

        // === Agregasi data untuk Statistik Atas dan Notifikasi ===
        $totalLunasKontrak = 0;
        $totalPendingKontrak = 0;

        foreach ($allContracts as $kontrak) {
            $durasi = $kontrak->durasi_bulan ?? 1;
            $totalTagihan = $kontrak->jumlah_peserta * ($kontrak->kelas->harga ?? 0) * $durasi; 
            $totalBayarMasuk = $kontrak->pembayaran->sum('jumlah_bayar');
            $sisaTagihan = $totalTagihan - $totalBayarMasuk;

            $kontrak->total_tagihan = $totalTagihan;
            $kontrak->total_bayar_masuk = $totalBayarMasuk;
            $kontrak->sisa_tagihan = $sisaTagihan;
            $kontrak->durasi_bulan = $durasi;

            if ($sisaTagihan <= 0) {
                $totalLunasKontrak++;
            } else {
                $totalPendingKontrak++;
                $unpaidContracts->push($kontrak);
            }
        }

        $totalLunas = $totalLunasKontrak;
        $totalPending = $totalPendingKontrak;

        // Agregasi data pembayaran ke dalam Kontrak Collection (untuk tampilan tabel bawah)
        // INTEGRASI ML PREDIKSI RISIKO DI SINI
        $pembayaran = $kontrakPaginated->through(function ($kontrak) {
            $durasi = $kontrak->durasi_bulan ?? 1;
            $totalTagihan = $kontrak->jumlah_peserta * ($kontrak->kelas->harga ?? 0) * $durasi;
            $totalBayarMasuk = $kontrak->pembayaran->sum('jumlah_bayar');
            $sisaTagihan = $totalTagihan - $totalBayarMasuk;

            // Panggil API ML untuk Prediksi Risiko
            if ($kontrak->pendaftar && $totalTagihan > 0) {
                $riskData = $this->callMlRiskApi($kontrak); 
                $kontrak->risk_class = $riskData['risk_prediction'] ?? 'N/A';
                $kontrak->risk_score = $riskData['risk_score'] ?? null;
            } else {
                 $kontrak->risk_class = 'N/A';
                 $kontrak->risk_score = null;
            }
            
            // Tambahkan properti agregasi
            $kontrak->total_bayar_masuk = $totalBayarMasuk;
            $kontrak->status_pembayaran_agregasi = ($sisaTagihan <= 0) ? 'lunas' : 'pending';
            $kontrak->tanggal_terakhir_bayar = $kontrak->pembayaran->max('tanggal_bayar');
            
            return $kontrak;
        });

        return view('admin.pembayaran', compact('pembayaran', 'totalPembayaran', 'totalLunas', 'totalPending', 'search', 'unpaidContracts'));
    }

    // 📌 Halaman detail pembayaran (SHOW)
    public function show($id)
    {
        $pembayaran = Pembayaran::with(['kontrak.kelas', 'kontrak.pembayaran', 'pendaftar'])->findOrFail($id);
        
        $kontrak = $pembayaran->kontrak;

        // Hitung durasi dan total tagihan
        $durasi = $kontrak->durasi_bulan ?? 1;
        $totalTagihanKontrak = $kontrak->jumlah_peserta * ($kontrak->kelas->harga ?? 0) * $durasi;
        $totalBayarMasukKontrak = $kontrak->pembayaran->sum('jumlah_bayar');
        $sisaTagihanKontrak = $totalTagihanKontrak - $totalBayarMasukKontrak;
        $statusKontrakAgregat = ($sisaTagihanKontrak <= 0) ? 'lunas' : 'pending';
        
        $riskData = null;
        // ✅ PANGGIL API ML UNTUK HALAMAN DETAIL
        if ($kontrak->pendaftar && $totalTagihanKontrak > 0) {
             // Pastikan kontrak memiliki data yang dibutuhkan untuk dikirim ke API
             $kontrak->durasi_bulan = $durasi; 
             $kontrak->total_tagihan = $totalTagihanKontrak; 
             $riskData = $this->callMlRiskApi($kontrak);
        }

        // Kirim data agregat dan risiko ke view
        return view('admin.pembayaran.show', compact(
            'pembayaran', 
            'totalTagihanKontrak', 
            'totalBayarMasukKontrak', 
            'sisaTagihanKontrak', 
            'statusKontrakAgregat',
            'riskData' // Kirim data risiko ke view
        ));
    }
    
    // ... (Metode create, store, edit, update, destroy, dan getFinancialChartData tetap sama)
    // [CODE LAINNYA DIBAWAH INI SAMA DENGAN VERSI SEBELUMNYA]
    // Saya hanya menampilkan bagian yang diubah dan metode lainnya yang diperlukan

    // 📌 Halaman tambah pembayaran
    public function create()
    {
        // Pastikan kelas di-load agar harga bisa dihitung di view dan store
        $kontrak = Kontrak::with(['pendaftar', 'kelas'])->get();

        // Agregasi data untuk setiap kontrak di halaman create
        $kontrak->each(function ($k) {
            $durasi = $k->durasi_bulan ?? 1;
            $totalTagihan = $k->jumlah_peserta * ($k->kelas->harga ?? 0) * $durasi;
            $totalBayarMasuk = $k->pembayaran->sum('jumlah_bayar');
            $sisaTagihan = $totalTagihan - $totalBayarMasuk;

            $k->total_tagihan = $totalTagihan;
            $k->total_bayar_masuk = $totalBayarMasuk;
            $k->sisa_tagihan = $sisaTagihan;
        });
        
        return view('admin.pembayaran.create', compact('kontrak'));
    }


    // 📌 Simpan data pembayaran baru (MULTI-FILE LOGIC)
    public function store(Request $request)
    {
        $request->validate([
            'kontrak_id' => 'required|exists:kontrak,id',
            'jumlah_bayar' => 'required|numeric|min:1',
            // ✅ VALIDASI MULTIPLE FILES
            'bukti_pembayaran' => 'nullable|array', 
            'bukti_pembayaran.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
            'tanggal_bayar' => 'required|date',
            'status' => 'required|in:pending,lunas' 
        ]);

        $kontrak = Kontrak::with('kelas')->find($request->kontrak_id);

        if (!$kontrak) {
            return redirect()->back()->with('error', 'Kontrak tidak ditemukan.');
        }

        $durasi = $kontrak->durasi_bulan ?? 1;
        $totalTagihan = $kontrak->jumlah_peserta * ($kontrak->kelas->harga ?? 0) * $durasi;
        $jumlahBayar = (float) $request->jumlah_bayar;
        
        $totalBayarMasukSaatIni = Pembayaran::where('kontrak_id', $kontrak->id)->sum('jumlah_bayar') + $jumlahBayar;
        $statusOtomatis = ($totalBayarMasukSaatIni >= $totalTagihan) ? 'lunas' : 'pending';
        
        $data = [
            'kontrak_id' => $kontrak->id,
            'pendaftar_id' => $kontrak->pendaftar_id ?? null, 
            'jumlah_bayar' => $jumlahBayar,
            'tanggal_bayar' => $request->tanggal_bayar,
            'status' => $statusOtomatis, 
        ];

        // ✅ LOGIKA UPLOAD MULTIPLE FILES
        $uploadedFiles = [];
        if ($request->hasFile('bukti_pembayaran')) {
            $uploadPath = public_path('uploads/bukti_pembayaran');
            
            if (!File::isDirectory($uploadPath)) {
                File::makeDirectory($uploadPath, 0777, true, true);
            }

            foreach ($request->file('bukti_pembayaran') as $image) {
                if ($image) {
                    $imageName = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move($uploadPath, $imageName);
                    $uploadedFiles[] = $imageName;
                }
            }
        }
        
        // Simpan array nama file (Model akan otomatis mengonversi ke JSON)
        $data['bukti_pembayaran'] = $uploadedFiles;

        Pembayaran::create($data);

        return redirect()->route('admin.pembayaran')->with('success', 'Pembayaran berhasil ditambahkan. Status otomatis diatur: ' . $statusOtomatis);
    }

    // 📌 Halaman edit pembayaran
    public function edit($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $kontrak = Kontrak::with(['pendaftar', 'kelas'])->get(); 

        $kontrak->each(function ($k) {
            $durasi = $k->durasi_bulan ?? 1;
            $totalTagihan = $k->jumlah_peserta * ($k->kelas->harga ?? 0) * $durasi;
            $totalBayarMasuk = $k->pembayaran->sum('jumlah_bayar');
            $sisaTagihan = $totalTagihan - $totalBayarMasuk;

            $k->total_tagihan = $totalTagihan;
            $k->total_bayar_masuk = $totalBayarMasuk;
            $k->sisa_tagihan = $sisaTagihan;
        });

        return view('admin.pembayaran.edit', compact('pembayaran', 'kontrak'));
    }

    // 📌 Update data pembayaran (MULTI-FILE LOGIC)
    public function update(Request $request, $id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        
        $request->validate([
            'kontrak_id' => 'required|exists:kontrak,id',
            'jumlah_bayar' => 'required|numeric|min:1',
            // ✅ VALIDASI MULTIPLE FILES
            'bukti_pembayaran' => 'nullable|array',
            'bukti_pembayaran.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
            'tanggal_bayar' => 'required|date',
            'status' => 'required|in:pending,lunas'
        ]);

        $kontrak = Kontrak::with('kelas')->find($request->kontrak_id); 

        if (!$kontrak) {
            return redirect()->back()->with('error', 'Kontrak tidak ditemukan.');
        }
        
        $durasi = $kontrak->durasi_bulan ?? 1;
        $totalTagihan = $kontrak->jumlah_peserta * ($kontrak->kelas->harga ?? 0) * $durasi;
        $jumlahBayar = (float) $request->jumlah_bayar;

        $totalBayarLain = Pembayaran::where('kontrak_id', $kontrak->id)
                                    ->where('id', '!=', $pembayaran->id)
                                    ->sum('jumlah_bayar');
        $totalBayarMasukSaatIni = $totalBayarLain + $jumlahBayar;
        $statusOtomatis = ($totalBayarMasukSaatIni >= $totalTagihan) ? 'lunas' : 'pending';

        $data = [
            'kontrak_id' => $kontrak->id,
            'pendaftar_id' => $kontrak->pendaftar_id ?? null,
            'jumlah_bayar' => $jumlahBayar,
            'tanggal_bayar' => $request->tanggal_bayar,
            'status' => $statusOtomatis, 
        ];

        // ✅ PERBAIKAN LOGIKA MULTI-FILE: Tangani data lama (string) dan file baru
        $existingProof = $pembayaran->bukti_pembayaran;
        
        if (is_string($existingProof) && $existingProof) {
            // Data lama string tunggal -> konversi ke array
            $newFiles = [$existingProof];
        } elseif (is_array($existingProof)) {
            // Data sudah array
            $newFiles = $existingProof;
        } else {
            // Null atau kosong
            $newFiles = [];
        }

        if ($request->hasFile('bukti_pembayaran')) {
            $uploadPath = public_path('uploads/bukti_pembayaran');
            
            foreach ($request->file('bukti_pembayaran') as $image) {
                if ($image) {
                    $imageName = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move($uploadPath, $imageName);
                    $newFiles[] = $imageName; // Tambahkan ke array file baru
                }
            }
        }
        
        // Simpan array yang diperbarui
        $data['bukti_pembayaran'] = $newFiles;

        $pembayaran->update($data);

        return redirect()->route('admin.pembayaran')->with('success', 'Pembayaran berhasil diperbarui. Status otomatis dihitung ulang: ' . $statusOtomatis);
    }

    // 📌 Hapus data pembayaran (MULTI-FILE LOGIC)
    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        // ✅ LOGIKA HAPUS MULTIPLE FILES
        $buktiFiles = $pembayaran->bukti_pembayaran ?? [];
        $uploadPath = public_path('uploads/bukti_pembayaran');

        // Menggunakan is_array untuk menangani data lama (string)
        if (is_array($buktiFiles)) {
            foreach ($buktiFiles as $fileName) {
                $filePath = $uploadPath . '/' . $fileName;
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
            }
        } else {
            // Menangani case data lama yang masih berupa string tunggal (single file)
            $filePath = $uploadPath . '/' . $buktiFiles;
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }
        
        $pembayaran->delete();

        return redirect()->route('admin.pembayaran')->with('success', 'Data pembayaran berhasil dihapus.');
    }

    // 📌 Mengambil data grafik untuk AJAX di halaman Pembayaran
    public function getFinancialChartData(Request $request)
    {
        $period = $request->get('period', 'monthly');

        switch ($period) {
            case 'weekly':
                $startDate = Carbon::now()->subDays(6)->startOfDay();
                $dateFormat = '%Y-%m-%d'; 
                $labelFormat = 'D, d M'; 
                break;
            case 'yearly':
                $startDate = Carbon::now()->subYears(5)->startOfYear(); 
                $dateFormat = '%Y';
                $labelFormat = 'Y'; 
                break;
            case 'monthly':
            default:
                $startDate = Carbon::now()->subMonths(11)->startOfMonth(); 
                $dateFormat = '%Y-%m';
                $labelFormat = 'F Y'; 
                break;
        }

        $query = Pembayaran::selectRaw("SUM(jumlah_bayar) as total_amount, DATE_FORMAT(tanggal_bayar, '{$dateFormat}') as period_label")
            ->where('tanggal_bayar', '>=', $startDate) 
            ->whereNotNull('tanggal_bayar') 
            ->groupBy('period_label')
            ->orderBy('period_label', 'asc');
            
        $data = $query->get();

        $labels = [];
        $amounts = [];

        foreach ($data as $item) {
            try {
                if ($period == 'weekly') {
                    $labels[] = $item->period_label;
                } elseif ($period == 'monthly') {
                    $labels[] = Carbon::createFromFormat('Y-m', $item->period_label)->isoFormat($labelFormat);
                } else {
                    $labels[] = $item->period_label; 
                }
            } catch (\Exception $e) {
                $labels[] = $item->period_label;
            }

            $amounts[] = (int) $item->total_amount;
        }

        return response()->json([
            'period' => $period, 
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Pemasukan (Rp)',
                    'data' => $amounts,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.4)', 
                    'borderColor' => 'rgba(37, 99, 235, 1)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'fill' => true,
                ]
            ]
        ]);
    }

    // 📌 Metode untuk Cetak Laporan Pembayaran (Reverted: Tanpa Grafik)
    public function print()
    {
        $kontrak = Kontrak::with(['pendaftar', 'kelas', 'pembayaran'])->get();

        $totalPembayaran = Pembayaran::sum('jumlah_bayar');

        $totalLunas = 0;
        $totalPending = 0;

        foreach ($kontrak as $k) {
            $totalTagihan = $k->jumlah_peserta * ($k->kelas->harga ?? 0);
            $totalBayarMasuk = $k->pembayaran->sum('jumlah_bayar');
            $sisaTagihan = $totalTagihan - $totalBayarMasuk;

            $k->total_tagihan = $totalTagihan;
            $k->total_bayar_masuk = $totalBayarMasuk;
            $k->sisa_tagihan = $sisaTagihan;
            $k->status_pembayaran_agregasi = ($sisaTagihan <= 0) ? 'lunas' : 'pending';

            if ($sisaTagihan <= 0) {
                $totalLunas++;
            } else {
                $totalPending++;
            }
        }

        // Hanya mengirim data tabel dan ringkasan, tanpa chartData
        return view('admin.print.pembayaran-list', compact(
            'kontrak',
            'totalPembayaran',
            'totalLunas',
            'totalPending'
        ));
    }
}