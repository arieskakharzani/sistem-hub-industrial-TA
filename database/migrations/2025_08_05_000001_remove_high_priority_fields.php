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
        // Hapus foreign key constraint dan field dari tabel anjuran
        Schema::table('anjuran', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['kepala_dinas_id']);
            // Then drop the column
            $table->dropColumn(['kepala_dinas_id']);
        });

        // Update jenis_dokumen untuk menggabungkan risalah
        // Use raw SQL to avoid issues with enum changes
        DB::statement("ALTER TABLE dokumen_hubungan_industrial MODIFY COLUMN jenis_dokumen ENUM('risalah', 'perjanjian_bersama', 'anjuran', 'buku_register_perselisihan', 'laporan_hasil_mediasi')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan jenis_dokumen ke versi lama
        DB::statement("ALTER TABLE dokumen_hubungan_industrial MODIFY COLUMN jenis_dokumen ENUM('risalah_klarifikasi', 'risalah_penyelesaian', 'perjanjian_bersama', 'anjuran', 'buku_register_perselisihan', 'laporan_hasil_mediasi')");

        // Kembalikan field anjuran dan foreign key constraint
        Schema::table('anjuran', function (Blueprint $table) {
            $table->uuid('kepala_dinas_id')->nullable()->after('isi_anjuran');
            $table->foreign('kepala_dinas_id')->references('kepala_dinas_id')->on('kepala_dinas')->onDelete('cascade');
        });
    }
};
