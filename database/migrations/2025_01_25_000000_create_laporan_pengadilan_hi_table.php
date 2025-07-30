<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laporan_pengadilan_hi', function (Blueprint $table) {
            $table->uuid('laporan_phi_id')->primary();
            $table->uuid('pengaduan_id');
            $table->foreign('pengaduan_id')->references('pengaduan_id')->on('pengaduans')->onDelete('cascade');
            $table->string('nomor_laporan')->unique();
            $table->date('tanggal_laporan');
            $table->string('nama_pelapor');
            $table->text('alamat_pelapor');
            $table->string('nama_terlapor');
            $table->text('alamat_terlapor');
            $table->text('perihal_perselisihan');
            $table->text('pokok_permasalahan');
            $table->text('upaya_penyelesaian');
            $table->text('hasil_mediasi');
            $table->text('alasan_tidak_sepakat');
            $table->text('rekomendasi_pengadilan');
            $table->enum('status_laporan', ['draft', 'submitted', 'sent', 'rejected'])->default('draft');
            $table->timestamp('tanggal_kirim')->nullable();
            $table->string('file_laporan')->nullable();
            $table->text('catatan_tambahan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_pengadilan_hi');
    }
};
