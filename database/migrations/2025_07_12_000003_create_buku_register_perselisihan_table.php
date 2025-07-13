<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('buku_register_perselisihan', function (Blueprint $table) {
            $table->uuid('buku_register_perselisihan_id')->primary();
            $table->uuid('dokumen_hi_id');
            $table->date('tanggal_pencatatan');
            $table->string('pihak_mencatat');
            $table->string('pihak_pekerja');
            $table->string('pihak_pengusaha');
            $table->enum('perselisihan_hak', ['ya', 'tidak'])->default('tidak');
            $table->enum('perselisihan_kepentingan', ['ya', 'tidak'])->default('tidak');
            $table->enum('perselisihan_phk', ['ya', 'tidak'])->default('tidak');
            $table->enum('perselisihan_sp_sb', ['ya', 'tidak'])->default('tidak');
            $table->enum('penyelesaian_bipartit', ['ya', 'tidak'])->default('tidak');
            $table->enum('penyelesaian_klarifikasi', ['ya', 'tidak'])->default('tidak');
            $table->enum('penyelesaian_mediasi', ['ya', 'tidak'])->default('tidak');
            $table->enum('penyelesaian_pb', ['ya', 'tidak'])->default('tidak');
            $table->enum('penyelesaian_anjuran', ['ya', 'tidak'])->default('tidak');
            $table->enum('penyelesaian_risalah', ['ya', 'tidak'])->default('tidak');
            $table->enum('tindak_lanjut_phi', ['ya', 'tidak'])->default('tidak');
            $table->enum('tindak_lanjut_ma', ['ya', 'tidak'])->default('tidak');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->foreign('dokumen_hi_id')->references('dokumen_hi_id')->on('dokumen_hubungan_industrial')->onDelete('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('buku_register_perselisihan');
    }
};
