<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Risalah;
use App\Models\Pengaduan;

class RisalahKlarifikasiNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $risalah;
    protected $pengaduan;

    /**
     * Create a new notification instance.
     */
    public function __construct(Risalah $risalah, Pengaduan $pengaduan)
    {
        $this->risalah = $risalah;
        $this->pengaduan = $pengaduan;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $userRole = $notifiable->active_role;
        $userName = '';

        if ($userRole === 'pelapor' && $this->pengaduan->pelapor) {
            $userName = $this->pengaduan->pelapor->nama_pelapor;
        } elseif ($userRole === 'terlapor' && $this->pengaduan->terlapor) {
            $userName = $this->pengaduan->terlapor->nama_terlapor;
        }

        return (new MailMessage)
            ->subject('Risalah Klarifikasi - Kasus Selesai')
            ->greeting('Halo ' . $userName . ',')
            ->line('Kasus perselisihan hubungan industrial dengan nomor pengaduan **' . $this->pengaduan->nomor_pengaduan . '** telah selesai.')
            ->line('Berdasarkan hasil klarifikasi, kasus ini akan dilanjutkan dengan perundingan bipartit di luar ranah dinas.')
            ->line('Risalah klarifikasi telah dibuat dan dapat diunduh melalui sistem.')
            ->action('Lihat Detail Kasus', url('/pengaduan/' . $this->pengaduan->pengaduan_id))
            ->line('Terima kasih telah menggunakan layanan kami.')
            ->salutation('Salam, Tim SIPPPHI');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Risalah Klarifikasi - Kasus Selesai',
            'message' => 'Kasus dengan nomor pengaduan ' . $this->pengaduan->nomor_pengaduan . ' telah selesai. Risalah klarifikasi telah dibuat.',
            'type' => 'risalah_klarifikasi',
            'pengaduan_id' => $this->pengaduan->pengaduan_id,
            'risalah_id' => $this->risalah->risalah_id,
            'nomor_pengaduan' => $this->pengaduan->nomor_pengaduan,
        ];
    }
}
