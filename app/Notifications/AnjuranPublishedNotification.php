<?php

namespace App\Notifications;

use App\Models\Anjuran;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AnjuranPublishedNotification extends Notification implements ShouldQueue
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
        $actionUrl = url('/anjuran/' . $this->anjuran->anjuran_id);
        $finalDocuments = [
            ['label' => 'Anjuran', 'url' => $actionUrl],
        ];
        return (new MailMessage)
            ->subject('Anjuran Telah Diterbitkan')
            ->view('emails.dokumen-siap-final', [
                'mediator' => $notifiable,
                'documentTypeLabel' => 'Anjuran',
                'finalDocuments' => $finalDocuments,
                'actionUrl' => $actionUrl,
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Anjuran Telah Diterbitkan',
            'message' => 'Dokumen Anjuran untuk kasus Anda telah diterbitkan.',
            'action_url' => "/anjuran/{$this->anjuran->anjuran_id}",
        ];
    }
}
