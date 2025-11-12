<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prestasi extends Model
{
    use HasFactory;

    protected $table = 'prestasis';

    protected $fillable = [
        'judul',
        'deskripsi',
        'tanggal_dicapai',
        'bukti_foto',
        'dokumentasi', // Wajib ada di fillable
        'author_id',
    ];

    // ✅ FIX KRITIS: Melakukan Casting untuk menyimpan array sebagai JSON string
    protected $casts = [
        'dokumentasi' => 'array', 
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
