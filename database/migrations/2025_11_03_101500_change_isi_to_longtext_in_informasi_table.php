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
        Schema::table('informasi', function (Blueprint $table) {
            // FIX: Mengubah tipe kolom 'isi' menjadi LONGTEXT
            $table->longText('isi')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('informasi', function (Blueprint $table) {
            // Opsional: Mengubah kembali ke tipe TEXT/string jika rollback, tapi disarankan tetap longtext jika sudah terisi data besar
            $table->text('isi')->change(); 
        });
    }
};
