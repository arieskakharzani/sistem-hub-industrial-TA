<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('laporan_hasil_mediasi', function (Blueprint $table) {
            $table->dropColumn(['upah_terakhir', 'pendapat_saksi']);
        });
    }

    public function down()
    {
        Schema::table('laporan_hasil_mediasi', function (Blueprint $table) {
            $table->string('upah_terakhir');
            $table->text('pendapat_saksi');
        });
    }
};
