<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    // Tentukan nama tabel jika tidak sesuai dengan konvensi Laravel
    protected $table = 'siswa';
    

    protected $fillable = [
        'kelas',
        'nama_siswa',
        'jenis_kelamin',
        'nis',
        'kelas_id',
        'tahun_ajaran_id'
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function pelanggaran()
    {
        return $this->hasMany(Pelanggaran::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
