<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PesanKontak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Wajib untuk memanggil API ML

class PesanKontakController extends Controller
{
    /**
     * Menampilkan daftar semua pesan kontak, diurutkan dari terbaru.
     */
    public function index()
    {
        // Mengambil semua pesan dengan pagination
        $pesan = PesanKontak::orderBy('created_at', 'desc')->paginate(10);
        
        // Menghitung pesan belum dibaca untuk badge di layout
        $unreadCount = PesanKontak::where('is_read', false)->count();
        view()->share('unreadCount', $unreadCount); 

        // Mengirim data ke view index (Kritis: Mengubah ke admin.pesan_kontak.index)
        return view('admin.pesan_kontak.index', compact('pesan'));
    }

    /**
     * Menampilkan detail pesan tertentu dan menandainya sebagai sudah dibaca + Analisis Sentimen.
     */
    public function show($id)
    {
        // Mencari pesan berdasarkan ID
        $pesan = PesanKontak::findOrFail($id);
        
        // Tandai sebagai sudah dibaca jika statusnya belum dibaca
        if (!$pesan->is_read) {
            $pesan->is_read = true;
            $pesan->save();
        }

        $sentimentResult = null;
        $mlApiUrl = 'http://127.0.0.1:8080/sentiment/predict';
        $messageText = $pesan->contactComment; // Ambil teks komentar untuk analisis

        try {
            // Panggil API Machine Learning Sentimen
            $response = Http::timeout(5)->post($mlApiUrl, [
                'text' => $messageText,
            ]);

            if ($response->successful()) {
                $sentimentResult = $response->json();
            } else {
                $sentimentResult = [
                    'error' => true,
                    'status' => $response->status(),
                    'message' => 'ML Service Error: ' . $response->status(),
                ];
            }

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $sentimentResult = [
                'error' => true,
                'status' => 'Connection Refused',
                'message' => 'ML Service down or not running on port 8080.',
            ];
        }

        // Mengirim data ke view show (Kritis: Mengubah ke admin.pesan_kontak.show)
        return view('admin.pesan_kontak.show', compact('pesan', 'sentimentResult'));
    }

    // 🟨 Menghapus pesan kontak (Destroy method tetap)
    public function destroy($id)
    {
        PesanKontak::findOrFail($id)->delete();
        // Redirect menggunakan nama rute yang benar (admin.pesan.index)
        return redirect()->route('admin.pesan.index')->with('success', 'Pesan berhasil dihapus.');
    }
}