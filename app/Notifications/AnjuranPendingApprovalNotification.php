<?php

namespace App\Notifications;

use App\Models\Anjuran;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AnjuranPendingApprovalNotification extends Notification implements ShouldQueue
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
            ->subject('Anjuran Mediator Menunggu Approval - ' . $this->anjuran->nomor_anjuran)
            ->view('emails.anjuran-pending-approval', [
                'anjuran' => $this->anjuran,
                'kepalaDinas' => $notifiable->kepalaDinas
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Anjuran Mediator Menunggu Approval',
            'message' => 'Anjuran ' . $this->anjuran->nomor_anjuran . ' dari ' . $this->anjuran->mediator->nama_mediator . ' menunggu approval Anda',
            'type' => 'anjuran_pending_approval',
            'anjuran_id' => $this->anjuran->anjuran_id
        ];
    }
}
