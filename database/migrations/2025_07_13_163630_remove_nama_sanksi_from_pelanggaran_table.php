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
            $table->dropColumn('nama_sanksi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('pelanggaran', function (Blueprint $table) {
            $table->string('nama_sanksi')->nullable(); // Sesuaikan dengan tipe data asli jika berbeda
        });
    }
};