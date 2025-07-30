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

    public function __construct(public Anjuran $anjuran) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Anjuran Mediator - ' . $this->anjuran->nomor_anjuran)
            ->view('emails.anjuran-published', [
                'anjuran' => $this->anjuran,
                'user' => $notifiable,
                'deadline' => $this->anjuran->deadline_response_at->format('d/m/Y H:i')
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Anjuran Mediator Telah Diterbitkan',
            'message' => 'Anjuran untuk pengaduan #' . $this->anjuran->dokumenHI->pengaduan->nomor_pengaduan . ' telah diterbitkan. Deadline: ' . $this->anjuran->deadline_response_at->format('d/m/Y H:i'),
            'type' => 'anjuran_published',
            'anjuran_id' => $this->anjuran->anjuran_id,
            'deadline' => $this->anjuran->deadline_response_at->format('Y-m-d H:i:s'),
            'days_remaining' => $this->anjuran->getDaysUntilDeadline()
        ];
    }
}
