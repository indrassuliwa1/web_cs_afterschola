<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Informasi;
use App\Models\Trainer;
use App\Models\Prestasi; // ✅ Model Prestasi digunakan untuk pemuatan data
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File; 

class InformasiController extends Controller
{
    // Hanya menangani kategori yang tersisa di tabel Informasi
    private $validInformasiCategories = ['general']; 

    // 📌 Tampilkan semua data, difilter berdasarkan tab (kategori/trainer/prestasi)
    public function index(Request $request)
    {
        $activeCategory = $request->get('kategori', 'general'); 
        
        $dataList = null; 
        
        // --- LOGIKA PEMUATAN DATA BERDASARKAN TAB AKTIF ---
        if ($activeCategory === 'trainer') {
            // MEMUAT DATA TRAINER
            $dataList = Trainer::latest()
                ->paginate(10)
                ->appends(['kategori' => $activeCategory]);

        } elseif ($activeCategory === 'prestasi') {
            // MEMUAT DATA PRESTASI DARI TABEL 'PRESTASIS'
            $dataList = Prestasi::with('author') 
                ->latest()
                ->paginate(10)
                ->appends(['kategori' => $activeCategory]);

        } else {
            // MEMUAT DATA INFORMASI (General SAJA)
            if (!in_array($activeCategory, $this->validInformasiCategories)) {
                $activeCategory = 'general';
            }
            $dataList = Informasi::where('kategori', $activeCategory)
                ->with('author') 
                ->latest()
                ->paginate(10)
                ->appends(['kategori' => $activeCategory]);
        }
        
        // Hitung total untuk setiap kategori
        $totalGeneral = Informasi::where('kategori', 'general')->count();
        $totalPrestasi = Prestasi::count(); // Hitungan dari tabel Prestasis
        $totalTrainer = Trainer::count(); // Hitungan dari tabel Trainers

        // Mengirim $dataList ke view dengan nama $dataInformasi
        return view('admin.informasi', compact(
            'dataList', 
            'activeCategory', 
            'totalGeneral', 
            'totalTrainer', 
            'totalPrestasi'
        ))
        ->with('dataInformasi', $dataList); // Mengganti $dataList menjadi $dataInformasi
    }
    
    // 📌 Menampilkan form tambah data (Hanya untuk Informasi General)
    public function create(Request $request)
    {
        $activeCategory = $request->get('kategori', 'general');
        
        // Periksa jika Trainer/Prestasi diakses lewat sini, redirect ke form yang benar
        if ($activeCategory === 'trainer') {
            return redirect()->route('admin.trainer.create');
        } elseif ($activeCategory === 'prestasi') {
            return redirect()->route('admin.prestasi.create');
        }


        if (!in_array($activeCategory, $this->validInformasiCategories)) {
            $activeCategory = 'general';
        }
        return view('admin.informasi.create', compact('activeCategory'));
    }

    // 📌 Menyimpan data baru (Hanya untuk Informasi General)
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:' . implode(',', $this->validInformasiCategories), // Hanya General
            'isi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'tanggal' => 'nullable|date',
        ]);

        $imageName = null;
        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/informasi'), $imageName);
        }

        Informasi::create([
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'isi' => $request->isi,
            'gambar' => $imageName,
            'tanggal' => $request->tanggal ?? now(),
            'author_id' => Auth::id(),
        ]);

        // FIX: Menggunakan admin.informasi untuk redirect setelah store
        return redirect()->route('admin.informasi', ['kategori' => $request->kategori])
            ->with('success', 'Data ' . ucfirst($request->kategori) . ' berhasil ditambahkan.');
    }

    // 📌 Menampilkan detail informasi
    public function show(Informasi $informasi)
    {
        // Pengalihan ke Controller yang benar berdasarkan kategori
        if ($informasi->kategori === 'trainer') {
            return redirect()->route('admin.trainer.show', $informasi->id);
        } elseif ($informasi->kategori === 'prestasi') {
             return redirect()->route('admin.prestasi.show', $informasi->id);
        }
        
        $informasi->load('author'); 
        return view('admin.informasi.show', compact('informasi'));
    }

    // 📌 Menampilkan form edit
    public function edit(Informasi $informasi)
    {
        $activeCategory = $informasi->kategori;
        // Pengalihan ke Controller yang benar
        if ($activeCategory === 'trainer') {
            return redirect()->route('admin.trainer.edit', $informasi->id);
        } elseif ($activeCategory === 'prestasi') {
            return redirect()->route('admin.prestasi.edit', $informasi->id);
        }

        return view('admin.informasi.edit', compact('informasi', 'activeCategory'));
    }

    // 📌 Memperbarui data
    public function update(Request $request, Informasi $informasi)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:' . implode(',', $this->validInformasiCategories), // Hanya General
            'isi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'tanggal' => 'nullable|date',
        ]);

        $data = $request->only(['judul', 'kategori', 'isi', 'tanggal']);
        $imageName = $informasi->gambar;

        if ($request->hasFile('gambar')) {
            // Hapus file lama jika ada
            if ($informasi->gambar && File::exists(public_path('uploads/informasi/' . $informasi->gambar))) {
                File::delete(public_path('uploads/informasi/' . $informasi->gambar));
            }
            // Upload file baru
            $image = $request->file('gambar');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/informasi'), $imageName);
        }
        
        $data['gambar'] = $imageName;
        $informasi->update($data);

        return redirect()->route('admin.informasi', ['kategori' => $request->kategori])
            ->with('success', 'Data ' . ucfirst($request->kategori) . ' berhasil diperbarui.');
    }

    // 📌 Menghapus data
    public function destroy(Informasi $informasi)
    {
        $category = $informasi->kategori;
        // Hapus file gambar jika ada
        if ($informasi->gambar && File::exists(public_path('uploads/informasi/' . $informasi->gambar))) {
            File::delete(public_path('uploads/informasi/' . $informasi->gambar));
        }
        $informasi->delete();

        return redirect()->route('admin.informasi', ['kategori' => $category])
            ->with('success', 'Data ' . ucfirst($category) . ' berhasil dihapus.');
    }
}
