<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pendaftar extends Model
{
    use HasFactory;

    protected $table = 'pendaftar';

    // PASTIKAN SEMUA KOLOM YANG DIKIRIM OLEH Controller ADA DI SINI.
    protected $fillable = [
        'nama',
        'email',
        'no_hp',
        'alamat',
        'tipe', // <--- KRITIS: DITAMBAHKAN AGAR Pendaftar::create bisa menyimpan nilai tipe
        // Kelas_id, user_id, status tidak dikirim saat pembuatan pendaftar di Controller, jadi dihapus dari fillable.
    ];

    // 🔗 Relasi ke Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    // 🔗 Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🔗 Relasi ke Pembayaran
    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class);
    }

    // 🔗 Relasi ke Kontrak
    public function kontrak()
    {
        return $this->hasOne(Kontrak::class);
    }
}
