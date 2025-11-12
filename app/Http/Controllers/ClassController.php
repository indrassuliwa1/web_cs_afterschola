<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Trainer;
use App\Models\Prestasi;
use App\Models\Informasi; // Model Berita/Informasi

class ClassController extends Controller
{
    /**
     * Display the homepage with classes, trainers, and prestasi.
     */
    public function index()
    {
        $kelasList = Kelas::all();
        $trainers = Trainer::all();
        $prestasis = Prestasi::all();
        
        // Ambil data berita/informasi (3 terbaru)
        $beritas = Informasi::latest()->take(3)->get(); 

        // Kirim SEMUA data ke view 'welcome'
        return view('welcome', [
            'kelasList' => $kelasList,
            'trainers' => $trainers,
            'prestasis' => $prestasis,
            'beritas' => $beritas // Mengirim data ke view dengan nama 'beritas'
        ]);
    }

    // ----------------------------------------------------------------------
    // FUNGSI DETAIL KELAS
    // ----------------------------------------------------------------------

    /**
     * Display the detail page for a specific class.
     */
public function show($id) 
    {
        // GANTI: $kelas = Kelas::where('slug', $id)->firstOrFail(); 

        // ✅ CARA YANG BENAR: Menggunakan findOrFail untuk Primary Key (ID)
        $kelas = Kelas::findOrFail($id); 

        // Mengirim objek $kelas ke view class-detail.blade.php
        return view('class-detail', compact('kelas')); 
    }
    // ----------------------------------------------------------------------
    // FUNGSI DETAIL BERITA (Mencari berdasarkan ID dan Memanggil View yang Benar)
    // ----------------------------------------------------------------------

    /**
     * Display the detail page for a specific news item.
     */
    public function showBerita(string $id) // Menerima ID dari URL
    {
        // Mencari data berdasarkan primary key (ID)
        // Ini mengatasi error "Unknown column 'slug'"
        $berita = Informasi::findOrFail($id); 
        
        // KRITIS: Memanggil view 'detail.berita' (untuk file detail.berita.blade.php)
        // Ini mengatasi error "View [detail.berita] not found"
        return view('berita-detail', ['informasi' => $berita]); 
    }
}