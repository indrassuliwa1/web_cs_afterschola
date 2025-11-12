<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // PENTING: Nama tabel harus 'pendaftar' (singular)
        Schema::create('pendaftar', function (Blueprint $table) {
            $table->id();

            // Kolom Pendaftar
            $table->string('nama', 100);
            $table->string('email', 100)->unique();
            $table->string('no_hp', 15);
            $table->string('tipe', 20)->comment('Contoh: guru, orangtua, siswa'); // Nilai yang dicari validasi!
            $table->text('alamat');

            // Foreign Key ke Kontrak (Relasi One-to-Many terbalik)
            // Kolom ini TIDAK BOLEH memiliki constraint() di sini karena tabel 'kontrak' belum dibuat.
            $table->unsignedBigInteger('kontrak_id')->nullable(); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftar');
    }
};
