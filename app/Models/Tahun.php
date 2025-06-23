<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tahun extends Model
{
    // Tentukan nama tabel jika tidak sesuai dengan konvensi Laravel
    protected $table = 'tahun_ajaran';  // 👈 Tambahkan baris ini

    protected $fillable = [
        'tahun_ajaran',
        'status'
    ];

   
}
