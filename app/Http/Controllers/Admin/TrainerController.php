<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trainer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // Tambahkan ini untuk operasi File::delete

class TrainerController extends Controller
{
    /**
     * Menampilkan daftar semua Trainer menggunakan view 'admin.informasi'.
     */
    public function index()
    {
        // ... (Logika untuk index tetap sama, diarahkan ke informasi.blade.php) ...
        
        $trainers = Trainer::latest()->paginate(10);
        
        // Asumsi Anda perlu total count untuk navigasi tab
        $totalGeneral = \App\Models\Informasi::count(); 
        $totalTrainer = $trainers->total(); 
        $totalPrestasi = \App\Models\Prestasi::count();

        $dataInformasi = $trainers;
        $activeCategory = 'trainer';
        
        return view('admin.informasi', compact(
            'dataInformasi', 
            'activeCategory', 
            'totalGeneral', 
            'totalTrainer', 
            'totalPrestasi'
        )); 
    }

    /**
     * Menampilkan form untuk menambah Trainer baru.
     */
    public function create()
    {
        // ✅ KOREKSI VIEW: Menggunakan 'admin.trainer.create' karena file ada di resources/views/admin/trainer/create.blade.php
        return view('admin.trainer.create'); 
    }

    /**
     * Menyimpan data Trainer baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'spesialisasi' => 'required|string|max:255',
            'deskripsi' => 'required|string', // Wajib karena form Summernote diset required
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048', 
        ]);

        $fileName = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/trainer_photos'), $fileName);
        }

        $dataToStore = $request->only([
            'nama', 
            'spesialisasi', 
            'deskripsi', 
        ]);
        $dataToStore['foto'] = $fileName;

        Trainer::create($dataToStore);

        return redirect()->route('admin.trainer.index')
                         ->with('success', 'Trainer berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail Trainer tertentu.
     */
    public function show($trainer) 
    {
        $trainer = Trainer::findOrFail($trainer);
        // ✅ KOREKSI VIEW: Menggunakan 'admin.trainer.show'
        return view('admin.trainer.show', compact('trainer'));
    }

    /**
     * Menampilkan form untuk mengedit Trainer tertentu.
     */
    public function edit($trainer) 
    {
        $trainer = Trainer::findOrFail($trainer);
        // ✅ KOREKSI VIEW: Menggunakan 'admin.trainer.edit'
        return view('admin.trainer.edit', compact('trainer'));
    }

    /**
     * Memperbarui data Trainer di database.
     */
    public function update(Request $request, $trainer) 
    {
        $trainer = Trainer::findOrFail($trainer);

        $request->validate([
            'nama' => 'required|string|max:100',
            'spesialisasi' => 'required|string|max:255',
            'deskripsi' => 'required|string', 
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Nullable karena opsional saat update
        ]);

        $dataToUpdate = $request->only([
            'nama', 
            'spesialisasi', 
            'deskripsi'
        ]);
        
        $fileName = $trainer->foto; // Pertahankan file lama

        if ($request->hasFile('foto')) {
            // Hapus file lama
            if ($trainer->foto && File::exists(public_path('uploads/trainer_photos/' . $trainer->foto))) {
                File::delete(public_path('uploads/trainer_photos/' . $trainer->foto));
            }

            // Simpan file baru
            $file = $request->file('foto');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/trainer_photos'), $fileName);
        }
        
        $dataToUpdate['foto'] = $fileName;
        
        $trainer->update($dataToUpdate);

        return redirect()->route('admin.trainer.index')
                         ->with('success', 'Data Trainer berhasil diperbarui!');
    }

    /**
     * Menghapus Trainer dari database.
     */
    public function destroy($trainer) 
    {
        $trainer = Trainer::findOrFail($trainer);
        
        // Hapus foto dari server
        if ($trainer->foto && File::exists(public_path('uploads/trainer_photos/' . $trainer->foto))) {
            File::delete(public_path('uploads/trainer_photos/' . $trainer->foto));
        }
        
        $trainer->delete();

        return redirect()->route('admin.trainer.index')
                         ->with('success', 'Trainer berhasil dihapus!');
    }
}