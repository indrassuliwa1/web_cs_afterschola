<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Informasi;
use App\Models\User;

class InformasiSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk tabel informasi.
     */
    public function run(): void
    {
        // Ambil admin pertama (pastikan sudah ada di UserSeeder)
        $admin = User::where('role', 'admin')->first();

        // Jika tidak ada, buat admin default
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin Default',
                'email' => 'admin@default.com',
                'password' => bcrypt('password123'),
                'role' => 'admin',
            ]);
        }

        // ✅ Gunakan kategori yang sesuai dengan enum di migration: ['general', 'trainer', 'prestasi']
        Informasi::create([
            'kategori' => 'general',
            'judul' => 'Pendaftaran Semester Baru',
            'isi' => 'Pendaftaran untuk semester baru telah dibuka. Silakan isi formulir di website.',
            'author_id' => $admin->id,
        ]);

        Informasi::create([
            'kategori' => 'trainer', // ganti dari 'pengumuman' ke 'trainer' agar sesuai enum
            'judul' => 'Libur Akhir Tahun',
            'isi' => 'Kegiatan belajar mengajar akan diliburkan mulai tanggal 20 Desember hingga 5 Januari.',
            'author_id' => $admin->id,
        ]);

        Informasi::create([
            'kategori' => 'prestasi',
            'judul' => 'Mahasiswa Berprestasi',
            'isi' => 'Selamat kepada mahasiswa yang telah meraih juara 1 lomba desain web tingkat nasional!',
            'author_id' => $admin->id,
        ]);
    }
}
