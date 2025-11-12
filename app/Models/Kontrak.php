<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kontrak extends Model
{
    use HasFactory;

    protected $table = 'kontrak';

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'pendaftar_id',
        'kelas_id',
        'nama_kontrak',
        'status',
        'jumlah_peserta',
        'harga',
        'tanggal_mulai',
        'tanggal_selesai',
        'data_peserta_file', // ✅ FIELD BARU DITAMBAHKAN
    ];

    /**
     * 🔗 Relasi ke Pendaftar (Belongs To - Penanggung Jawab)
     */
    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class, 'pendaftar_id');
    }

    /**
     * 🔗 Relasi ke Kelas
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    /**
     * 🔗 Relasi KRITIS ke Pembayaran (Has Many - Satu kontrak punya banyak pembayaran)
     * Ini memperbaiki error RelationNotFoundException.
     */
    public function pembayaran()
    {
        // Mencari pembayaran yang memiliki foreign key kontrak_id ini
        return $this->hasMany(Pembayaran::class, 'kontrak_id');
    }
}
