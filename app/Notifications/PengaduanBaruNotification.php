<?php

namespace App\Notifications;

use App\Models\Pengaduan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class PengaduanBaruNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $pengaduan;

    /**
     * Create a new notification instance.
     */
    public function __construct(Pengaduan $pengaduan)
    {
        $this->pengaduan = $pengaduan;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail']; // Bisa tambah 'broadcast' untuk real-time
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $actionUrl = route('pengaduan.show', $this->pengaduan->pengaduan_id);

        return (new MailMessage)
            ->subject('🚨 Pengaduan Baru Masuk - ' . $this->pengaduan->perihal)
            ->view('emails.pengaduan-baru', [
                'pengaduan' => $this->pengaduan,
                'mediator' => $notifiable,
                'actionUrl' => $actionUrl
            ]);
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'pengaduan_id' => $this->pengaduan->pengaduan_id,
            'title' => 'Pengaduan Baru Masuk',
            'message' => 'Pengaduan baru dengan perihal "' . $this->pengaduan->perihal . '" dari ' . $this->pengaduan->pelapor->nama_pelapor,
            'action_url' => route('pengaduan.show', $this->pengaduan->pengaduan_id),
            'action_text' => 'Lihat Pengaduan',
            'type' => 'pengaduan_baru',
            'data' => [
                'pelapor_nama' => $this->pengaduan->pelapor->nama_pelapor,
                'perihal' => $this->pengaduan->perihal,
                'tanggal_laporan' => $this->pengaduan->tanggal_laporan->format('d/m/Y'),
                'status' => $this->pengaduan->status
            ]
        ];
    }

    /**
     * Get the array representation of the notification (untuk broadcast).
     */
    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
