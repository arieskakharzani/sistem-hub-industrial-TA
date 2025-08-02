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
        Schema::table('jadwal', function (Blueprint $table) {
            // Update enum jenis_jadwal untuk menambah ttd_perjanjian_bersama
            DB::statement("ALTER TABLE jadwal MODIFY COLUMN jenis_jadwal ENUM('klarifikasi', 'mediasi', 'ttd_perjanjian_bersama') NOT NULL");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal', function (Blueprint $table) {
            // Kembalikan ke enum sebelumnya
            DB::statement("ALTER TABLE jadwal MODIFY COLUMN jenis_jadwal ENUM('klarifikasi', 'mediasi') NOT NULL");
        });
    }
};
