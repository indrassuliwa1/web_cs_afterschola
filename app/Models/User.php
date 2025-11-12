<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_image',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // 🔗 Relasi ke tabel Informasi (User bisa punya banyak Informasi)
    public function informasi()
    {
        return $this->hasMany(Informasi::class, 'author_id');
    }

    // 🔗 Relasi ke Kontrak
    public function kontrak()
    {
        return $this->hasMany(Kontrak::class);
    }

    // 🔗 Jika suatu saat user juga punya data pendaftar
    public function pendaftar()
    {
        return $this->hasMany(Pendaftar::class);
    }
}
