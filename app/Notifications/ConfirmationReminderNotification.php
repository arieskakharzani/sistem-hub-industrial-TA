<?php

namespace App\Notifications;

use App\Models\Jadwal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConfirmationReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Jadwal $jadwal,
        public string $userRole
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $roleLabel = $this->userRole === 'pelapor' ? 'Pelapor' : 'Terlapor';
        $deadline = $this->jadwal->getConfirmationDeadline();

        return (new MailMessage)
            ->subject('⏰ Reminder: Konfirmasi Kehadiran Jadwal')
            ->view('emails.confirmation-reminder', [
                'jadwal' => $this->jadwal,
                'user' => $notifiable,
                'userRole' => $this->userRole,
                'roleLabel' => $roleLabel,
                'deadline' => $deadline
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'jadwal_id' => $this->jadwal->jadwal_id,
            'user_role' => $this->userRole,
            'deadline' => $this->jadwal->getConfirmationDeadline()->format('Y-m-d H:i:s'),
            'message' => 'Reminder: Konfirmasi kehadiran untuk jadwal ' . $this->jadwal->getJenisJadwalLabel() . ' harus dilakukan sebelum ' . $this->jadwal->getConfirmationDeadline()->format('d/m/Y H:i')
        ];
    }
}
