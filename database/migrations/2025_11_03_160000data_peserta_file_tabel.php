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
        Schema::table('kontrak', function (Blueprint $table) {
            // Kolom untuk menyimpan nama file data peserta (PDF/Excel/etc)
            $table->string('data_peserta_file')->nullable()->after('tanggal_selesai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kontrak', function (Blueprint $table) {
            $table->dropColumn('data_peserta_file');
        });
    }
};