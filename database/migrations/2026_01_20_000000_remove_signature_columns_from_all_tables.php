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
        // Hapus kolom signature dari tabel risalah (hanya yang ada)
        Schema::table('risalah', function (Blueprint $table) {
            $table->dropColumn([
                'ttd_mediator',
                'tanggal_ttd_mediator',
                'signature_mediator'
            ]);
        });

        // Hapus kolom signature dari tabel perjanjian_bersama
        Schema::table('perjanjian_bersama', function (Blueprint $table) {
            $table->dropColumn([
                'ttd_pekerja',
                'ttd_pengusaha',
                'ttd_mediator',
                'tanggal_ttd_pekerja',
                'tanggal_ttd_pengusaha',
                'tanggal_ttd_mediator',
                'signature_pekerja',
                'signature_pengusaha',
                'signature_mediator'
            ]);
        });

        // Hapus kolom signature dari tabel anjuran
        Schema::table('anjuran', function (Blueprint $table) {
            $table->dropColumn([
                'ttd_mediator',
                'ttd_kepala_dinas',
                'tanggal_ttd_mediator',
                'tanggal_ttd_kepala_dinas',
                'signature_mediator',
                'signature_kepala_dinas'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tambahkan kembali kolom signature ke tabel risalah
        Schema::table('risalah', function (Blueprint $table) {
            $table->boolean('ttd_mediator')->default(false);
            $table->datetime('tanggal_ttd_mediator')->nullable();
            $table->string('signature_mediator')->nullable();
        });

        // Tambahkan kembali kolom signature ke tabel perjanjian_bersama
        Schema::table('perjanjian_bersama', function (Blueprint $table) {
            $table->boolean('ttd_pekerja')->default(false);
            $table->boolean('ttd_pengusaha')->default(false);
            $table->boolean('ttd_mediator')->default(false);
            $table->datetime('tanggal_ttd_pekerja')->nullable();
            $table->datetime('tanggal_ttd_pengusaha')->nullable();
            $table->datetime('tanggal_ttd_mediator')->nullable();
            $table->string('signature_pekerja')->nullable();
            $table->string('signature_pengusaha')->nullable();
            $table->string('signature_mediator')->nullable();
        });

        // Tambahkan kembali kolom signature ke tabel anjuran
        Schema::table('anjuran', function (Blueprint $table) {
            $table->boolean('ttd_mediator')->default(false);
            $table->boolean('ttd_kepala_dinas')->default(false);
            $table->datetime('tanggal_ttd_mediator')->nullable();
            $table->datetime('tanggal_ttd_kepala_dinas')->nullable();
            $table->string('signature_mediator')->nullable();
            $table->string('signature_kepala_dinas')->nullable();
        });
    }
};
