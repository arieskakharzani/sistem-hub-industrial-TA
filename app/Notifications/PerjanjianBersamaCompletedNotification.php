<?php

namespace App\Notifications;

use App\Models\PerjanjianBersama;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PerjanjianBersamaCompletedNotification extends Notification implements ShouldQueue
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
            ->subject('Perjanjian Bersama Telah Selesai')
            ->greeting("Yth. {$notifiable->name},")
            ->line('Perjanjian Bersama telah ditandatangani oleh semua pihak.')
            ->line('Silahkan login ke sistem untuk melihat dokumen final.')
            ->action('Lihat Dokumen', url("/perjanjian-bersama/{$this->perjanjianBersama->perjanjian_bersama_id}"))
            ->line('Terima kasih atas perhatiannya.');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Perjanjian Bersama Telah Selesai',
            'message' => 'Perjanjian Bersama telah ditandatangani oleh semua pihak.',
            'action_url' => "/perjanjian-bersama/{$this->perjanjianBersama->perjanjian_bersama_id}",
        ];
    }
} 