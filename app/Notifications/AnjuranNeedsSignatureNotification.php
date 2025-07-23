<?php

namespace App\Notifications;

use App\Models\Anjuran;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AnjuranNeedsSignatureNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $anjuran;

    public function __construct(Anjuran $anjuran)
    {
        $this->anjuran = $anjuran;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $actionUrl = url('/penyelesaian');
        return (new MailMessage)
            ->subject('Anjuran Memerlukan Tanda Tangan Anda')
            ->view('emails.tanda-tangan-dibutuhkan', [
                'user' => $notifiable,
                'documentTypeLabel' => 'Anjuran',
                'perihal' => $this->anjuran->dokumenHI->pengaduan->perihal ?? '-',
                'namaPekerja' => $this->anjuran->nama_pekerja ?? '-',
                'namaPengusaha' => $this->anjuran->nama_pengusaha ?? '-',
                'actionUrl' => $actionUrl,
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Anjuran Memerlukan Tanda Tangan',
            'message' => 'Terdapat dokumen Anjuran yang memerlukan tanda tangan Anda.',
            'action_url' => '/penyelesaian',
        ];
    }
}
