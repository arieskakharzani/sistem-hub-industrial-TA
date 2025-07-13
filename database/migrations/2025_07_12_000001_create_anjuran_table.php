<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('anjuran', function (Blueprint $table) {
            $table->uuid('anjuran_id')->primary();
            $table->uuid('dokumen_hi_id');
            $table->text('keterangan_pekerja');
            $table->text('keterangan_pengusaha');
            $table->text('pertimbangan_hukum');
            $table->text('isi_anjuran');
            $table->enum('status_approval', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->timestamps();
            $table->foreign('dokumen_hi_id')->references('dokumen_hi_id')->on('dokumen_hubungan_industrial')->onDelete('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('anjuran');
    }
};
