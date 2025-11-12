<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Pendaftar;
use App\Models\Informasi;
use App\Models\Kontrak;
use App\Models\Pembayaran; 
use App\Models\PesanKontak; 
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request; 
use Carbon\Carbon; 

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard admin dengan data statistik dan data untuk chart.
     */
    public function __invoke()
    {
        // 1. Ambil Statistik Dasar
        $totalUser = User::count();
        $totalKelas = Kelas::count();
        $totalPendaftar = Pendaftar::count();
        $totalInformasi = Informasi::count();

        // 2. Hitung Total Pemasukan (Total Pembayaran yang sudah masuk)
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
        
        // 6. ✅ ANALISIS KEPUASAN PENGGUNA (DUMMY DATA: AGAR CHART TAMPIL)
        $sentimentResults = [
            'positive' => 60,
            'negative' => 15,
            'neutral' => 25
        ];

        $sentimentData = [
            'labels' => array_keys($sentimentResults),
            'counts' => array_values($sentimentResults)
        ];
        
        // 7. ✅ LOGIKA PRIORITAS AKSI (ACTION URGENCY) - DUMMY DATA
        // Menggunakan data DUMMY SEMENTARA agar widget prioritas tampil
        $actionItems = [
            [
                'type' => 'Pesan Negatif',
                'description' => 'Balas pesan dari (DUMMY 1)',
                'urgency' => 'Tinggi',
                'time_ago' => '1 jam lalu',
                'url' => route('admin.pesan.index'),
            ],
            [
                'type' => 'Kontrak Risiko',
                'description' => 'Follow up kontrak (DUMMY 2)',
                'urgency' => 'Menengah',
                'time_ago' => '1 hari lalu',
                'url' => route('admin.pembayaran'),
            ],
        ];

        // Mengirim semua variabel yang dibutuhkan ke view
        return view('admin.dashboard', compact(
            'totalUser',
            'totalKelas',
            'totalPendaftar',
            'totalInformasi',
            'totalPemasukan', 
            'informasiTerbaru',
            'pendaftarTerbaru',
            'unpaidContractsCount',
            'sentimentData', 
            'actionItems' 
        ));
    }

    /**
     * Mengambil data pemasukan dan pendaftar untuk grafik berdasarkan periode yang diminta.
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