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
            $table->renameColumn('poin_pelanggaran', 'total_bobot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('pelanggaran', function (Blueprint $table) {
            $table->renameColumn('total_bobot', 'poin_pelanggaran');
        });
    }
};