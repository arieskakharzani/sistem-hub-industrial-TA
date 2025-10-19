<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Jadwal;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class HandleKlarifikasiTimeout extends Command
{
    protected $signature = 'klarifikasi:handle-timeout';
    protected $description = 'Handles overdue klarifikasi schedules and auto-completes them when parties are still pending.';

    public function handle()
    {
        Log::info('Running klarifikasi:handle-timeout command.');

        // Get all klarifikasi schedules overdue where BOTH parties are still pending
        $overdueKlarifikasi = Jadwal::where('jenis_jadwal', 'klarifikasi')
            ->where('status_jadwal', 'dijadwalkan')
            ->where('tanggal', '<', now()->toDateString())
            ->where('konfirmasi_pelapor', 'pending')
            ->where('konfirmasi_terlapor', 'pending')
            ->get();

        foreach ($overdueKlarifikasi as $jadwal) {
            try {
                DB::beginTransaction();

                // Update status to 'selesai'
                $jadwal->update(['status_jadwal' => 'selesai']);

                Log::info("Klarifikasi jadwal {$jadwal->nomor_jadwal} auto-marked as 'selesai' due to BOTH parties pending after timeout.", [
                    'jadwal_id' => $jadwal->jadwal_id,
                    'pengaduan_id' => $jadwal->pengaduan_id,
                    'tanggal' => $jadwal->tanggal,
                    'konfirmasi_pelapor' => $jadwal->konfirmasi_pelapor,
                    'konfirmasi_terlapor' => $jadwal->konfirmasi_terlapor,
                    'status_updated_to' => 'selesai'
                ]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                Log::error("Failed to auto-mark klarifikasi jadwal {$jadwal->nomor_jadwal} as 'selesai': " . $e->getMessage(), [
                    'jadwal_id' => $jadwal->jadwal_id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        Log::info("Klarifikasi timeout handling completed. Processed {$overdueKlarifikasi->count()} schedules.");
    }
}
