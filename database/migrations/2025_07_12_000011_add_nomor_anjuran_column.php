<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('anjuran', function (Blueprint $table) {
            $table->string('nomor_anjuran')->nullable();
            $table->date('tanggal_anjuran')->nullable();
        });
    }

    public function down()
    {
        Schema::table('anjuran', function (Blueprint $table) {
            $table->dropColumn(['nomor_anjuran', 'tanggal_anjuran']);
        });
    }
}; 