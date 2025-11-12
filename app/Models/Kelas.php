<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'deskripsi',
        'harga',
        'foto_kelas', // ✅ DITAMBAHKAN
    ];

    public function kontrak()
    {
        return $this->hasMany(Kontrak::class, 'kelas_id');
    }
}
