<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Good practice if you use factories

class Pelanggaran extends Model
{
    // If you use model factories, include this trait
    use HasFactory;

    protected $table = 'pelanggaran';

    protected $fillable = [
        'siswa_id',
        'kategori_id',
        'jenis_id',
        'sanksi_id',
        'tahun_ajaran_id',
        'keterangan',
        'status',
        'keputusan_tindakan_terpilih',
        'total_bobot'
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