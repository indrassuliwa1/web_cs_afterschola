<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // Wajib di-import

class KelasController extends Controller
{
    // 📌 Menampilkan daftar kelas
    public function index()
    {
        $kelasList = Kelas::withCount('kontrak')->latest()->paginate(10); 
        return view('admin.kelas', compact('kelasList')); // Mengubah ke view root admin/kelas.blade.php
    }

    // 📌 Menampilkan form tambah
    public function create()
    {
        return view('admin.kelas.create');
    }

    // 📌 Menyimpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'foto_kelas' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // ✅ Validasi Foto
        ]);

        $data = $request->except('foto_kelas');
        
        // Logika Upload Foto
        if ($request->hasFile('foto_kelas')) {
            $file = $request->file('foto_kelas');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/kelas'), $fileName);
            $data['foto_kelas'] = $fileName;
        }

        Kelas::create($data);

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }
    
    // 📌 Menampilkan detail (show)
    public function show(Kelas $kelas)
    {
        return view('admin.kelas.show', compact('kelas'));
    }

    // 📌 Menampilkan form edit
    public function edit(Kelas $kelas)
    {
        return view('admin.kelas.edit', compact('kelas'));
    }

    // 📌 Memperbarui data
    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'foto_kelas' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except('foto_kelas');
        $oldPhoto = $kelas->foto_kelas;

        // Logika Update Foto
        if ($request->hasFile('foto_kelas')) {
            // Hapus foto lama
            if ($oldPhoto && File::exists(public_path('uploads/kelas/' . $oldPhoto))) {
                File::delete(public_path('uploads/kelas/' . $oldPhoto));
            }
            
            $file = $request->file('foto_kelas');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/kelas'), $fileName);
            $data['foto_kelas'] = $fileName;
        }

        $kelas->update($data);

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    }
    
    // 📌 Menghapus data
    public function destroy(Kelas $kelas)
    {
        // Hapus foto terkait sebelum menghapus entri database
        if ($kelas->foto_kelas && File::exists(public_path('uploads/kelas/' . $kelas->foto_kelas))) {
            File::delete(public_path('uploads/kelas/' . $kelas->foto_kelas));
        }

        $kelas->delete();
        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}
