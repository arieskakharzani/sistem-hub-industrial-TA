<?php

namespace App\Notifications;

use App\Models\Jadwal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MediasiProceedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $jadwal;
    public $absentParty;
    public $reason;

    public function __construct(Jadwal $jadwal, string $absentParty, string $reason = '')
    {
        $this->jadwal = $jadwal;
        $this->absentParty = $absentParty;
        $this->reason = $reason;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $absentPartyLabel = $this->getAbsentPartyLabel();

        return (new MailMessage)
            ->subject('📋 Mediasi Akan Dilanjutkan - ' . $this->jadwal->nomor_jadwal)
            ->view('emails.mediasi-proceed', [
                'jadwal' => $this->jadwal,
                'absentPartyLabel' => $absentPartyLabel,
                'reason' => $this->reason,
                'notifiable' => $notifiable
            ]);
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'mediasi_proceed',
            'jadwal_id' => $this->jadwal->jadwal_id,
            'pengaduan_id' => $this->jadwal->pengaduan_id,
            'message' => "Mediasi {$this->jadwal->nomor_jadwal} akan dilanjutkan meskipun ada pihak yang tidak hadir.",
            'action_url' => route('jadwal.show', $this->jadwal->jadwal_id),
            'absent_party' => $this->absentParty,
            'reason' => $this->reason,
        ];
    }

    protected function getAbsentPartyLabel(): string
    {
        if ($this->absentParty === 'pelapor') {
            return 'Pelapor';
        } elseif ($this->absentParty === 'terlapor') {
            return 'Terlapor';
        } elseif ($this->absentParty === 'both') {
            return 'Pelapor dan Terlapor';
        }
        return 'Pihak yang tidak diketahui';
    }
}

