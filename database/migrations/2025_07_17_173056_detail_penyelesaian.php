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
        Schema::create('detail_penyelesaian', function (Blueprint $table) {
            $table->uuid('detail_penyelesaian_id')->primary();
            $table->uuid('risalah_id');
            $table->foreign('risalah_id')->references('risalah_id')->on('risalah')->onDelete('cascade');
            $table->text('kesimpulan_penyelesaian')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_penyelesaian');
    }
};
