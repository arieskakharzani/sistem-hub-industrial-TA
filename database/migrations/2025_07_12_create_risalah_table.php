<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('risalah', function (Blueprint $table) {
            $table->uuid('risalah_id')->primary();
            $table->uuid('jadwal_id');
            $table->uuid('dokumen_hi_id')->nullable();
            $table->foreign('jadwal_id')->references('jadwal_id')->on('jadwal')->onDelete('cascade');
            $table->foreign('dokumen_hi_id')->references('dokumen_hi_id')->on('dokumen_hubungan_industrial')->onDelete('set null');
            $table->enum('jenis_risalah', ['klarifikasi', 'mediasi', 'penyelesaian']);
            $table->string('nama_perusahaan');
            $table->string('jenis_usaha');
            $table->string('alamat_perusahaan');
            $table->string('nama_pekerja');
            $table->string('alamat_pekerja');
            $table->date('tanggal_perundingan');
            $table->string('tempat_perundingan');
            $table->text('pokok_masalah')->nullable();
            // $table->text('arahan_mediator')->nullable();
            // $table->enum('kesimpulan_klarifikasi', ['bipartit_lagi', 'lanjut_ke_tahap_mediasi'])->nullable();
            $table->text('pendapat_pekerja')->nullable();
            $table->text('pendapat_pengusaha')->nullable();
            // $table->text('kesimpulan_penyelesaian')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('risalah');
    }
};
