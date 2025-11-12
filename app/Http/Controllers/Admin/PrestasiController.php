<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prestasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File; // Wajib di-import

class PrestasiController extends Controller
{
    // Display a listing of the resource. (Redirects to consolidated list view)
    public function index(Request $request)
    {
        // FIX: Mengarahkan index ke halaman informasi utama yang menangani listing tab prestasi
        return redirect()->route('admin.informasi', ['kategori' => 'prestasi']);
    }

    // Show the form for creating a new resource.
    public function create()
    {
        return view('admin.prestasi.create');
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_dicapai' => 'required|date',
            'bukti_foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'dokumentasi' => 'nullable|array', 
            'dokumentasi.*' => 'image|mimes:jpg,jpeg,png|max:2048', 
        ]);

        $data = $request->only(['judul', 'deskripsi', 'tanggal_dicapai']);
        $data['author_id'] = Auth::id();
        
        // 1. Upload Foto Utama
        $data['bukti_foto'] = null;
        if ($request->hasFile('bukti_foto')) {
            $image = $request->file('bukti_foto');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/prestasi'), $imageName);
            $data['bukti_foto'] = $imageName;
        }

        // 2. Upload Dokumentasi Tambahan (Multi-File)
        $dokumentasiFiles = [];
        if ($request->hasFile('dokumentasi')) {
            $uploadPath = public_path('uploads/prestasi/dokumentasi');
            // Membuat folder jika belum ada (wajib!)
            if (!File::isDirectory($uploadPath)) {
                File::makeDirectory($uploadPath, 0777, true, true);
            }

            foreach ($request->file('dokumentasi') as $file) {
                // Hanya memproses file yang valid
                if ($file->isValid()) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $file->move($uploadPath, $fileName);
                    $dokumentasiFiles[] = $fileName;
                }
            }
        }
        $data['dokumentasi'] = $dokumentasiFiles; // ✅ Simpan array nama file

        Prestasi::create($data);

        return redirect()->route('admin.informasi', ['kategori' => 'prestasi'])
                         ->with('success', 'Data Prestasi ' . $request->judul . ' berhasil ditambahkan.');
    }

    // Display the specified resource.
    public function show(Prestasi $prestasi)
    {
        $prestasi->load('author');
        return view('admin.prestasi.show', compact('prestasi'));
    }

    // Show the form for editing the specified resource.
    public function edit(Prestasi $prestasi)
    {
        return view('admin.prestasi.edit', compact('prestasi'));
    }

    // Update the specified resource in storage.
    public function update(Request $request, Prestasi $prestasi)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_dicapai' => 'required|date',
            'bukti_foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'dokumentasi' => 'nullable|array', 
            'dokumentasi.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['judul', 'deskripsi', 'tanggal_dicapai']);
        // Ambil array dokumentasi lama, pastikan itu array
        $dokumentasiFiles = is_array($prestasi->dokumentasi) ? $prestasi->dokumentasi : []; 

        // 1. Upload/Ganti Foto Utama
        $imageName = $prestasi->bukti_foto;
        if ($request->hasFile('bukti_foto')) {
            // Hapus foto lama jika ada
            if ($prestasi->bukti_foto && File::exists(public_path('uploads/prestasi/' . $prestasi->bukti_foto))) {
                File::delete(public_path('uploads/prestasi/' . $prestasi->bukti_foto));
            }
            // Upload foto baru
            $image = $request->file('bukti_foto');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/prestasi'), $imageName);
        }
        $data['bukti_foto'] = $imageName;

        // 2. Tambahkan Dokumentasi Baru (Gabungkan dengan yang Lama)
        if ($request->hasFile('dokumentasi')) {
            $uploadPath = public_path('uploads/prestasi/dokumentasi');
            if (!File::isDirectory($uploadPath)) {
                File::makeDirectory($uploadPath, 0777, true, true);
            }
            
            $buktiBaru = [];
            foreach ($request->file('dokumentasi') as $file) {
                if ($file->isValid()) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $file->move($uploadPath, $fileName);
                    $buktiBaru[] = $fileName;
                }
            }
            // ✅ FIX: Menggabungkan array lama dan array baru
            $data['dokumentasi'] = array_merge($dokumentasiFiles, $buktiBaru); 
        } else {
             // Jika tidak ada file baru diupload, pertahankan dokumentasi lama
             $data['dokumentasi'] = $dokumentasiFiles;
        }
        
        $prestasi->update($data);

        return redirect()->route('admin.informasi', ['kategori' => 'prestasi'])
                         ->with('success', 'Data Prestasi ' . $request->judul . ' berhasil diperbarui.');
    }

    // Remove the specified resource from storage.
    public function destroy(Prestasi $prestasi)
    {
        // 1. Hapus Foto Utama
        if ($prestasi->bukti_foto && File::exists(public_path('uploads/prestasi/' . $prestasi->bukti_foto))) {
            File::delete(public_path('uploads/prestasi/' . $prestasi->bukti_foto));
        }

        // 2. Hapus Semua File Dokumentasi
        $dokumentasiArray = is_array($prestasi->dokumentasi) ? $prestasi->dokumentasi : [];
        if (!empty($dokumentasiArray)) {
            foreach ($dokumentasiArray as $fileName) {
                $filePath = public_path('uploads/prestasi/dokumentasi/' . $fileName);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
            }
        }

        $prestasi->delete();

        return redirect()->route('admin.informasi', ['kategori' => 'prestasi'])
                         ->with('success', 'Data Prestasi berhasil dihapus.');
    }
}
