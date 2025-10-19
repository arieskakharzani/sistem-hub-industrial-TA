<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Drop foreign key constraints yang mereferensikan pengaduans
        Schema::table('jadwal', function (Blueprint $table) {
            $table->dropForeign(['pengaduan_id']);
        });

        Schema::table('dokumen_hubungan_industrial', function (Blueprint $table) {
            $table->dropForeign(['pengaduan_id']);
        });

        Schema::table('laporan_pengadilan_hi', function (Blueprint $table) {
            $table->dropForeign(['pengaduan_id']);
        });

        // Step 2: Rename tabel pengaduans menjadi pengaduan
        Schema::rename('pengaduans', 'pengaduan');

        // Step 3: Recreate foreign key constraints dengan nama tabel baru
        Schema::table('jadwal', function (Blueprint $table) {
            $table->foreign('pengaduan_id')->references('pengaduan_id')->on('pengaduan')->onDelete('cascade');
        });

        Schema::table('dokumen_hubungan_industrial', function (Blueprint $table) {
            $table->foreign('pengaduan_id')->references('pengaduan_id')->on('pengaduan');
        });

        Schema::table('laporan_pengadilan_hi', function (Blueprint $table) {
            $table->foreign('pengaduan_id')->references('pengaduan_id')->on('pengaduan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Drop foreign key constraints
        Schema::table('jadwal', function (Blueprint $table) {
            $table->dropForeign(['pengaduan_id']);
        });

        Schema::table('dokumen_hubungan_industrial', function (Blueprint $table) {
            $table->dropForeign(['pengaduan_id']);
        });

        Schema::table('laporan_pengadilan_hi', function (Blueprint $table) {
            $table->dropForeign(['pengaduan_id']);
        });

        // Step 2: Rename tabel kembali ke pengaduans
        Schema::rename('pengaduan', 'pengaduans');

        // Step 3: Recreate foreign key constraints
        Schema::table('jadwal', function (Blueprint $table) {
            $table->foreign('pengaduan_id')->references('pengaduan_id')->on('pengaduans')->onDelete('cascade');
        });

        Schema::table('dokumen_hubungan_industrial', function (Blueprint $table) {
            $table->foreign('pengaduan_id')->references('pengaduan_id')->on('pengaduans');
        });

        Schema::table('laporan_pengadilan_hi', function (Blueprint $table) {
            $table->foreign('pengaduan_id')->references('pengaduan_id')->on('pengaduans')->onDelete('cascade');
        });
    }
};
