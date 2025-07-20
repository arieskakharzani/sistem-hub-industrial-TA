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
        
        return (new MailMessage)
            ->subject("Risalah {$jenis} Telah Ditandatangani")
            ->greeting("Yth. {$notifiable->name},")
            ->line("Risalah {$jenis} untuk kasus Anda telah ditandatangani oleh mediator.")
            ->line("Silahkan login ke sistem untuk melihat detail risalah tersebut.")
            ->action('Lihat Risalah', url("/risalah/{$this->risalah->risalah_id}"))
            ->line('Terima kasih atas perhatiannya.');
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