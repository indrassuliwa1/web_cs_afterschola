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
        Schema::table('pesan_kontaks', function (Blueprint $table) {
            // Kolom untuk menyimpan hasil sentimen dari ML (positive, negative, neutral)
            // Kolom ini ditambahkan setelah kolom 'contactComment'
            $table->string('sentiment_result')->nullable()->after('contactComment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesan_kontaks', function (Blueprint $table) {
            $table->dropColumn('sentiment_result');
        });
    }
};