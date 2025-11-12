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
        Schema::table('pembayaran', function (Blueprint $table) {
            // Mengubah tipe kolom bukti_pembayaran menjadi LONGTEXT untuk menyimpan array JSON
            $table->longText('bukti_pembayaran')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            // Mengubah kembali ke tipe string jika rollback
            $table->string('bukti_pembayaran')->nullable()->change(); 
        });
    }
};
