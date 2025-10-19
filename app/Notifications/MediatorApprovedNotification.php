<?php

namespace App\Notifications;

use App\Models\Mediator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MediatorApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $mediator;

    /**
     * Create a new notification instance.
     */
    public function __construct(Mediator $mediator)
    {
        $this->mediator = $mediator;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $password = $this->generateTemporaryPassword();

        // Update user with temporary password
        $this->mediator->user->update([
            'password' => bcrypt($password)
        ]);

        return (new MailMessage)
            ->subject('Akun Mediator Anda Telah Disetujui - SIPPPHI')
            ->greeting('Selamat!')
            ->line('Registrasi mediator Anda telah disetujui oleh Kepala Dinas.')
            ->line('Berikut adalah kredensial login Anda:')
            ->line("**Email:** {$this->mediator->user->email}")
            ->line("**Password:** {$password}")
            ->line('**PENTING:** Silakan ganti password Anda setelah login pertama kali.')
            ->action('Login ke Sistem', route('login'))
            ->line('Terima kasih telah bergabung dengan sistem SIPPPHI.')
            ->line('Jika Anda mengalami masalah, silakan hubungi admin sistem.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'mediator_id' => $this->mediator->mediator_id,
            'nama_mediator' => $this->mediator->nama_mediator,
            'nip' => $this->mediator->nip,
            'approved_at' => $this->mediator->approved_at,
        ];
    }

    /**
     * Generate temporary password
     */
    private function generateTemporaryPassword(): string
    {
        return 'Mediator' . rand(1000, 9999);
    }
}
