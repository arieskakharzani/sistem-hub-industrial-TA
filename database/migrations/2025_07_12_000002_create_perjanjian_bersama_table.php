<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('perjanjian_bersama', function (Blueprint $table) {
            $table->uuid('perjanjian_bersama_id')->primary();
            $table->uuid('dokumen_hi_id');
            $table->string('nama_pengusaha');
            $table->string('jabatan_pengusaha');
            $table->string('perusahaan_pengusaha');
            $table->string('alamat_pengusaha');
            $table->string('nama_pekerja');
            $table->string('jabatan_pekerja');
            $table->string('perusahaan_pekerja');
            $table->string('alamat_pekerja');
            $table->text('isi_kesepakatan');
            $table->date('tanggal_berlaku');
            $table->enum('status_approval', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->timestamps();
            $table->foreign('dokumen_hi_id')->references('dokumen_hi_id')->on('dokumen_hubungan_industrial')->onDelete('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('perjanjian_bersama');
    }
};
