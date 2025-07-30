<?php

namespace App\Notifications;

use App\Models\Anjuran;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AnjuranRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Anjuran $anjuran, public string $reason) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Anjuran Ditolak - ' . $this->anjuran->nomor_anjuran)
            ->view('emails.anjuran-rejected', [
                'anjuran' => $this->anjuran,
                'reason' => $this->reason
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Anjuran Ditolak Kepala Dinas',
            'message' => 'Anjuran ' . $this->anjuran->nomor_anjuran . ' ditolak dengan alasan: ' . $this->reason,
            'type' => 'anjuran_rejected',
            'anjuran_id' => $this->anjuran->anjuran_id,
            'reason' => $this->reason
        ];
    }
}
