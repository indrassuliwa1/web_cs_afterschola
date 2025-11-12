<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesanKontak extends Model
{
    use HasFactory;

    // Tambahkan semua kolom yang Anda izinkan untuk diisi melalui Mass Assignment
    protected $fillable = [
        'contactName',
        'contactEmail',
        'contactComment',
        'is_read', // Ini juga perlu diisi jika Anda ingin menyertakan status awal
    ];
    
    protected $table = 'pesan_kontaks';

    // Opsional: Anda bisa menggunakan $guarded jika Anda ingin mengizinkan SEMUA kolom kecuali beberapa:
    // protected $guarded = []; // Mengizinkan semua kolom kecuali yang ada di array ini
}