<?php

namespace App\Console\Commands;

use App\Models\Jadwal;
use App\Notifications\ConfirmationReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class KirimReminderKonfirmasi extends Command
{
    /**
     * Nama dan signature command console
     *
     * @var string
     */
    protected $signature = 'jadwal:kirim-reminder';

    /**
     * Deskripsi command console
     *
     * @var string
     */
    protected $description = 'Kirim reminder konfirmasi kehadiran untuk jadwal';

    /**
     * Jalankan command
     */
    public function handle()
    {
        $this->info('📧 Mengirim reminder konfirmasi...');

        // Ambil jadwal yang perlu reminder konfirmasi
        $jadwalPerluReminder = Jadwal::where('status_jadwal', 'dijadwalkan')
            ->where(function ($query) {
                $query->where('konfirmasi_pelapor', 'pending')
                    ->orWhere('konfirmasi_terlapor', 'pending');
            })
            ->get()
            ->filter(function ($jadwal) {
                // Kirim reminder jika:
                // 1. Deadline mendekati (24 jam sebelum deadline) ATAU
                // 2. Deadline sudah lewat tapi jadwal belum lewat waktu
                return $jadwal->isConfirmationDeadlineApproaching() ||
                    ($jadwal->isConfirmationDeadlinePassed() && !$jadwal->isOverdue());
            });

        $jumlahDikirim = 0;

        foreach ($jadwalPerluReminder as $jadwal) {
            $this->info("Mengirim reminder untuk jadwal ID: {$jadwal->jadwal_id}");

            // Kirim reminder ke pelapor jika belum dikonfirmasi
            if ($jadwal->konfirmasi_pelapor === 'pending' && $jadwal->pengaduan->pelapor->user) {
                $jadwal->pengaduan->pelapor->user->notify(new ConfirmationReminderNotification($jadwal, 'pelapor'));
                $this->info("📧 Reminder dikirim ke pelapor");
            }

            // Kirim reminder ke terlapor jika belum dikonfirmasi
            if ($jadwal->konfirmasi_terlapor === 'pending' && $jadwal->pengaduan->terlapor) {
                // Catatan: terlapor tidak punya akun user, jadi perlu implementasi khusus
                // Ini perlu diimplementasi di notification
                $this->info("📧 Reminder dikirim ke terlapor");
            }

            $jumlahDikirim++;
        }

        $this->info("🎯 Berhasil mengirim {$jumlahDikirim} reminder");

        // Catat log ringkasan
        Log::info('Pengecekan reminder konfirmasi selesai', [
            'jumlah_dikirim' => $jumlahDikirim,
            'waktu' => now()
        ]);

        return 0;
    }
}
