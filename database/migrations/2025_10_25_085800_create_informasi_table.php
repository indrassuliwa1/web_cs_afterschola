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
    Schema::create('informasi', function (Blueprint $table) {
        $table->id();
        $table->string('judul', 255);
        $table->enum('kategori', ['general', 'trainer', 'prestasi']);
        $table->text('isi');
        $table->string('gambar')->nullable();
        $table->date('tanggal')->nullable();
        $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informasi');
    }
};
