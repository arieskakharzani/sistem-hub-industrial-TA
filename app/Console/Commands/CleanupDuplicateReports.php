<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LaporanHasilMediasi;
use App\Models\BukuRegisterPerselisihan;
use Illuminate\Support\Facades\DB;

class CleanupDuplicateReports extends Command
{
    protected $signature = 'app:cleanup-duplicate-reports {--dry-run}';
    protected $description = 'Cleanup duplicate laporan hasil mediasi and buku register perselisihan';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->info('🔍 DRY RUN MODE - No changes will be made');
        }

        $this->info('=== CLEANUP DUPLICATE REPORTS ===');

        // Cleanup Laporan Hasil Mediasi
        $this->cleanupLaporanHasilMediasi($dryRun);

        // Cleanup Buku Register Perselisihan
        $this->cleanupBukuRegisterPerselisihan($dryRun);

        $this->info('=== CLEANUP COMPLETED ===');
    }

    private function cleanupLaporanHasilMediasi($dryRun)
    {
        $this->info('📄 Cleaning up duplicate Laporan Hasil Mediasi...');

        // Find duplicates by dokumen_hi_id
        $duplicates = LaporanHasilMediasi::select('dokumen_hi_id')
            ->groupBy('dokumen_hi_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            $reports = LaporanHasilMediasi::where('dokumen_hi_id', $duplicate->dokumen_hi_id)
                ->orderBy('created_at', 'desc')
                ->get();

            $this->line("Found {$reports->count()} reports for dokumen_hi_id: {$duplicate->dokumen_hi_id}");

            // Keep the latest one, delete the rest
            $latest = $reports->first();
            $toDelete = $reports->skip(1);

            foreach ($toDelete as $report) {
                if ($dryRun) {
                    $this->line("  [DRY RUN] Would delete laporan_id: {$report->laporan_id}");
                } else {
                    $report->delete();
                    $this->line("  ✅ Deleted laporan_id: {$report->laporan_id}");
                }
            }
        }

        if ($duplicates->isEmpty()) {
            $this->info('✅ No duplicate Laporan Hasil Mediasi found');
        }
    }

    private function cleanupBukuRegisterPerselisihan($dryRun)
    {
        $this->info('📋 Cleaning up duplicate Buku Register Perselisihan...');

        // Find duplicates by dokumen_hi_id
        $duplicates = BukuRegisterPerselisihan::select('dokumen_hi_id')
            ->groupBy('dokumen_hi_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            $registers = BukuRegisterPerselisihan::where('dokumen_hi_id', $duplicate->dokumen_hi_id)
                ->orderBy('created_at', 'desc')
                ->get();

            $this->line("Found {$registers->count()} registers for dokumen_hi_id: {$duplicate->dokumen_hi_id}");

            // Keep the latest one, delete the rest
            $latest = $registers->first();
            $toDelete = $registers->skip(1);

            foreach ($toDelete as $register) {
                if ($dryRun) {
                    $this->line("  [DRY RUN] Would delete buku_register_id: {$register->buku_register_perselisihan_id}");
                } else {
                    $register->delete();
                    $this->line("  ✅ Deleted buku_register_id: {$register->buku_register_perselisihan_id}");
                }
            }
        }

        if ($duplicates->isEmpty()) {
            $this->info('✅ No duplicate Buku Register Perselisihan found');
        }
    }
} 