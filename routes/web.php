<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// 🔥 FIX: Mengubah Controller Supervisor dari folder Auth ke folder Supervisor
use App\Http\Controllers\Supervisor\SupervisorDashboardController; 
use App\Http\Controllers\Supervisor\SupervisorModulesController; // ✅ CONTROLLER BARU

// ---------------- ADMIN CONTROLLERS ----------------
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KontrakController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\InformasiController;
use App\Http\Controllers\Admin\KelasController as AdminKelasController;
use App\Http\Controllers\Admin\TrainerController; 
use App\Http\Controllers\Admin\PrestasiController; 
use App\Http\Controllers\Admin\PrintController;
use App\Http\Controllers\Admin\ProfileController; 
use App\Http\Controllers\Admin\PesanKontakController; 

// ---------------- LANDING PAGE CONTROLLERS (Milik Teman Anda) ----------------
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\ContactController; 
use App\Http\Controllers\ClassController; 

// ---------------- MODELS DIPERLUKAN DI RUTE ----------------
use App\Models\Trainer; 
use App\Models\Prestasi; 

// --------ML--------
use Illuminate\Support\Facades\Http;

// 🤖 4. INTEGRASI MACHINE LEARNING (FASTAPI SENTIMENT SERVICE)
// ==========================================================
Route::post('/sentiment/analyze', function (Request $request) {
    $text = $request->input('text');

    // Kirim ke FastAPI service di port 8080
    $response = Http::post('http://127.0.0.1:8080/sentiment/predict', [
        'text' => $text
    ]);

    return $response->json();
});

// ==========================================================
// 🚀 1. LANDING PAGE & PENDAFTARAN ROUTES (AREA PUBLIK)
// ==========================================================

// Rute Halaman Utama (Home Page)
Route::get('/', [ClassController::class, 'index'])->name('home');

// Route Detail Kelas
Route::get('/kelas/{id}', [ClassController::class, 'show'])->name('kelas.show');

// Route Detail Berita
Route::get('/berita/{id}', [ClassController::class, 'showBerita'])->name('berita.show');

// Route untuk menyimpan pesan kontak dari halaman publik
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Route Formulir Kontak (Duplikasi yang Anda miliki)
Route::post('/kontak', [ContactController::class, 'store']); 

// --- RUTE PENDAFTARAN (3 STEP) ---
Route::get('/register', [PendaftaranController::class, 'showStepOne'])->name('register.step1.show');
Route::post('/register/step1', [PendaftaranController::class, 'storeStepOne'])->name('register.step1.store');
Route::get('/konfirmasi-pembayaran', [PendaftaranController::class, 'showStepTwo'])->name('register.step2.show');
Route::post('/register/step2', [PendaftaranController::class, 'storeStepTwo'])->name('register.step2.store');
Route::get('/pendaftaran-sukses', [PendaftaranController::class, 'showSuccess'])->name('register.success');

// Chatbot 
Route::post('/chatbot', function (Request $request) {
    $message = strtolower($request->input('message'));

    $responses = [
        'halo' => 'Halo juga! Ada yang bisa saya bantu? 😊',
        'daftar' => 'Untuk daftar, klik tombol “Daftar Sekarang” di halaman utama ya!',
        'kelas' => 'Kami punya berbagai kelas: Full-Stack, UI/UX, dan Data Science!',
        'harga' => 'Harga kelas mulai dari Rp700.000 hingga Rp1.500.000 💰',
        'terima kasih' => 'Sama-sama! Senang bisa membantu 😄'
    ];

    $reply = 'Maaf, saya belum mengerti pertanyaan kamu. Coba ketik: halo, daftar, kelas, atau harga.';

    foreach ($responses as $key => $response) {
        if (str_contains($message, $key)) {
            $reply = $response;
            break;
        }
    }

    return response()->json(['reply' => $reply]);
});


// ==========================================================
// 🔒 2. AUTH ROUTES (Login & Logout)
// ==========================================================

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout')
    ->middleware('auth');


// ==========================================================
// 🛡️ 3. ADMIN & RESOURCES ROUTES
// ==========================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Admin Dashboard
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Rute AJAX untuk Dashboard Chart
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getFinancialChartData'])->name('dashboard.chartData');
    
    // Rute Profil Admin
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // RUTE PESAN KONTAK ADMIN
    Route::get('pesan-kontak', [PesanKontakController::class, 'index'])->name('pesan.index');
    Route::get('pesan-kontak/{id}', [PesanKontakController::class, 'show'])->name('pesan.show');
    Route::delete('pesan-kontak/{id}', [PesanKontakController::class, 'destroy'])->name('pesan.destroy');
    
    // Informasi (General & Prestasi)
    Route::get('/informasi', [InformasiController::class, 'index'])->name('informasi');
    Route::get('/informasi/create', [InformasiController::class, 'create'])->name('informasi.create'); 
    Route::post('/informasi', [InformasiController::class, 'store'])->name('informasi.store');
    Route::get('/informasi/{informasi}', [InformasiController::class, 'show'])->name('informasi.show');
    Route::resource('informasi', InformasiController::class)->only(['edit', 'update', 'destroy']);

    // Trainer Routes (CRUD Penuh)
    Route::get('/trainer', [TrainerController::class, 'index'])->name('trainer.index');
    Route::get('/trainer/create', [TrainerController::class, 'create'])->name('trainer.create');
    Route::post('/trainer', [TrainerController::class, 'store'])->name('trainer.store'); 
    Route::get('/trainer/{trainer}', [TrainerController::class, 'show'])->name('trainer.show');
    Route::get('/trainer/{trainer}/edit', [TrainerController::class, 'edit'])->name('trainer.edit');
    Route::put('/trainer/{trainer}', [TrainerController::class, 'update'])->name('trainer.update');
    Route::delete('/trainer/{trainer}', [TrainerController::class, 'destroy'])->name('trainer.destroy');
    
    // Prestasi Routes (CRUD Penuh)
    Route::resource('prestasi', PrestasiController::class);
    
    // KELAS ROUTES (ADMIN)
    Route::get('/kelas', [AdminKelasController::class, 'index'])->name('kelas.index');
    Route::resource('kelas', AdminKelasController::class)->except(['index'])->parameters(['kelas' => 'kelas']); 

    // Kontrak Routes
    Route::get('/kontrak', [KontrakController::class, 'index'])->name('kontrak');
    Route::get('/kontrak/create', [KontrakController::class, 'create'])->name('kontrak.create');
    Route::post('/kontrak', [KontrakController::class, 'store'])->name('kontrak.store');
    Route::get('/kontrak/{id}', [KontrakController::class, 'show'])->name('kontrak.show'); 
    Route::get('/kontrak/{id}/edit', [KontrakController::class, 'edit'])->name('kontrak.edit');
    Route::put('/kontrak/{id}', [KontrakController::class, 'update'])->name('kontrak.update');
    Route::delete('/kontrak/{id}', [KontrakController::class, 'destroy'])->name('kontrak.destroy');
    
    // Pembayaran Routes (DIDEFINISIKAN MANUAL)
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran');
    
    // --- Rute Pembayaran Statis (Harus di atas rute dinamis {id}) ---
    // Rute AJAX untuk Chart di halaman Pembayaran
    Route::get('/pembayaran/chart-data', [PembayaranController::class, 'getFinancialChartData'])->name('pembayaran.chartData');
    // Rute untuk Cetak Laporan Pembayaran
    Route::get('/pembayaran/print', [PembayaranController::class, 'print'])->name('pembayaran.print');
    // --- End Rute Pembayaran Statis ---

    Route::get('/pembayaran/create', [PembayaranController::class, 'create'])->name('pembayaran.create');
    Route::post('/pembayaran/store', [PembayaranController::class, 'store'])->name('pembayaran.store');
    Route::get('/pembayaran/{id}', [PembayaranController::class, 'show'])->name('pembayaran.show');
    Route::get('/pembayaran/{id}/edit', [PembayaranController::class, 'edit'])->name('pembayaran.edit'); 
    Route::put('/pembayaran/{id}', [PembayaranController::class, 'update'])->name('pembayaran.update'); 
    Route::delete('/pembayaran/{id}', [PembayaranController::class, 'destroy'])->name('pembayaran.destroy');
    
    // Lainnya
    Route::get('/get-kontrak/{pendaftar_id}', [PembayaranController::class, 'getKontrak']);

    // ---------------- PRINT ----------------
    Route::get('/kontrak/{id}/print', [PrintController::class, 'printKontrak'])->name('kontrak.print');
    Route::get('/kontrak/print/all', [KontrakController::class, 'print'])->name('kontrak.printAll');
});

// ==========================================================
// 🛡️ 4. SUPERVISOR ROUTES (Read-Only)
// ==========================================================
Route::middleware(['auth', 'role:supervisor'])->prefix('supervisor')->name('supervisor.')->group(function () {
    
    // Dashboard Supervisor (Monitoring & Analytics)
    Route::get('/dashboard', [SupervisorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chart-data', [SupervisorDashboardController::class, 'getFinancialChartData'])->name('dashboard.chartData');
    
    // Modul Kontrak (Read-Only)
    Route::get('/kontrak', [SupervisorModulesController::class, 'kontrakIndex'])->name('kontrak');
    
    // Modul Pembayaran (Read-Only)
    Route::get('/pembayaran', [SupervisorModulesController::class, 'pembayaranIndex'])->name('pembayaran');
});
