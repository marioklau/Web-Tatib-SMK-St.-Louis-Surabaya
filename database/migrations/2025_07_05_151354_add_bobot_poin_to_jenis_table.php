<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('jenis', function (Blueprint $table) {
            $table->integer('bobot_poin')->default(1)->after('bentuk_pelanggaran');
        });
    }

    public function down()
    {
        Schema::table('jenis', function (Blueprint $table) {
            $table->dropColumn('bobot_poin');
        });
    }
};
