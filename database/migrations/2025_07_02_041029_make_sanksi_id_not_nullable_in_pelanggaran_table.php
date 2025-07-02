<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeSanksiIdNotNullableInPelanggaranTable extends Migration
{
    public function up()
    {
        Schema::table('pelanggaran', function (Blueprint $table) {
            $table->foreignId('sanksi_id')->nullable(false)->change(); // Mengubah menjadi NOT NULL
        });
    }

    public function down()
    {
        Schema::table('pelanggaran', function (Blueprint $table) {
            $table->foreignId('sanksi_id')->nullable()->change(); // Mengembalikan ke nullable jika rollback
        });
    }
}