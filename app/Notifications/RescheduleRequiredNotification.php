<?php

namespace App\Notifications;

use App\Models\JadwalMediasi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RescheduleRequiredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $jadwalMediasi;
    protected $absentParty;
    protected $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct(JadwalMediasi $jadwalMediasi, string $absentParty, string $reason = '')
    {
        $this->jadwalMediasi = $jadwalMediasi;
        $this->absentParty = $absentParty;
        $this->reason = $reason;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $pengaduan = $this->jadwalMediasi->pengaduan;
        $absentPartyText = $this->getAbsentPartyText();

        $subject = "🚨 URGENT: Penjadwalan Ulang Diperlukan - Mediasi #{$this->jadwalMediasi->jadwal_id}";

        return (new MailMessage)
            ->subject($subject)
            ->priority(1) // High priority
            ->view('emails.reschedule-required', [
                'jadwal' => $this->jadwalMediasi,
                'pengaduan' => $pengaduan,
                'absentParty' => $this->absentParty,
                'absentPartyText' => $absentPartyText,
                'reason' => $this->reason,
                'mediator' => $notifiable
            ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        $pengaduan = $this->jadwalMediasi->pengaduan;
        $absentPartyText = $this->getAbsentPartyText();

        return [
            'type' => 'reschedule_required',
            'title' => '🚨 Penjadwalan Ulang Diperlukan',
            'message' => "{$absentPartyText} tidak dapat hadir pada mediasi '{$pengaduan->perihal}' tanggal {$this->jadwalMediasi->tanggal_mediasi->format('d F Y')}. Penjadwalan ulang segera diperlukan.",
            'icon' => '⚠️',
            'color' => 'red',
            'priority' => 'high',
            'action_url' => route('jadwal.edit', $this->jadwalMediasi->jadwal_id),
            'action_text' => 'Jadwalkan Ulang Sekarang',

            // Data payload
            'jadwal_id' => $this->jadwalMediasi->jadwal_id,
            'pengaduan_id' => $this->jadwalMediasi->pengaduan_id,
            'absent_party' => $this->absentParty,
            'reason' => $this->reason,
            'original_date' => $this->jadwalMediasi->tanggal_mediasi->format('Y-m-d'),
            'original_time' => $this->jadwalMediasi->waktu_mediasi->format('H:i'),
            'status_jadwal' => $this->jadwalMediasi->status_jadwal,
            'requires_immediate_action' => true,

            // Confirmation details
            'konfirmasi_pelapor' => $this->jadwalMediasi->konfirmasi_pelapor,
            'konfirmasi_terlapor' => $this->jadwalMediasi->konfirmasi_terlapor,
            'catatan_pelapor' => $this->jadwalMediasi->catatan_konfirmasi_pelapor,
            'catatan_terlapor' => $this->jadwalMediasi->catatan_konfirmasi_terlapor,

            // Pengaduan info
            'pengaduan_perihal' => $pengaduan->perihal ?? '',
            'pelapor_nama' => $pengaduan->pelapor->nama_pelapor ?? '',
            'terlapor_nama' => $pengaduan->terlapor->nama_terlapor ?? '',
        ];
    }

    /**
     * Get human-readable absent party text
     */
    private function getAbsentPartyText(): string
    {
        return match ($this->absentParty) {
            'pelapor' => 'Pelapor',
            'terlapor' => 'Terlapor',
            'both' => 'Kedua belah pihak',
            default => 'Salah satu pihak'
        };
    }
}
