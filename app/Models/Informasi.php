<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Informasi extends Model
{
    use HasFactory;

    protected $table = 'informasi';

    protected $fillable = [
        'kategori',
        'judul',
        'isi',
        'gambar', // <-- DITAMBAHKAN (Dari kolom 'gambar' di migrasi)
        'tanggal', // <-- DITAMBAHKAN (Dari kolom 'tanggal' di migrasi)
        'author_id',
    ];

    // 🔗 Relasi ke User (pembuat informasi)
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}