<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\Anjuran;
use App\Mail\FinalCaseDocumentsMail;
use Barryvdh\DomPDF\Facade\Pdf;

class TestEmailFinalDocuments extends Command
{
    protected $signature = 'test:email-final-documents {--email= : Email untuk testing}';
    protected $description = 'Test mengirim email dokumen final ke pelapor dan terlapor';

    public function handle()
    {
        $this->info('=== Test Email Dokumen Final ===');

        try {
            // Ambil anjuran terbaru
            $anjuran = Anjuran::with([
                'dokumenHI.pengaduan.pelapor.user',
                'dokumenHI.pengaduan.terlapor',
                'dokumenHI.laporanHasilMediasi'
            ])->latest()->first();

            if (!$anjuran) {
                $this->error('❌ Tidak ada anjuran ditemukan untuk testing');
                return 1;
            }

            $this->info("📋 Anjuran ID: {$anjuran->anjuran_id}");
            $this->info("📄 Nomor Anjuran: {$anjuran->nomor_anjuran}");
            $this->info("👤 Pelapor: {$anjuran->dokumenHI->pengaduan->pelapor->nama_pelapor}");
            $this->info("🏢 Terlapor: {$anjuran->dokumenHI->pengaduan->terlapor->nama_terlapor}");

            // Generate PDF anjuran
            $this->info('🔄 Generating PDF Anjuran...');
            $anjuranPdf = Pdf::loadView('dokumen.pdf.anjuran', compact('anjuran'));
            $anjuranPdfContent = $anjuranPdf->output();
            $this->info('✅ PDF Anjuran berhasil dibuat');

            // Ambil laporan hasil mediasi
            $laporanHasilMediasi = $anjuran->dokumenHI->laporanHasilMediasi()->latest()->first();
            $laporanPdfContent = null;

            if ($laporanHasilMediasi) {
                $this->info('🔄 Generating PDF Laporan Hasil Mediasi...');
                $laporanPdf = Pdf::loadView('laporan.pdf.laporan-hasil-mediasi', [
                    'laporanHasilMediasi' => $laporanHasilMediasi
                ]);
                $laporanPdfContent = $laporanPdf->output();
                $this->info('✅ PDF Laporan Hasil Mediasi berhasil dibuat');
            } else {
                $this->warn('⚠️ Laporan Hasil Mediasi tidak ditemukan');
            }

            // Test email
            $testEmail = $this->option('email');

            if ($testEmail) {
                // Test ke email yang ditentukan
                $this->info("📧 Mengirim test email ke: {$testEmail}");
                Mail::to($testEmail)
                    ->send(new FinalCaseDocumentsMail($anjuran, $laporanPdfContent, $anjuranPdfContent));
                $this->info('✅ Test email berhasil dikirim!');
            } else {
                // Test ke pelapor dan terlapor asli
                $pelaporEmail = $anjuran->dokumenHI->pengaduan->pelapor->user->email;
                $terlaporEmail = $anjuran->dokumenHI->pengaduan->terlapor->email;

                if ($pelaporEmail) {
                    $this->info("📧 Mengirim email ke Pelapor: {$pelaporEmail}");
                    Mail::to($pelaporEmail)
                        ->send(new FinalCaseDocumentsMail($anjuran, $laporanPdfContent, $anjuranPdfContent));
                    $this->info('✅ Email berhasil dikirim ke Pelapor');
                }

                if ($terlaporEmail) {
                    $this->info("📧 Mengirim email ke Terlapor: {$terlaporEmail}");
                    Mail::to($terlaporEmail)
                        ->send(new FinalCaseDocumentsMail($anjuran, $laporanPdfContent, $anjuranPdfContent));
                    $this->info('✅ Email berhasil dikirim ke Terlapor');
                }
            }

            $this->info("\n🎉 Test email dokumen final selesai!");
            $this->table(
                ['Item', 'Status'],
                [
                    ['Anjuran', $anjuran->nomor_anjuran],
                    ['Pelapor', $anjuran->dokumenHI->pengaduan->pelapor->nama_pelapor],
                    ['Terlapor', $anjuran->dokumenHI->pengaduan->terlapor->nama_terlapor],
                    ['Laporan Hasil Mediasi', $laporanHasilMediasi ? 'Ada' : 'Tidak ada'],
                ]
            );
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            $this->error("📍 File: " . $e->getFile());
            $this->error("📍 Line: " . $e->getLine());
            return 1;
        }

        return 0;
    }
}
