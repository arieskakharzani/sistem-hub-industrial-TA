<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pengaduan', function (Blueprint $table) {
            $table->string('nomor_pengaduan')->unique()->nullable()->after('pengaduan_id');
        });
        Schema::table('jadwal', function (Blueprint $table) {
            $table->string('nomor_jadwal')->unique()->nullable()->after('jadwal_id');
        });
    }

    public function down()
    {
        Schema::table('pengaduan', function (Blueprint $table) {
            $table->dropColumn('nomor_pengaduan');
        });
        Schema::table('jadwal', function (Blueprint $table) {
            $table->dropColumn('nomor_jadwal');
        });
    }
};
