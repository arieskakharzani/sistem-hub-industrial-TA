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
        Schema::table('laporan_hasil_mediasi', function (Blueprint $table) {
            $table->string('jenis_usaha')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_hasil_mediasi', function (Blueprint $table) {
            $table->string('jenis_usaha')->nullable(false)->change();
        });
    }
};
