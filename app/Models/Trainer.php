<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    use HasFactory;

    // Kolom yang dapat diisi secara massal (mass assignable)
    protected $fillable = [
        'nama',
        'spesialisasi',
        'deskripsi',
        'foto',
    ];
}
