<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sanksi extends Model
{
    protected $table = 'sanksi';

    protected $fillable = [
        'bobot_min',
        'bobot_max',
        'nama_sanksi',
        'pembina',
        'keputusan_tindakan',
        'kategori_id'
    ];

    protected $casts = [
        'nama_sanksi' => 'array',
        'keputusan_tindakan' => 'array', // Pastikan ini di-cast sebagai array
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}
