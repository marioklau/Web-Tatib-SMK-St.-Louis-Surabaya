<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNamaSanksiToPelanggaranTable extends Migration
{
    public function up()
    {
        Schema::table('pelanggaran', function (Blueprint $table) {
            $table->string('nama_sanksi')->nullable()->after('sanksi_id'); // atau setelah kolom lain sesuai kebutuhan
        });
    }

    public function down()
    {
        Schema::table('pelanggaran', function (Blueprint $table) {
            $table->dropColumn('nama_sanksi');
        });
    }
}
