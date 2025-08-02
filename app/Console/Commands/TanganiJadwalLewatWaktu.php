<?php

namespace App\Console\Commands;

use App\Models\Jadwal;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TanganiJadwalLewatWaktu extends Command
{
    /**
     * Nama dan signature command console
     *
     * @var string
     */
    protected $signature = 'jadwal:tangani-lewat-waktu';

    /**
     * Deskripsi command console
     *
     * @var string
     */
    protected $description = 'Tangani jadwal yang sudah lewat waktu tanpa konfirmasi';

    /**
     * Jalankan command
     */
    public function handle()
    {
        $this->info('🔍 Mengecek jadwal yang lewat waktu...');

        // Ambil semua jadwal yang sudah lewat waktu dan belum dikonfirmasi
        $jadwalLewatWaktu = Jadwal::where('status_jadwal', 'dijadwalkan')
            ->where('tanggal', '<', now())
            ->where(function ($query) {
                $query->where('konfirmasi_pelapor', 'pending')
                    ->orWhere('konfirmasi_terlapor', 'pending');
            })
            ->get();

        $jumlahDiproses = 0;

        foreach ($jadwalLewatWaktu as $jadwal) {
            $this->info("Memproses jadwal ID: {$jadwal->jadwal_id}");

            // Tangani jadwal yang lewat waktu secara otomatis
            $jadwal->handleOverdueJadwal();

            $jumlahDiproses++;

            $this->info("✅ Jadwal {$jadwal->jadwal_id} telah dibatalkan otomatis");
        }

        $this->info("🎯 Berhasil memproses {$jumlahDiproses} jadwal lewat waktu");

        // Catat log ringkasan
        Log::info('Pengecekan jadwal lewat waktu selesai', [
            'jumlah_diproses' => $jumlahDiproses,
            'waktu' => now()
        ]);

        return 0;
    }
}
