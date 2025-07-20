<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabel Risalah (untuk klarifikasi dan penyelesaian)
        Schema::table('risalah', function (Blueprint $table) {
            $table->boolean('ttd_mediator')->default(false);
            $table->timestamp('tanggal_ttd_mediator')->nullable();
            $table->string('signature_mediator')->nullable(); // Menyimpan path/data tanda tangan
        });

        // Tabel Perjanjian Bersama
        Schema::table('perjanjian_bersama', function (Blueprint $table) {
            $table->boolean('ttd_pengusaha')->default(false);
            $table->boolean('ttd_pekerja')->default(false);
            $table->boolean('ttd_mediator')->default(false);
            $table->timestamp('tanggal_ttd_pengusaha')->nullable();
            $table->timestamp('tanggal_ttd_pekerja')->nullable();
            $table->timestamp('tanggal_ttd_mediator')->nullable();
            $table->string('signature_pengusaha')->nullable();
            $table->string('signature_pekerja')->nullable();
            $table->string('signature_mediator')->nullable();
        });

        // Tabel Anjuran
        Schema::table('anjuran', function (Blueprint $table) {
            $table->boolean('ttd_mediator')->default(false);
            $table->boolean('ttd_kepala_dinas')->default(false);
            $table->timestamp('tanggal_ttd_mediator')->nullable();
            $table->timestamp('tanggal_ttd_kepala_dinas')->nullable();
            $table->string('signature_mediator')->nullable();
            $table->string('signature_kepala_dinas')->nullable();
        });
    }

    public function down()
    {
        Schema::table('risalah', function (Blueprint $table) {
            $table->dropColumn([
                'ttd_mediator',
                'tanggal_ttd_mediator',
                'signature_mediator'
            ]);
        });

        Schema::table('perjanjian_bersama', function (Blueprint $table) {
            $table->dropColumn([
                'ttd_pengusaha',
                'ttd_pekerja',
                'ttd_mediator',
                'tanggal_ttd_pengusaha',
                'tanggal_ttd_pekerja',
                'tanggal_ttd_mediator',
                'signature_pengusaha',
                'signature_pekerja',
                'signature_mediator'
            ]);
        });

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
}; 