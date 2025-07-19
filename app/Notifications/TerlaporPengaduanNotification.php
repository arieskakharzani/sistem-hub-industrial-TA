<?php

namespace App\Notifications;

use App\Models\Pengaduan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TerlaporPengaduanNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $pengaduan;

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
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Notifikasi Pengaduan Baru - SIPPPHI Kab. Bungo')
            ->view('emails.pengaduan-baru-terlapor', [
                'pengaduan' => $this->pengaduan
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'pengaduan_id' => $this->pengaduan->pengaduan_id,
            'title' => 'Pengaduan Baru',
            'message' => 'Anda telah dilaporkan oleh ' . $this->pengaduan->pelapor->nama_pelapor,
            'perihal' => $this->pengaduan->perihal,
            'tanggal' => $this->pengaduan->tanggal_laporan->format('d/m/Y'),
            'type' => 'pengaduan_baru',
            'action_url' => route('pengaduan.show-terlapor', $this->pengaduan)
        ];
    }
} 