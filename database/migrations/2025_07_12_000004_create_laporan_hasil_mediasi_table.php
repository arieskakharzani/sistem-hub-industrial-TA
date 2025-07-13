<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('laporan_hasil_mediasi', function (Blueprint $table) {
            $table->uuid('laporan_id')->primary();
            $table->uuid('dokumen_hi_id');
            $table->date('tanggal_penerimaan_pengaduan');
            $table->string('nama_pekerja');
            $table->string('alamat_pekerja');
            $table->string('upah_terakhir');
            $table->string('masa_kerja');
            $table->string('nama_perusahaan');
            $table->string('alamat_perusahaan');
            $table->string('jenis_usaha');
            $table->string('waktu_penyelesaian_mediasi');
            $table->text('permasalahan');
            $table->text('pendapat_pekerja');
            $table->text('pendapat_pengusaha');
            $table->text('pendapat_saksi');
            $table->text('upaya_penyelesaian');
            $table->timestamps();
            $table->foreign('dokumen_hi_id')->references('dokumen_hi_id')->on('dokumen_hubungan_industrial')->onDelete('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('laporan_hasil_mediasi');
    }
};
