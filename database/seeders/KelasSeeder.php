<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        Kelas::create(['nama_kelas' => 'Matematika Dasar', 'deskripsi' => 'Kelas untuk memahami konsep dasar matematika.']);
        Kelas::create(['nama_kelas' => 'Bahasa Inggris', 'deskripsi' => 'Pelatihan grammar dan speaking.']);
        Kelas::create(['nama_kelas' => 'Sains Terapan', 'deskripsi' => 'Belajar sains melalui eksperimen sederhana.']);
    }
}
