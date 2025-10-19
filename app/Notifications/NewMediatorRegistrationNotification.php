<?php

namespace App\Notifications;

use App\Models\Mediator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMediatorRegistrationNotification extends Notification
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
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        try {
            $actionUrl = route('kepala-dinas.mediator.approval.index');

            return (new MailMessage)
                ->subject('Mediator Baru Mendaftar - Perlu Approval - SIPPPHI')
                ->greeting('Ada Mediator Baru yang Mendaftar!')
                ->line('Seorang mediator baru telah mendaftar ke sistem SIPPPHI dan memerlukan persetujuan Anda.')
                ->line('**Detail Mediator:**')
                ->line("**Nama:** {$this->mediator->nama_mediator}")
                ->line("**NIP:** {$this->mediator->nip}")
                ->line("**Email:** {$this->mediator->user->email}")
                ->line("**Tanggal Registrasi:** {$this->mediator->created_at->format('d F Y, H:i')}")
                ->line("**File SK:** {$this->mediator->sk_file_name}")
                ->line('**Langkah Selanjutnya:**')
                ->line('1. Login ke sistem sebagai Kepala Dinas')
                ->line('2. Akses menu "Approval Mediator"')
                ->line('3. Review dokumen SK yang diupload')
                ->line('4. Approve atau Reject registrasi')
                ->action('Review Registrasi', $actionUrl)
                ->line('**PENTING:** Pastikan untuk memverifikasi keaslian dokumen SK sebelum memberikan persetujuan.')
                ->line('Terima kasih atas perhatian Anda.');
        } catch (\Exception $e) {
            \Log::error('Error in NewMediatorRegistrationNotification toMail', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            // Fallback email
            return (new MailMessage)
                ->subject('Mediator Baru Mendaftar - Perlu Approval - SIPPPHI')
                ->greeting('Ada Mediator Baru yang Mendaftar!')
                ->line('Seorang mediator baru telah mendaftar ke sistem SIPPPHI.')
                ->line('Silakan login ke sistem untuk melihat detail lebih lanjut.')
                ->action('Login ke Sistem', route('login'));
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
            'email' => $this->mediator->user->email,
            'registered_at' => $this->mediator->created_at,
        ];
    }
}
