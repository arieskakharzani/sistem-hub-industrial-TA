<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('detail_mediasi', function (Blueprint $table) {
            $table->uuid('detail_mediasi_id')->primary();
            $table->uuid('risalah_id');
            $table->foreign('risalah_id')->references('risalah_id')->on('risalah')->onDelete('cascade');
            $table->text('ringkasan_pembahasan')->nullable();
            $table->text('kesepakatan_sementara')->nullable();
            $table->text('ketidaksepakatan_sementara')->nullable();
            $table->text('catatan_khusus')->nullable();
            $table->text('rekomendasi_mediator')->nullable();
            $table->enum('status_sidang', ['selesai', 'lanjut_sidang_berikutnya'])->default('lanjut_sidang_berikutnya');
            $table->integer('sidang_ke'); // 1, 2, 3
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_mediasi');
    }
};
