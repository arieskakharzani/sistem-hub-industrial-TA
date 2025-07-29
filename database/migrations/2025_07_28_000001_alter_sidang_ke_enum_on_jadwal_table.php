<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('jadwal', function (Blueprint $table) {
            // Ubah kolom sidang_ke menjadi enum
            $table->enum('sidang_ke', ['1', '2', '3'])->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('jadwal', function (Blueprint $table) {
            // Kembalikan ke string jika rollback
            $table->string('sidang_ke')->nullable()->change();
        });
    }
};
