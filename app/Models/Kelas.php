<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'kode_kelas',
        'nama_kelas',
        'wali_kelas',
        'tahun_ajaran_id'
    ];

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }
}
