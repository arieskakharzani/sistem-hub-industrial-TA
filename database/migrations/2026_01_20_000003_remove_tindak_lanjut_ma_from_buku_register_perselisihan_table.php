<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('buku_register_perselisihan', function (Blueprint $table) {
            $table->dropColumn('tindak_lanjut_ma');
        });
    }

    public function down()
    {
        Schema::table('buku_register_perselisihan', function (Blueprint $table) {
            $table->enum('tindak_lanjut_ma', ['ya', 'tidak'])->default('tidak');
        });
    }
};
