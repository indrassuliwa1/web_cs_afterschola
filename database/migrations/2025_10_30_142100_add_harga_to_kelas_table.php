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
        // PENTING: Pastikan nama tabel ini benar-benar 'kelas' (singular)
        Schema::table('kelas', function (Blueprint $table) {
            // Menambahkan kolom 'harga' sebagai integer (untuk menyimpan nilai mata uang, misalnya dalam Rupiah).
            // Diletakkan 'after' deskripsi agar urutan kolom rapi.
            $table->integer('harga')->default(0)->after('deskripsi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            // Menghapus kolom 'harga' jika migrasi di-rollback
            $table->dropColumn('harga');
        });
    }
};
