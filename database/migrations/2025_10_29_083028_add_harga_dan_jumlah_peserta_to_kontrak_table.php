<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kontrak', function (Blueprint $table) {
            if (!Schema::hasColumn('kontrak', 'harga')) {
                $table->decimal('harga', 15, 2)->nullable()->after('jumlah_peserta');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kontrak', function (Blueprint $table) {
            $table->dropColumn(['harga']);
        });
    }
};
