<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TerlaporAccountCreated extends Notification
{
    use Queueable;

    protected $credentials;
    protected $terlapor;

    public function __construct($credentials, $terlapor)
    {
        $this->credentials = $credentials;
        $this->terlapor = $terlapor;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Akun Terlapor - Sistem Pengaduan Hubungan Industrial')
            ->greeting('Halo ' . $this->terlapor->nama_terlapor)
            ->line('Akun Anda telah dibuat dalam Sistem Pengaduan dan Penyelesaian Hubungan Industrial.')
            ->line('Berikut adalah informasi login Anda:')
            ->line('**Email:** ' . $this->credentials['email'])
            ->line('**Password:** ' . $this->credentials['password'])
            ->line('**Nama Terlapor:** ' . $this->terlapor->nama_terlapor)
            ->action('Login ke Sistem', url('/login'))
            ->line('Harap segera login dan ubah password Anda untuk keamanan.')
            ->line('Jika Anda memiliki pertanyaan, silakan hubungi nomor terkait.')
            ->salutation('Terima kasih,<br>Dinas Tenaga Kerja dan Transmigrasi Kabupaten Bungo');
    }
}
