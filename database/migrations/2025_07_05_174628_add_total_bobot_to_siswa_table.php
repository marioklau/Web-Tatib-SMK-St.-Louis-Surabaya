<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   // Di file migration
    public function up()
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->integer('total_bobot')->default(0)->after('tahun_ajaran_id');
        });
    }
};
