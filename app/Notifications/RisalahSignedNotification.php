<?php

namespace App\Notifications;

use App\Models\Risalah;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RisalahSignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $risalah;

    public function __construct(Risalah $risalah)
    {
        $this->risalah = $risalah;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $jenis = $this->risalah->jenis === 'klarifikasi' ? 'Klarifikasi' : 'Penyelesaian';
        $actionUrl = url('/risalah/' . $this->risalah->risalah_id);
        $finalDocuments = [
            ['label' => 'Risalah ' . $jenis, 'url' => $actionUrl],
        ];
        return (new MailMessage)
            ->subject("Risalah {$jenis} Telah Ditandatangani")
            ->view('emails.dokumen-siap-final', [
                'mediator' => $notifiable,
                'documentTypeLabel' => 'Risalah ' . $jenis,
                'finalDocuments' => $finalDocuments,
                'actionUrl' => $actionUrl,
            ]);
    }

    public function toArray($notifiable)
    {
        $jenis = $this->risalah->jenis === 'klarifikasi' ? 'Klarifikasi' : 'Penyelesaian';

        return [
            'title' => "Risalah {$jenis} Telah Ditandatangani",
            'message' => "Risalah {$jenis} untuk kasus Anda telah ditandatangani oleh mediator.",
            'action_url' => "/risalah/{$this->risalah->risalah_id}",
        ];
    }
}
