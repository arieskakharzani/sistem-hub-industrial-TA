<?php

namespace App\Notifications;

use App\Models\PerjanjianBersama;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PerjanjianBersamaCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $perjanjianBersama;

    public function __construct(PerjanjianBersama $perjanjianBersama)
    {
        $this->perjanjianBersama = $perjanjianBersama;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $actionUrl = url('/perjanjian-bersama/' . $this->perjanjianBersama->perjanjian_bersama_id);
        $finalDocuments = [
            ['label' => 'Perjanjian Bersama', 'url' => $actionUrl],
        ];
        return (new MailMessage)
            ->subject('Perjanjian Bersama Telah Selesai')
            ->view('emails.dokumen-siap-final', [
                'mediator' => $notifiable,
                'documentTypeLabel' => 'Perjanjian Bersama',
                'finalDocuments' => $finalDocuments,
                'actionUrl' => $actionUrl,
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Perjanjian Bersama Telah Selesai',
            'message' => 'Perjanjian Bersama telah ditandatangani oleh semua pihak.',
            'action_url' => "/perjanjian-bersama/{$this->perjanjianBersama->perjanjian_bersama_id}",
        ];
    }
}
