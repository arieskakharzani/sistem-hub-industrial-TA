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
            $table->string('nama_pengusaha');
            $table->string('jabatan_pengusaha');
            $table->string('perusahaan_pengusaha');
            $table->text('alamat_pengusaha');
            $table->string('nama_pekerja');
            $table->string('jabatan_pekerja');
            $table->string('perusahaan_pekerja');
            $table->text('alamat_pekerja');
            $table->text('keterangan_pekerja');
            $table->text('keterangan_pengusaha');
            $table->text('pertimbangan_hukum');
            $table->text('isi_anjuran');
            $table->uuid('kepala_dinas_id')->nullable();
            $table->timestamps();

            $table->foreign('dokumen_hi_id')->references('dokumen_hi_id')->on('dokumen_hubungan_industrial')->onDelete('cascade');
            $table->foreign('kepala_dinas_id')->references('kepala_dinas_id')->on('kepala_dinas')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('anjuran');
    }
};
