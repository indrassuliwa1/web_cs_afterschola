<?php

namespace App\Http\Controllers\Supervisor; // ✅ Namespace diperbarui ke Supervisor

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Pendaftar;
use App\Models\Informasi;
use App\Models\Kontrak;
use App\Models\Pembayaran; 
use App\Models\PesanKontak; // Ditambahkan untuk data Sentimen
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request; 
use Carbon\Carbon; 

class SupervisorDashboardController extends Controller
{
    /**
     * Menampilkan dashboard Supervisor dengan data statistik dan data untuk chart.
     */
    public function index()
    {
        // 1. Ambil Statistik Dasar
        $totalUser = User::count();
        $totalKelas = Kelas::count();
        $totalPendaftar = Pendaftar::count();
        $totalInformasi = Informasi::count();

        // 2. Hitung Total Pemasukan
        $totalPemasukan = Pembayaran::sum('jumlah_bayar');
        
        // 3. Hitung Kontrak Belum Lunas 
        $unpaidContractsCount = Kontrak::whereDoesntHave('pembayaran')->count(); 

        // 4. Ambil Pendaftar/Kontrak Terbaru (5 Data Terakhir)
        $pendaftarTerbaru = Kontrak::with('pendaftar')
            ->latest()
            ->take(5)
            ->get();
        
        // 5. Ambil Informasi Terbaru (Berita)
        $informasiTerbaru = Informasi::latest()->take(5)->get();
        
        // 6. ANALISIS KEPUASAN PENGGUNA (Menggunakan query yang sama dengan Admin)
        $sentimentResults = PesanKontak::select('sentiment_result', DB::raw('count(*) as total'))
            ->groupBy('sentiment_result')
            ->whereNotNull('sentiment_result')
            ->pluck('total', 'sentiment_result')
            ->toArray();

        // Fallback jika tidak ada data
        if (empty($sentimentResults)) {
             // Menggunakan default 0 agar chart tidak error jika tabel kosong
             $sentimentResults = [ 'positive' => 0, 'negative' => 0, 'neutral' => 0 ];
        }

        $sentimentData = [
            'labels' => array_keys($sentimentResults),
            'counts' => array_values($sentimentResults)
        ];
        
        // Catatan: Variabel $actionItems tidak diperlukan untuk Supervisor.

        return view('supervisor.dashboard', compact( 
            'totalUser',
            'totalKelas',
            'totalPendaftar',
            'totalInformasi',
            'totalPemasukan', 
            'informasiTerbaru',
            'pendaftarTerbaru',
            'unpaidContractsCount',
            'sentimentData'
        ));
    }

    /**
     * Metode AJAX untuk grafik (Identik dengan DashboardController).
     */
    public function getFinancialChartData(Request $request)
    {
        $period = $request->get('period', 'monthly');

        switch ($period) {
            case 'weekly':
                $startDate = Carbon::now()->subDays(6)->startOfDay();
                $sqlDateFormat = '%Y-%m-%d'; 
                $grouping = 'date';
                $labelFormat = 'D, d M';
                break;
            case 'yearly':
                $startDate = Carbon::now()->subYears(5)->startOfYear();
                $sqlDateFormat = '%Y';
                $grouping = 'year';
                $labelFormat = 'Y'; 
                break;
            case 'monthly':
            default:
                $startDate = Carbon::now()->subMonths(11)->startOfMonth();
                $sqlDateFormat = '%Y-%m';
                $grouping = 'month';
                $labelFormat = 'F Y'; 
                break;
        }

        // 1. Query Data Pemasukan (Pembayaran)
        $paymentData = Pembayaran::selectRaw("
            SUM(jumlah_bayar) as total_amount, 
            DATE_FORMAT(tanggal_bayar, '{$sqlDateFormat}') as period_key
        ")
            ->where('tanggal_bayar', '>=', $startDate) 
            ->whereNotNull('tanggal_bayar') 
            ->groupBy('period_key')
            ->orderBy('period_key', 'asc')
            ->get()
            ->keyBy('period_key'); 

        // 2. Query Data Pendaftar (Kontrak)
        $contractData = Kontrak::selectRaw("
            COUNT(id) as total_contracts,
            DATE_FORMAT(created_at, '{$sqlDateFormat}') as period_key
        ")
            ->where('created_at', '>=', $startDate)
            ->groupBy('period_key')
            ->orderBy('period_key', 'asc')
            ->get()
            ->keyBy('period_key'); 

        // 3. Gabungkan Data dan Isi Data Kosong (Zero-filling)
        $labels = [];
        $paymentAmounts = [];
        $contractCounts = [];
        $currentDate = $startDate->copy();
        $endDate = Carbon::now();

        while ($currentDate->lessThanOrEqualTo($endDate)) {
            
            if ($grouping === 'date') {
                $periodKey = $currentDate->format('Y-m-d');
            } elseif ($grouping === 'month') {
                $periodKey = $currentDate->format('Y-m');
            } else { // yearly
                $periodKey = $currentDate->format('Y');
            }


            // Format Label untuk dikirim ke JS 
            if ($period === 'weekly') {
                $labels[] = $periodKey; 
            } elseif ($period === 'monthly') {
                $labels[] = Carbon::createFromFormat('Y-m', $periodKey)->isoFormat($labelFormat);
            } else {
                $labels[] = $periodKey; 
            }

            // Dapatkan Nilai, default 0
            $paymentAmounts[] = (int) ($paymentData[$periodKey]->total_amount ?? 0);
            $contractCounts[] = (int) ($contractData[$periodKey]->total_contracts ?? 0);

            // Pindah ke periode berikutnya
            if ($grouping === 'date') {
                $currentDate->addDay();
            } elseif ($grouping === 'month') {
                $currentDate->addMonth();
            } elseif ($grouping === 'year') {
                $currentDate->addYear();
            }
        }

        if ($period === 'yearly') {
            $labels = array_unique($labels);
        }

        return response()->json([
            'period' => $period,
            'labels' => array_values($labels),
            'datasets' => [
                // Dataset 1: Pemasukan (Garis Biru Tua)
                [
                    'label' => 'Pemasukan (Rp)',
                    'data' => array_values($paymentAmounts),
                    'yAxisID' => 'y1', // Sumbu Y 1 (Kiri)
                    'backgroundColor' => 'rgba(22, 67, 112, 0.2)', 
                    'borderColor' => '#164370', 
                    'borderWidth' => 3, 
                    'tension' => 0.4,
                    'fill' => true,
                ],
                // Dataset 2: Pendaftar (Garis Merah/Kuning)
                [
                    'label' => 'Pendaftar (Kontrak Baru)',
                    'data' => array_values($contractCounts),
                    'yAxisID' => 'y2', // Sumbu Y 2 (Kanan)
                    'backgroundColor' => 'rgba(245, 158, 11, 0.2)', 
                    'borderColor' => '#F59E0B', 
                    'borderWidth' => 3,
                    'tension' => 0.4,
                    'fill' => false, 
                ]
            ]
        ]);
    }
}