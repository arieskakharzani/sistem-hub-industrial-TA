<?php

namespace App\Notifications;

use App\Models\PerjanjianBersama;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PerjanjianBersamaNeedsMediatorSignatureNotification extends Notification implements ShouldQueue
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
        return (new MailMessage)
            ->subject('Perjanjian Bersama Siap Ditandatangani')
            ->greeting("Yth. {$notifiable->name},")
            ->line('Pelapor dan Terlapor telah menandatangani Perjanjian Bersama.')
            ->line('Silahkan login ke sistem untuk memberikan tanda tangan final sebagai Mediator.')
            ->action('Tanda Tangani Dokumen', url("/penyelesaian"))
            ->line('Terima kasih atas perhatiannya.');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Perjanjian Bersama Siap Ditandatangani',
            'message' => 'Pelapor dan Terlapor telah menandatangani Perjanjian Bersama. Dokumen siap untuk tanda tangan final Anda.',
            'action_url' => '/penyelesaian',
        ];
    }
} 