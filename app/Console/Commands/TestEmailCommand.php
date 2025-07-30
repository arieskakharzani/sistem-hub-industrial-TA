<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\DraftPerjanjianBersamaMail;
use App\Models\Pengaduan;
use App\Models\PerjanjianBersama;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email {pengaduan_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email draft perjanjian bersama';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pengaduanId = $this->argument('pengaduan_id');

        $pengaduan = Pengaduan::with([
            'pelapor.user',
            'terlapor',
            'mediator.user',
            'dokumenHI.perjanjianBersama'
        ])->find($pengaduanId);

        if (!$pengaduan) {
            $this->error('Pengaduan tidak ditemukan!');
            return 1;
        }

        $perjanjianBersama = $pengaduan->dokumenHI->first()?->perjanjianBersama->first();

        if (!$perjanjianBersama) {
            $this->error('Perjanjian Bersama tidak ditemukan!');
            return 1;
        }

        $this->info('Testing email untuk pengaduan: ' . $pengaduan->nomor_pengaduan);
        $this->info('Perjanjian Bersama ID: ' . $perjanjianBersama->perjanjian_bersama_id);

        // Test email ke pelapor
        if ($pengaduan->pelapor && $pengaduan->pelapor->user) {
            $this->info('Mengirim email ke pelapor: ' . $pengaduan->pelapor->user->email);

            try {
                Mail::to($pengaduan->pelapor->user->email)
                    ->send(new DraftPerjanjianBersamaMail($pengaduan, $perjanjianBersama, 'pelapor'));

                $this->info('✅ Email berhasil dikirim ke pelapor');
            } catch (\Exception $e) {
                $this->error('❌ Error mengirim email ke pelapor: ' . $e->getMessage());
            }
        } else {
            $this->warn('⚠️ Pelapor atau user pelapor tidak ditemukan');
        }

        // Test email ke terlapor
        if ($pengaduan->terlapor) {
            $this->info('Mengirim email ke terlapor: ' . $pengaduan->terlapor->email_terlapor);

            try {
                Mail::to($pengaduan->terlapor->email_terlapor)
                    ->send(new DraftPerjanjianBersamaMail($pengaduan, $perjanjianBersama, 'terlapor'));

                $this->info('✅ Email berhasil dikirim ke terlapor');
            } catch (\Exception $e) {
                $this->error('❌ Error mengirim email ke terlapor: ' . $e->getMessage());
            }
        } else {
            $this->warn('⚠️ Terlapor tidak ditemukan');
        }

        $this->info('Test email selesai!');
        return 0;
    }
}
