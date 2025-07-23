<?php

namespace App\Notifications;

use App\Models\PerjanjianBersama;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PerjanjianBersamaNeedsTerlaporSignatureNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $perjanjianBersama;

    public function __construct(PerjanjianBersama $perjanjianBersama)
    {
        $this->perjanjianBersama = $perjanjianBersama;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $actionUrl = url('/penyelesaian');
        return (new MailMessage)
            ->subject('Perjanjian Bersama Memerlukan Tanda Tangan Anda')
            ->view('emails.tanda-tangan-dibutuhkan', [
                'user' => $notifiable,
                'documentTypeLabel' => 'Perjanjian Bersama',
                'perihal' => $this->perjanjianBersama->dokumenHI->pengaduan->perihal ?? '-',
                'namaPekerja' => $this->perjanjianBersama->nama_pekerja ?? '-',
                'namaPengusaha' => $this->perjanjianBersama->nama_pengusaha ?? '-',
                'actionUrl' => $actionUrl,
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Perjanjian Bersama Memerlukan Tanda Tangan',
            'message' => 'Pelapor telah menandatangani Perjanjian Bersama. Sekarang dokumen memerlukan tanda tangan Anda sebagai Terlapor.',
            'action_url' => '/penyelesaian',
        ];
    }
}
