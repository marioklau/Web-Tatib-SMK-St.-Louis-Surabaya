<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggaran extends Model
{
    protected $table = 'pelanggaran';

    protected $fillable = [
        'siswa_id', 
        'kategori_id', 
        'jenis_id', 
        'sanksi_id',
        'status', 
        'keterangan'
    ];

    public function siswa() {
        return $this->belongsTo(Siswa::class);
    }

    public function kategori() {
        return $this->belongsTo(Kategori::class);
    }

    public function jenis() {
        return $this->belongsTo(Jenis::class);
    }

    public function sanksi() {
        return $this->belongsTo(Sanksi::class);
    }
}

