<?php

namespace App\Notifications;

use App\Models\Mediator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MediatorRejectedNotification extends Notification
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
        try {
            $mailMessage = (new MailMessage)
                ->subject('Registrasi Mediator Ditolak - SIPPPHI')
                ->greeting('Mohon Maaf')
                ->line('Registrasi mediator Anda telah ditolak oleh Kepala Dinas.')
                ->line('**Alasan Penolakan:**')
                ->line($this->mediator->rejection_reason ?? 'Tidak ada alasan yang diberikan.')
                ->line('**Langkah Selanjutnya:**')
                ->line('1. Perbaiki dokumen sesuai dengan alasan penolakan')
                ->line('2. Registrasi ulang dengan dokumen yang benar')
                ->line('3. Pastikan SK yang diupload adalah dokumen resmi yang ditandatangani oleh Menteri')
                ->action('Registrasi Ulang', route('mediator.register'))
                ->line('Jika Anda memerlukan bantuan lebih lanjut, silakan hubungi admin sistem.')
                ->line('Terima kasih atas pengertian Anda.');

            return $mailMessage;
        } catch (\Exception $e) {
            \Log::error('Error in MediatorRejectedNotification toMail', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            // Fallback email
            return (new MailMessage)
                ->subject('Registrasi Mediator Ditolak - SIPPPHI')
                ->greeting('Mohon Maaf')
                ->line('Registrasi mediator Anda telah ditolak.')
                ->line('Silakan registrasi ulang dengan dokumen yang benar.')
                ->action('Registrasi Ulang', route('mediator.register'));
        }
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
            'rejection_reason' => $this->mediator->rejection_reason,
            'rejection_date' => $this->mediator->rejection_date,
        ];
    }
}
