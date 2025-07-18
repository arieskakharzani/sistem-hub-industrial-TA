<?php

namespace App\Notifications;

use App\Models\Jadwal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class MediatorInAppNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $jadwal;
    protected $userRole;
    protected $konfirmasi;
    protected $notificationType;

    /**
     * Create a new notification instance.
     */
    public function __construct(Jadwal $jadwal, string $userRole, string $konfirmasi, string $notificationType)
    {
        $this->jadwal = $jadwal;
        $this->userRole = $userRole;
        $this->konfirmasi = $konfirmasi;
        $this->notificationType = $notificationType;
    }

    /**
     * Get the notification's delivery channels.
     * ONLY database (in-app) for mediator
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification for database storage.
     */
    public function toArray($notifiable)
    {
        $pengaduan = $this->jadwal->pengaduan;

        return [
            'type' => $this->getNotificationType(),
            'title' => $this->getNotificationTitle(),
            'message' => $this->getNotificationMessage(),
            'icon' => $this->getNotificationIcon(),
            'color' => $this->getNotificationColor(),
            'priority' => $this->getNotificationPriority(),
            'action_url' => route('jadwal.show', $this->jadwal->jadwal_id),
            'action_text' => $this->getActionText(),

            // Data payload
            'data' => [
                'jadwal_id' => $this->jadwal->jadwal_id,
                'pengaduan_id' => $this->jadwal->pengaduan_id,
                'user_role' => $this->userRole,
                'konfirmasi' => $this->konfirmasi,
                'notification_type' => $this->notificationType,
                'tanggal' => $this->jadwal->tanggal->format('Y-m-d'),
                'waktu' => $this->jadwal->waktu->format('H:i'),
                'tempat' => $this->jadwal->tempat,
                'status_jadwal' => $this->jadwal->status_jadwal,
                'jenis_jadwal' => $this->jadwal->jenis_jadwal,

                // Confirmation status
                'konfirmasi_pelapor' => $this->jadwal->konfirmasi_pelapor,
                'konfirmasi_terlapor' => $this->jadwal->konfirmasi_terlapor,

                // Pengaduan info
                'pengaduan_perihal' => $pengaduan->perihal ?? '',
                'pelapor_nama' => $pengaduan->pelapor->nama_pelapor ?? '',
                'terlapor_nama' => $pengaduan->terlapor->nama_terlapor ?? '',

                // Meta
                'timestamp' => now()->toISOString(),
                'requires_action' => $this->requiresAction(),
            ]
        ];
    }

    /**
     * Get notification type for categorization
     */
    private function getNotificationType(): string
    {
        return match ($this->notificationType) {
            'attendance_confirmed' => 'konfirmasi_kehadiran',
            'absence_reported' => 'ketidakhadiran',
            'all_confirmed_present' => 'semua_konfirmasi_hadir',
            'reschedule_required' => 'perlu_reschedule',
            'ready_to_proceed' => 'siap_klarifikasi_mediasi',
            default => 'konfirmasi_umum'
        };
    }

    /**
     * Get notification title
     */
    private function getNotificationTitle(): string
    {
        $roleText = $this->userRole === 'pelapor' ? 'Pelapor' : ($this->userRole === 'terlapor' ? 'Terlapor' : 'Sistem');
        $jenisJadwal = $this->jadwal->jenis_jadwal;

        return match ($this->notificationType) {
            'attendance_confirmed' => "✅ {$roleText} Konfirmasi Hadir",
            'absence_reported' => "❌ {$roleText} Tidak Dapat Hadir",
            'all_confirmed_present' => "🎉 Semua Pihak Siap Hadir",
            'reschedule_required' => "⚠️ Perlu Penjadwalan Ulang",
            'ready_to_proceed' => "✅ {$jenisJadwal} Siap Dilaksanakan",
            default => "📋 Update Konfirmasi"
        };
    }

    /**
     * Get notification message
     */
    private function getNotificationMessage(): string
    {
        $pengaduan = $this->jadwal->pengaduan;
        $perihal = $pengaduan->perihal ?? 'Mediasi';
        $tanggal = $this->jadwal->tanggal->format('d F Y');
        $jenisJadwal = $this->jadwal->jenis_jadwal;

        $roleText = $this->userRole === 'pelapor' ? 'Pelapor' : ($this->userRole === 'terlapor' ? 'Terlapor' : '');

        return match ($this->notificationType) {
            'attendance_confirmed' => "{$roleText} telah mengkonfirmasi kehadiran untuk {$jenisJadwal} '{$perihal}' pada {$tanggal}.",

            'absence_reported' => "{$roleText} melaporkan tidak dapat hadir pada {$jenisJadwal} '{$perihal}' tanggal {$tanggal}. Penjadwalan ulang diperlukan.",

            'all_confirmed_present' => "Kedua belah pihak telah mengkonfirmasi kehadiran untuk {$jenisJadwal} '{$perihal}' pada {$tanggal}. {$jenisJadwal} dapat dilaksanakan sesuai jadwal.",

            'reschedule_required' => "Ada pihak yang tidak dapat hadir pada {$jenisJadwal} '{$perihal}' tanggal {$tanggal}. Silakan lakukan penjadwalan ulang.",

            'ready_to_proceed' => "Semua pihak siap hadir untuk {$jenisJadwal} '{$perihal}' pada {$tanggal}. Pastikan persiapan {$jenisJadwal} telah lengkap.",

            default => "Update konfirmasi untuk {$jenisJadwal} '{$perihal}' pada {$tanggal}."
        };
    }

    /**
     * Get notification icon
     */
    private function getNotificationIcon(): string
    {
        return match ($this->notificationType) {
            'attendance_confirmed' => '✅',
            'absence_reported' => '❌',
            'all_confirmed_present' => '🎉',
            'reschedule_required' => '⚠️',
            'ready_to_proceed' => '🚀',
            default => '📋'
        };
    }

    /**
     * Get notification color
     */
    private function getNotificationColor(): string
    {
        return match ($this->notificationType) {
            'attendance_confirmed' => 'green',
            'absence_reported' => 'red',
            'all_confirmed_present' => 'blue',
            'reschedule_required' => 'orange',
            'ready_to_proceed' => 'purple',
            default => 'gray'
        };
    }

    /**
     * Get notification priority
     */
    private function getNotificationPriority(): string
    {
        return match ($this->notificationType) {
            'reschedule_required' => 'high',
            'absence_reported' => 'high',
            'all_confirmed_present' => 'medium',
            'ready_to_proceed' => 'medium',
            'attendance_confirmed' => 'normal',
            default => 'normal'
        };
    }

    /**
     * Get action text for notification
     */
    private function getActionText(): string
    {
        $jenisJadwal = $this->jadwal->jenis_jadwal;
        return match ($this->notificationType) {
            'reschedule_required' => "Jadwalkan Ulang",
            'absence_reported' => "Lihat Detail & Reschedule",
            'all_confirmed_present' => "Lihat Jadwal",
            'ready_to_proceed' => "Lihat Persiapan {$jenisJadwal}",
            default => "Lihat Detail"
        };
    }

    /**
     * Check if notification requires immediate action
     */
    private function requiresAction(): bool
    {
        return in_array($this->notificationType, [
            'reschedule_required',
            'absence_reported'
        ]);
    }
}
