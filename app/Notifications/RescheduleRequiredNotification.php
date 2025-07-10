<?php

namespace App\Notifications;

use App\Models\Jadwal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RescheduleRequiredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $jadwal;
    protected $absentParty;
    protected $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct(Jadwal $jadwal, string $absentParty, string $reason = '')
    {
        $this->jadwal = $jadwal;
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
        $pengaduan = $this->jadwal->pengaduan;
        $absentPartyText = $this->getAbsentPartyText();

        $subject = "🚨 URGENT: Penjadwalan Ulang Diperlukan - Mediasi #{$this->jadwal->jadwal_id}";

        return (new MailMessage)
            ->subject($subject)
            ->priority(1) // High priority
            ->view('emails.reschedule-required', [
                'jadwal' => $this->jadwal,
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
        $pengaduan = $this->jadwal->pengaduan;
        $absentPartyText = $this->getAbsentPartyText();

        return [
            'type' => 'reschedule_required',
            'title' => '🚨 Penjadwalan Ulang Diperlukan',
            'message' => "{$absentPartyText} tidak dapat hadir pada mediasi '{$pengaduan->perihal}' tanggal {$this->jadwal->tanggal->format('d F Y')}. Penjadwalan ulang segera diperlukan.",
            'icon' => '⚠️',
            'color' => 'red',
            'priority' => 'high',
            'action_url' => route('jadwal.edit', $this->jadwal->jadwal_id),
            'action_text' => 'Jadwalkan Ulang Sekarang',

            // Data payload
            'jadwal_id' => $this->jadwal->jadwal_id,
            'pengaduan_id' => $this->jadwal->pengaduan_id,
            'absent_party' => $this->absentParty,
            'reason' => $this->reason,
            'original_date' => $this->jadwal->tanggal->format('Y-m-d'),
            'original_time' => $this->jadwal->waktu->format('H:i'),
            'status_jadwal' => $this->jadwal->status_jadwal,
            'requires_immediate_action' => true,

            // Confirmation details
            'konfirmasi_pelapor' => $this->jadwal->konfirmasi_pelapor,
            'konfirmasi_terlapor' => $this->jadwal->konfirmasi_terlapor,
            'catatan_pelapor' => $this->jadwal->catatan_konfirmasi_pelapor,
            'catatan_terlapor' => $this->jadwal->catatan_konfirmasi_terlapor,

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
