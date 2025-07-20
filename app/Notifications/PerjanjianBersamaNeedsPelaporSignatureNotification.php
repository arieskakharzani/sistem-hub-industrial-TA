<?php

namespace App\Notifications;

use App\Models\PerjanjianBersama;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PerjanjianBersamaNeedsPelaporSignatureNotification extends Notification implements ShouldQueue
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
            ->subject('Perjanjian Bersama Memerlukan Tanda Tangan Anda')
            ->greeting("Yth. {$notifiable->name},")
            ->line('Terdapat Perjanjian Bersama yang memerlukan tanda tangan Anda sebagai Pelapor.')
            ->line('Silahkan login ke sistem untuk menandatangani dokumen tersebut.')
            ->action('Tanda Tangani Dokumen', url("/penyelesaian"))
            ->line('Terima kasih atas perhatiannya.');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Perjanjian Bersama Memerlukan Tanda Tangan',
            'message' => 'Terdapat Perjanjian Bersama yang memerlukan tanda tangan Anda sebagai Pelapor.',
            'action_url' => '/penyelesaian',
        ];
    }
} 