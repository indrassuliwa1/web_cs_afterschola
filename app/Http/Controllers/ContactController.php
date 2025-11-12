<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Ganti: use App\Models\ContactMessage;
use App\Models\PesanKontak; // <-- GUNAKAN MODEL YANG BENAR

class ContactController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'contactName' => 'required|string|max:255',
            'contactEmail' => 'required|email|max:255',
            'contactComment' => 'required|string',
        ]);

        // 2. Simpan ke Database (menggunakan PesanKontak)
        PesanKontak::create($validated); // <-- GUNAKAN MODEL YANG BENAR

        // 3. Redirect dengan pesan sukses
        // Pastikan Anda menggunakan nama sesi 'success_contact' jika form Anda menggunakan logic itu.
        return redirect('/#kontak')->with('success_contact', 'Pesan Anda berhasil terkirim! Kami akan segera merespon.');
    }
}