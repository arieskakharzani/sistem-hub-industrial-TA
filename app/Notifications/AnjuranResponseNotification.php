<?php

namespace App\Notifications;

use App\Models\Anjuran;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AnjuranResponseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $anjuran;
    public $userRole;
    public $response;

    /**
     * Create a new notification instance.
     */
    public function __construct(Anjuran $anjuran, string $userRole, string $response)
    {
        $this->anjuran = $anjuran;
        $this->userRole = $userRole;
        $this->response = $response;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $roleLabel = $this->userRole === 'pelapor' ? 'Pelapor' : 'Terlapor';
        $responseLabel = $this->response === 'setuju' ? 'Setuju' : 'Tidak Setuju';

        return (new MailMessage)
            ->subject('Respon Anjuran dari ' . $roleLabel)
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Anda menerima respon anjuran dari ' . $roleLabel . '.')
            ->line('Nomor Anjuran: ' . ($this->anjuran->nomor_anjuran ?? 'A-' . $this->anjuran->anjuran_id))
            ->line('Respon: ' . $responseLabel)
            ->action('Lihat Detail', route('dokumen.anjuran.show', $this->anjuran->anjuran_id))
            ->line('Terima kasih telah menggunakan sistem SIPPPHI.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $roleLabel = $this->userRole === 'pelapor' ? 'Pelapor' : 'Terlapor';
        $responseLabel = $this->response === 'setuju' ? 'Setuju' : 'Tidak Setuju';

        return [
            'title' => 'Respon Anjuran dari ' . $roleLabel,
            'message' => 'Anda menerima respon anjuran dari ' . $roleLabel . ' dengan jawaban: ' . $responseLabel,
            'type' => 'anjuran_response',
            'anjuran_id' => $this->anjuran->anjuran_id,
            'user_role' => $this->userRole,
            'response' => $this->response,
        ];
    }
}
