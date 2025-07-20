<?php

namespace App\Notifications;

use App\Models\Anjuran;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AnjuranSignedByKepalaDinasNotification extends Notification implements ShouldQueue
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
            ->subject('Anjuran Telah Ditandatangani Kepala Dinas')
            ->greeting("Yth. {$notifiable->name},")
            ->line('Dokumen Anjuran telah ditandatangani oleh Kepala Dinas.')
            ->line('Silahkan login ke sistem untuk mempublikasikan dokumen kepada para pihak.')
            ->action('Lihat Dokumen', url("/anjuran/{$this->anjuran->anjuran_id}"))
            ->line('Terima kasih atas perhatiannya.');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Anjuran Telah Ditandatangani Kepala Dinas',
            'message' => 'Dokumen Anjuran telah ditandatangani oleh Kepala Dinas.',
            'action_url' => "/anjuran/{$this->anjuran->anjuran_id}",
        ];
    }
} 