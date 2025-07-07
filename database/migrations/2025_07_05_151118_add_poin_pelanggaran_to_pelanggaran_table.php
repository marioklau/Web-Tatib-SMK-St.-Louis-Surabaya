<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('pelanggaran', function (Blueprint $table) {
            $table->integer('poin_pelanggaran')->default(0)->after('keputusan_tindakan_terpilih');
        });
    }

    public function down()
    {
        Schema::table('pelanggaran', function (Blueprint $table) {
            $table->dropColumn('poin_pelanggaran');
        });
    }
};
