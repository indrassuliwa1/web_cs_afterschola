<?php

namespace App\Http\Controllers;

use App\Models\Informasi;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    /**
     * Menampilkan detail berita berdasarkan ID.
     */
    public function show($id)
    {
        // Pencarian data berdasarkan ID
        $berita = Informasi::findOrFail($id);

        // Mengembalikan view 'detail.berita'
        return view('berita-detail', compact('berita'));
    }
}
