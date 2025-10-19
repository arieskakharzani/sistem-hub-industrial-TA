<?php

namespace App\Console\Commands;

use App\Models\Pengaduan;
use App\Models\Jadwal;
use App\Services\LaporanService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HandleMediasiTimeout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mediasi:handle-timeout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Handle mediasi timeout - auto-complete pengaduan for unresponsive pelapor after 3 sessions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting mediasi timeout handling...');

        try {
            // 1. Handle overdue mediasi sessions (auto-complete to selesai)
            $this->handleOverdueMediasiSessions();

            // 2. Handle pelapor unresponsiveness (auto-complete pengaduan)
            $this->handlePelaporUnresponsiveness();

            $this->info('✅ Mediasi timeout handling completed successfully');
        } catch (\Exception $e) {
            $this->error('❌ Error in mediasi timeout handling: ' . $e->getMessage());
            Log::error('Mediasi timeout handling failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Handle overdue mediasi sessions - auto-complete to selesai
     */
    private function handleOverdueMediasiSessions()
    {
        $this->info('📅 Handling overdue mediasi sessions...');

        $overdueJadwal = Jadwal::where('jenis_jadwal', 'mediasi')
            ->where('status_jadwal', 'dijadwalkan')
            ->where('tanggal', '<', now()->toDateString())
            ->get();

        $count = 0;
        foreach ($overdueJadwal as $jadwal) {
            try {
                DB::beginTransaction();

                // Update jadwal status to selesai
                $jadwal->update(['status_jadwal' => 'selesai']);

                Log::info('📋 Auto-completed overdue mediasi session', [
                    'jadwal_id' => $jadwal->jadwal_id,
                    'pengaduan_id' => $jadwal->pengaduan_id,
                    'tanggal' => $jadwal->tanggal,
                    'sidang_ke' => $jadwal->sidang_ke
                ]);

                DB::commit();
                $count++;
            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Failed to auto-complete overdue mediasi session', [
                    'jadwal_id' => $jadwal->jadwal_id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $this->info("✅ Auto-completed {$count} overdue mediasi sessions");
    }

    /**
     * Handle pelapor unresponsiveness - auto-complete pengaduan
     */
    private function handlePelaporUnresponsiveness()
    {
        $this->info('👤 Handling pelapor unresponsiveness...');

        // Get pengaduan that should be auto-completed due to pelapor unresponsiveness
        $pengaduanToComplete = Pengaduan::where('status', 'proses')
            ->whereHas('jadwal', function ($query) {
                $query->where('jenis_jadwal', 'mediasi')
                    ->where('status_jadwal', 'selesai');
            })
            ->get()
            ->filter(function ($pengaduan) {
                return $pengaduan->shouldAutoCompleteDueToPelaporUnresponsiveness();
            });

        $count = 0;
        foreach ($pengaduanToComplete as $pengaduan) {
            try {
                DB::beginTransaction();

                // Update pengaduan status to selesai
                $pengaduan->update(['status' => 'selesai']);

                // Create buku register otomatis
                $this->createBukuRegisterOtomatis($pengaduan, 'pelapor_tidak_responsif');

                // Generate laporan otomatis
                $laporanService = new LaporanService();
                $laporanService->generateLaporanOtomatis($pengaduan);

                Log::info('📋 Auto-completed pengaduan due to pelapor unresponsiveness', [
                    'pengaduan_id' => $pengaduan->pengaduan_id,
                    'nomor_pengaduan' => $pengaduan->nomor_pengaduan,
                    'completion_type' => 'pelapor_tidak_responsif'
                ]);

                DB::commit();
                $count++;
            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Failed to auto-complete pengaduan due to pelapor unresponsiveness', [
                    'pengaduan_id' => $pengaduan->pengaduan_id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $this->info("✅ Auto-completed {$count} pengaduan due to pelapor unresponsiveness");
    }

    /**
     * Create buku register otomatis
     */
    private function createBukuRegisterOtomatis(Pengaduan $pengaduan, string $completionType)
    {
        try {
            // Load necessary relationships
            $pengaduan->load(['pelapor', 'terlapor', 'mediator']);

            // Create buku register entry
            $bukuRegister = $pengaduan->bukuRegister()->create([
                'tanggal_register' => now()->toDateString(),
                'nomor_register' => $this->generateNomorRegister(),
                'status_penyelesaian' => $completionType,
                'keterangan' => $this->getCompletionKeterangan($completionType),
                'created_by' => $pengaduan->mediator_id,
            ]);

            Log::info('📋 Created buku register otomatis', [
                'pengaduan_id' => $pengaduan->pengaduan_id,
                'buku_register_id' => $bukuRegister->buku_register_id,
                'completion_type' => $completionType
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create buku register otomatis', [
                'pengaduan_id' => $pengaduan->pengaduan_id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Generate nomor register
     */
    private function generateNomorRegister()
    {
        $year = now()->year;
        $month = now()->format('m');
        $day = now()->format('d');

        // Get count of registers for today
        $count = DB::table('buku_register')
            ->whereDate('created_at', now()->toDateString())
            ->count() + 1;

        return "REG-{$year}{$month}{$day}-" . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get completion keterangan based on type
     */
    private function getCompletionKeterangan(string $completionType): string
    {
        return match ($completionType) {
            'pelapor_tidak_responsif' => 'Pengaduan diselesaikan karena pelapor tidak responsif dalam 3 sesi mediasi',
            'terlapor_tidak_responsif' => 'Pengaduan diselesaikan karena terlapor tidak responsif dalam 3 sesi mediasi',
            default => 'Pengaduan diselesaikan secara otomatis'
        };
    }
}

