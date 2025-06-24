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
        // Pastikan tabel tidak ada sebelum dibuat
        Schema::dropIfExists('pengaduans');

        Schema::create('pengaduans', function (Blueprint $table) {
            $table->id('pengaduan_id');
            $table->foreignId('pelapor_id')->constrained('pelapor', 'pelapor_id')->onDelete('cascade');
            $table->foreignId('terlapor_id')->nullable()->constrained('terlapor', 'terlapor_id')->onDelete('set null');
            $table->date('tanggal_laporan');
            $table->enum('perihal', [
                'Perselisihan Hak',
                'Perselisihan Kepentingan',
                'Perselisihan PHK',
                'Perselisihan antar SP/SB'
            ]);
            $table->string('masa_kerja', 100);
            $table->string('nama_terlapor', 255);
            $table->string('email_terlapor', 100);
            $table->string('no_hp_terlapor', 15)->nullable();
            // $table->string('kontak_perusahaan', 100);
            $table->text('alamat_kantor_cabang')->nullable();
            $table->text('narasi_kasus');
            $table->text('catatan_tambahan')->nullable();
            $table->json('lampiran')->nullable(); // untuk menyimpan array file paths
            $table->enum('status', ['pending', 'proses', 'selesai'])->default('pending');

            // Fields untuk mediator
            $table->foreignId('mediator_id')->nullable()->constrained('mediator', 'mediator_id')->onDelete('set null');
            $table->text('catatan_mediator')->nullable();
            $table->timestamp('assigned_at')->nullable();

            $table->timestamps();

            // Indexes untuk performa
            $table->index(['pelapor_id', 'status']);
            $table->index('status');
            $table->index('perihal');
            $table->index('tanggal_laporan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaduans');
    }
};
