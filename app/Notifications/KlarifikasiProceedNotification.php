<?php

namespace App\Notifications;

use App\Models\Jadwal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class KlarifikasiProceedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $jadwal;
    protected $absentParty;
    protected $reason;

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
            ->subject('📋 Klarifikasi Akan Dilanjutkan - ' . $this->jadwal->nomor_jadwal)
            ->view('emails.klarifikasi-proceed', [
                'jadwal' => $this->jadwal,
                'absentPartyLabel' => $absentPartyLabel,
                'reason' => $this->reason,
                'notifiable' => $notifiable
            ]);
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'klarifikasi_proceed',
            'title' => 'Klarifikasi Akan Dilanjutkan',
            'message' => 'Jadwal klarifikasi ' . $this->jadwal->nomor_jadwal . ' akan dilanjutkan meskipun ada pihak yang tidak dapat hadir.',
            'jadwal_id' => $this->jadwal->jadwal_id,
            'absent_party' => $this->absentParty,
            'reason' => $this->reason,
            'data' => [
                'jadwal_id' => $this->jadwal->jadwal_id,
                'nomor_jadwal' => $this->jadwal->nomor_jadwal,
                'tanggal' => $this->jadwal->tanggal->format('Y-m-d'),
                'waktu' => $this->jadwal->waktu->format('H:i'),
                'tempat' => $this->jadwal->tempat,
                'absent_party' => $this->absentParty,
                'reason' => $this->reason
            ]
        ];
    }

    private function getAbsentPartyLabel(): string
    {
        return match ($this->absentParty) {
            'pelapor' => 'Pelapor',
            'terlapor' => 'Terlapor',
            'both' => 'Kedua Pihak',
            default => 'Tidak Diketahui'
        };
    }
}
