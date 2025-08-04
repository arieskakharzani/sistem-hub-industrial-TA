<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix negative waktu_penyelesaian_mediasi values
        $laporanHasilMediasi = DB::table('laporan_hasil_mediasi')->get();

        foreach ($laporanHasilMediasi as $laporan) {
            $waktuPenyelesaian = $laporan->waktu_penyelesaian_mediasi;

            // Check if the value contains negative number
            if (is_string($waktuPenyelesaian) && str_contains($waktuPenyelesaian, '-')) {
                // Extract the number and make it positive
                preg_match('/-?\d+(\.\d+)?/', $waktuPenyelesaian, $matches);
                if (isset($matches[0])) {
                    $number = abs((float) $matches[0]);
                    $newValue = $number . ' hari';

                    DB::table('laporan_hasil_mediasi')
                        ->where('laporan_id', $laporan->laporan_id)
                        ->update(['waktu_penyelesaian_mediasi' => $newValue]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback needed for this data fix
    }
};
