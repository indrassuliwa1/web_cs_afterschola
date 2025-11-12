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
        // Pastikan nama tabel ini benar
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            
            // Relasi (pastikan tabel 'kontrak' dan 'pendaftar' sudah dibuat!)
            $table->foreignId('kontrak_id')->constrained('kontrak')->onDelete('cascade');
            $table->foreignId('pendaftar_id')->nullable()->constrained('pendaftar')->onDelete('set null'); 

            $table->decimal('jumlah_bayar', 15, 2);
            $table->date('tanggal_bayar');
            
            // PERBAIKAN: Kolom bukti pembayaran dibuat lebih panjang (255)
            $table->string('bukti_pembayaran', 255)->nullable(); 

            // ENUM: Status hanya menerima 'pending' atau 'lunas'
            $table->enum('status', ['pending', 'lunas'])->default('pending');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
