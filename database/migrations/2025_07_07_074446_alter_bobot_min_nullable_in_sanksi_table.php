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
        Schema::table('sanksi', function (Blueprint $table) {
            $table->unsignedInteger('bobot_min')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('sanksi', function (Blueprint $table) {
            $table->unsignedInteger('bobot_min')->nullable(false)->change();
        });
    }
};
