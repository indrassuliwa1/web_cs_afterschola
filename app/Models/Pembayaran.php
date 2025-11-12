<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran'; 

    protected $fillable = [
        'kontrak_id', 
        'pendaftar_id',
        'jumlah_bayar',
        'status', 
        'tanggal_bayar',
        'bukti_pembayaran', // Sekarang ini akan berupa JSON string 
    ];

    // ✅ Tambahkan casting untuk mengonversi JSON string ke PHP Array/Object secara otomatis
    protected $casts = [
        'bukti_pembayaran' => 'array',
    ];

    /**
     * Relasi ke Kontrak
     */
    public function kontrak()
    {
        return $this->belongsTo(Kontrak::class, 'kontrak_id');
    }

    /**
     * Relasi ke Pendaftar
     */
    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class, 'pendaftar_id');
    }
}