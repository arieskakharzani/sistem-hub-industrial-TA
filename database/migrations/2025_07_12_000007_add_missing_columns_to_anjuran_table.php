<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('anjuran', function (Blueprint $table) {
            // Tambah kolom untuk data pengusaha
            if (!Schema::hasColumn('anjuran', 'nama_pengusaha')) {
                $table->string('nama_pengusaha')->nullable();
            }
            if (!Schema::hasColumn('anjuran', 'jabatan_pengusaha')) {
                $table->string('jabatan_pengusaha')->nullable();
            }
            if (!Schema::hasColumn('anjuran', 'perusahaan_pengusaha')) {
                $table->string('perusahaan_pengusaha')->nullable();
            }
            if (!Schema::hasColumn('anjuran', 'alamat_pengusaha')) {
                $table->text('alamat_pengusaha')->nullable();
            }

            // Tambah kolom untuk data pekerja
            if (!Schema::hasColumn('anjuran', 'nama_pekerja')) {
                $table->string('nama_pekerja')->nullable();
            }
            if (!Schema::hasColumn('anjuran', 'jabatan_pekerja')) {
                $table->string('jabatan_pekerja')->nullable();
            }
            if (!Schema::hasColumn('anjuran', 'alamat_pekerja')) {
                $table->text('alamat_pekerja')->nullable();
            }
            if (!Schema::hasColumn('anjuran', 'perusahaan_pekerja')) {
                $table->string('perusahaan_pekerja')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('anjuran', function (Blueprint $table) {
            $table->dropColumn([
                'nama_pengusaha',
                'jabatan_pengusaha',
                'perusahaan_pengusaha',
                'alamat_pengusaha',
                'nama_pekerja',
                'jabatan_pekerja',
                'alamat_pekerja',
                'perusahaan_pekerja'
            ]);
        });
    }
}; 