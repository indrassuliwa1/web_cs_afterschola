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
        Schema::create('kontrak', function (Blueprint $table) {
            $table->id();

            // Kolom Wajib
            $table->string('nama_kontrak', 100);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->integer('jumlah_peserta')->default(1);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');

            // Foreign Key ke Pendaftar (DIBUAT TANPA CONSTRAINT UNTUK MENGHINDARI ERROR URUTAN)
            $table->unsignedBigInteger('pendaftar_id');

            // Foreign Key ke Kelas (Relasi ini aman karena 'kelas' dibuat lebih dulu)
            $table->foreignId('kelas_id')
                  ->nullable()
                  ->constrained('kelas')
                  ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kontrak');
    }
};
