<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jadwal_mediasi', function (Blueprint $table) {
            $table->id('jadwal_id');
            $table->foreignId('pengaduan_id')->constrained('pengaduans', 'pengaduan_id')->onDelete('cascade');
            $table->foreignId('mediator_id')->constrained('mediator', 'mediator_id')->onDelete('cascade');
            $table->date('tanggal_mediasi');
            $table->time('waktu_mediasi');
            $table->string('tempat_mediasi');
            $table->enum('status_jadwal', ['dijadwalkan', 'berlangsung', 'selesai', 'ditunda', 'dibatalkan'])
                ->default('dijadwalkan');
            $table->text('catatan_jadwal')->nullable();
            $table->text('hasil_mediasi')->nullable();
            $table->timestamps();

            // Indexes untuk optimasi query
            $table->index(['mediator_id', 'tanggal_mediasi']);
            $table->index(['pengaduan_id', 'status_jadwal']);
            $table->index('status_jadwal');
            $table->index('tanggal_mediasi');
        });
    }

    public function down()
    {
        Schema::dropIfExists('jadwal_mediasi');
    }
};
