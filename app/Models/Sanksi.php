<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sanksi extends Model
{
    protected $table = 'sanksi';

    protected $casts = [
        'nama_sanksi' => 'array',
        'keputusan_tindakan' => 'array',
    ];

    protected $fillable = [
        'kategori_id',
        'bobot_min',
        'bobot_max',
        'nama_sanksi',
        'pembina',
        'keputusan_tindakan'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}