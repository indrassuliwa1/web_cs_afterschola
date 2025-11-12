<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\File; 

class ProfileController extends Controller
{
    /**
     * Menampilkan form edit profil admin.
     */
    public function edit()
    {
        // Mengirim data user yang sedang login
        $user = Auth::user(); 
        return view('admin.profile.edit', compact('user'));
    }

    /**
     * Memperbarui profil dan/atau password admin.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        // 1. Validasi Data Umum & Password (jika diisi)
        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            // ✅ KOREKSI NAMA KOLOM DI VALIDASI
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Nama input di form tetap 'profile_picture'
        ];

        // Hanya validasi password jika salah satu field password diisi
        if ($request->filled('current_password') || $request->filled('password') || $request->filled('password_confirmation')) {
            $validationRules['current_password'] = ['required', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('Password saat ini salah.');
                }
            }];
            $validationRules['password'] = ['required', 'confirmed', Password::min(8)];
        }

        $request->validate($validationRules);

        // 2. Update Nama dan Email
        $user->name = $request->name;
        $user->email = $request->email;
        
        // 3. Update Password
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // 4. Update Profile Picture
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $uploadPath = public_path('uploads/profile_pictures');

            // Hapus foto lama jika ada (Menggunakan nama kolom DB: profile_image)
            if ($user->profile_image && File::exists($uploadPath . '/' . $user->profile_image)) {
                File::delete($uploadPath . '/' . $user->profile_image);
            }

            // Simpan foto baru
            $fileName = time() . '_' . $file->getClientOriginalName();
            if (!File::isDirectory($uploadPath)) {
                File::makeDirectory($uploadPath, 0777, true, true);
            }
            $file->move($uploadPath, $fileName);
            
            // ✅ KOREKSI NAMA KOLOM SAAT PENYIMPANAN KE DB
            $user->profile_image = $fileName;
        }

        // Simpan semua perubahan (baris 78 di kode Anda yang error)
        $user->save(); 

        return redirect()->route('admin.profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }
}