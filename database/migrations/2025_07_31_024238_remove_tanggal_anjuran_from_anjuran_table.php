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
        Schema::table('anjuran', function (Blueprint $table) {
            $table->dropColumn('tanggal_anjuran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anjuran', function (Blueprint $table) {
            $table->date('tanggal_anjuran')->nullable()->after('nomor_anjuran');
        });
    }
};
