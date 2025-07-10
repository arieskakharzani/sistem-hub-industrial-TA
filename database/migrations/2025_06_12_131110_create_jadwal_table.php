<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jadwal', function (Blueprint $table) {
            $table->uuid('jadwal_id')->primary();
            $table->uuid('pengaduan_id');
            $table->foreign('pengaduan_id')->references('pengaduan_id')->on('pengaduans')->onDelete('cascade');
            $table->uuid('mediator_id');
            $table->foreign('mediator_id')->references('mediator_id')->on('mediator')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('waktu');
            $table->string('tempat');
            $table->enum('jenis_jadwal', ['klarifikasi', 'mediasi'])->default('mediasi');
            $table->string('sidang_ke')->nullable();
            $table->enum('status_jadwal', ['dijadwalkan', 'berlangsung', 'selesai', 'ditunda', 'dibatalkan'])
                ->default('dijadwalkan');
            $table->text('catatan_jadwal')->nullable();
            $table->text('hasil')->nullable();
            $table->timestamps();

            // Field untuk konfirmasi kehadiran
            $table->enum('konfirmasi_pelapor', ['pending', 'hadir', 'tidak_hadir'])->default('pending');
            $table->enum('konfirmasi_terlapor', ['pending', 'hadir', 'tidak_hadir'])->default('pending');
            $table->timestamp('tanggal_konfirmasi_pelapor')->nullable();
            $table->timestamp('tanggal_konfirmasi_terlapor')->nullable();
            $table->text('catatan_konfirmasi_pelapor')->nullable();
            $table->text('catatan_konfirmasi_terlapor')->nullable();

            // Indexes untuk optimasi query
            $table->index(['mediator_id', 'tanggal']);
            $table->index(['pengaduan_id', 'status_jadwal']);
            $table->index('status_jadwal');
            $table->index('tanggal');
            $table->index(['konfirmasi_pelapor', 'konfirmasi_terlapor']);
            $table->index('tanggal_konfirmasi_pelapor');
            $table->index('tanggal_konfirmasi_terlapor');
        });
    }

    public function down()
    {
        Schema::dropIfExists('jadwal');
    }
};
