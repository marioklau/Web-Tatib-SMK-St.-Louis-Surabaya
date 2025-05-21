<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jenis extends Model
{
    // Tentukan nama tabel jika tidak sesuai dengan konvensi Laravel
    protected $table = 'jenis';
    

    protected $fillable = [
        'bentuk_pelanggaran',
        'kategori_id'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}
