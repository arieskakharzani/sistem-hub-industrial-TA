<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dokumen_hubungan_industrial', function (Blueprint $table) {
            $table->uuid('dokumen_hi_id')->primary();
            $table->uuid('pengaduan_id');
            $table->enum('jenis_dokumen', [
                'risalah_klarifikasi',
                'risalah_penyelesaian',
                'perjanjian_bersama',
                'anjuran',
                'buku_register_perselisihan',
                'laporan_hasil_mediasi',
            ]);
            $table->string('file_path')->nullable();
            $table->timestamp('tanggal_dokumen')->nullable();
            $table->timestamps();

            $table->foreign('pengaduan_id')->references('pengaduan_id')->on('pengaduan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dokumen_hubungan_industrial');
    }
};
