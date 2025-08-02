<?php

namespace App\Notifications;

use App\Models\Anjuran;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AnjuranRejectedByPartiesNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Anjuran $anjuran, public string $rejectedBy) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $pengaduan = $this->anjuran->dokumenHI->pengaduan;
        $rejectedByText = $this->rejectedBy === 'pelapor' ? 'Pelapor' : 'Terlapor';

        return (new MailMessage)
            ->subject('Anjuran Ditolak - ' . $this->anjuran->nomor_anjuran)
            ->view('emails.anjuran-rejected-by-parties', [
                'anjuran' => $this->anjuran,
                'pengaduan' => $pengaduan,
                'mediator' => $notifiable,
                'rejectedBy' => $this->rejectedBy,
                'rejectedByText' => $rejectedByText
            ]);
    }

    public function toArray(object $notifiable): array
    {
        $pengaduan = $this->anjuran->dokumenHI->pengaduan;
        $rejectedByText = $this->rejectedBy === 'pelapor' ? 'Pelapor' : 'Terlapor';

        return [
            'title' => 'Anjuran Ditolak oleh ' . $rejectedByText,
            'message' => 'Anjuran untuk pengaduan #' . $pengaduan->nomor_pengaduan . ' telah ditolak oleh ' . $rejectedByText,
            'type' => 'anjuran_rejected_by_parties',
            'anjuran_id' => $this->anjuran->anjuran_id,
            'pengaduan_id' => $pengaduan->pengaduan_id,
            'rejected_by' => $this->rejectedBy,
            'rejected_by_text' => $rejectedByText
        ];
    }
}
