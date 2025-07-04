<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('kelas', function (Blueprint $table) {
        $table->dropUnique(['kode_kelas']); // ❌ hapus unique lama
        $table->unique(['kode_kelas', 'tahun_ajaran_id']); // ✅ tambah unique gabungan
    });
}

public function down()
{
    Schema::table('kelas', function (Blueprint $table) {
        $table->dropUnique(['kode_kelas', 'tahun_ajaran_id']);
        $table->unique('kode_kelas');
    });
}

};
