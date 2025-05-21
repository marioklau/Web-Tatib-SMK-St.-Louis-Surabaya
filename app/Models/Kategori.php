<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    // Tentukan nama tabel jika tidak sesuai dengan konvensi Laravel
    protected $table = 'kategori';  // 👈 Tambahkan baris ini

    protected $fillable = [
        'nama_kategori',
    ];

    public function jenis()
    {
        return $this->hasMany(Jenis::class, 'kategori_id');
    }
}
