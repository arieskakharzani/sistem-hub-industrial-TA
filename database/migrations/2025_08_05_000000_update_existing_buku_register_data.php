<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Update penyelesaian bipartit untuk semua record yang sudah ada
        // Karena semua kasus yang masuk ke dinas sudah melalui penyelesaian bipartit
        DB::table('buku_register_perselisihan')
            ->where('penyelesaian_bipartit', 'tidak')
            ->update(['penyelesaian_bipartit' => 'ya']);

        // Update penyelesaian klarifikasi untuk record yang memiliki risalah klarifikasi
        $bukuRegisters = DB::table('buku_register_perselisihan as br')
            ->join('dokumen_hubungan_industrial as dhi', 'br.dokumen_hi_id', '=', 'dhi.dokumen_hi_id')
            ->join('risalah as r', 'dhi.dokumen_hi_id', '=', 'r.dokumen_hi_id')
            ->where('r.jenis_risalah', 'klarifikasi')
            ->where('br.penyelesaian_klarifikasi', 'tidak')
            ->select('br.buku_register_perselisihan_id')
            ->get();

        foreach ($bukuRegisters as $record) {
            DB::table('buku_register_perselisihan')
                ->where('buku_register_perselisihan_id', $record->buku_register_perselisihan_id)
                ->update(['penyelesaian_klarifikasi' => 'ya']);
        }

        // Update penyelesaian risalah untuk record yang memiliki risalah penyelesaian
        $bukuRegistersWithPenyelesaian = DB::table('buku_register_perselisihan as br')
            ->join('dokumen_hubungan_industrial as dhi', 'br.dokumen_hi_id', '=', 'dhi.dokumen_hi_id')
            ->join('risalah as r', 'dhi.dokumen_hi_id', '=', 'r.dokumen_hi_id')
            ->where('r.jenis_risalah', 'penyelesaian')
            ->where('br.penyelesaian_risalah', 'tidak')
            ->select('br.buku_register_perselisihan_id')
            ->get();

        foreach ($bukuRegistersWithPenyelesaian as $record) {
            DB::table('buku_register_perselisihan')
                ->where('buku_register_perselisihan_id', $record->buku_register_perselisihan_id)
                ->update(['penyelesaian_risalah' => 'ya']);
        }
    }

    public function down()
    {
        // Tidak perlu rollback karena ini adalah koreksi data
    }
};
