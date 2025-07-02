<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 

class KeputusanTindakan extends Model
{
    use HasFactory;
    protected $table = 'keputusan_tindakan';

    protected $fillable = [
        'nama_keputusan',
    ];

    public function pelanggaran()
    {
        return $this->hasMany(Pelanggaran::class);
    }
}