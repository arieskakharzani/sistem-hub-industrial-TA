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
        Schema::create('detail_klarifikasi', function (Blueprint $table) {
            $table->uuid('detail_klarifikasi_id')->primary();
            $table->uuid('risalah_id');
            $table->foreign('risalah_id')->references('risalah_id')->on('risalah')->onDelete('cascade');
            $table->text('arahan_mediator')->nullable();
            $table->enum('kesimpulan_klarifikasi', ['bipartit_lagi', 'lanjut_ke_tahap_mediasi'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_klarifikasi');
    }
};
