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
            ->view('akun.terlapor-credentials', [
                'nama_terlapor' => $this->terlapor->nama_terlapor,
                'email' => $this->credentials['email'],
                'password' => $this->credentials['password'],
                'login_url' => url('/login'),
                'pengaduan_id' => null
            ]);
    }
}
