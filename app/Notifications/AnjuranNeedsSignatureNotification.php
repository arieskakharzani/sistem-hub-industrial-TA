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
        return (new MailMessage)
            ->subject('Anjuran Memerlukan Tanda Tangan Anda')
            ->greeting("Yth. {$notifiable->name},")
            ->line('Terdapat dokumen Anjuran yang memerlukan tanda tangan Anda.')
            ->line('Silahkan login ke sistem untuk menandatangani dokumen tersebut.')
            ->action('Tanda Tangani Dokumen', url("/penyelesaian"))
            ->line('Terima kasih atas perhatiannya.');
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