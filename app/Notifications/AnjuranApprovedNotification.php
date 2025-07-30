<?php

namespace App\Notifications;

use App\Models\Anjuran;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AnjuranApprovedNotification extends Notification implements ShouldQueue
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
            ->subject('Anjuran Disetujui - ' . $this->anjuran->nomor_anjuran)
            ->view('emails.anjuran-approved', [
                'anjuran' => $this->anjuran
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Anjuran Disetujui Kepala Dinas',
            'message' => 'Anjuran ' . $this->anjuran->nomor_anjuran . ' telah disetujui dan siap dipublish ke para pihak',
            'type' => 'anjuran_approved',
            'anjuran_id' => $this->anjuran->anjuran_id
        ];
    }
}
