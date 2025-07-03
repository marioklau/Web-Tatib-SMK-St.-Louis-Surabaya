<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            // Tambahkan index unik gabungan antara nis dan tahun_ajaran_id
            $table->unique(['nis', 'tahun_ajaran_id'], 'unique_nis_per_tahun');
        });
    }

    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropUnique('unique_nis_per_tahun');
        });
    }
};
