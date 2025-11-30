<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prestasi; // Pastikan Model Prestasi dipanggil

// Nama Class harus SAMA PERSIS dengan nama File
class PrestasicontrollerDetail extends Controller
{
    public function show($id)
    {
        // Ambil data prestasi berdasarkan ID
        $prestasi = Prestasi::findOrFail($id);

        // Arahkan ke view detail (resources/views/prestasi/show.blade.php)
        return view('prestasi.show', compact('prestasi'));
    }
}