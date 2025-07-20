<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Jadwal;

class MediatorInAppNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $jadwal;
    protected $type;
    protected $oldStatus;
    protected $data;

    /**
     * Create a new notification instance.
     */
    public function __construct(Jadwal $jadwal, string $type = 'jadwal_created', ?string $oldStatus = null, array $data = [])
    {
        $this->jadwal = $jadwal;
        $this->type = $type;
        $this->oldStatus = $oldStatus;
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return array_merge([
            'title' => $this->data['title'] ?? $this->getDefaultTitle(),
            'message' => $this->data['message'] ?? $this->getDefaultMessage(),
            'type' => $this->type,
            'jadwal_id' => $this->jadwal->jadwal_id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->jadwal->status_jadwal,
        ], $this->data);
    }

    /**
     * Get default title based on notification type
     */
    private function getDefaultTitle(): string
    {
        return match($this->type) {
            'jadwal_created' => 'Jadwal ' . $this->jadwal->jenis_jadwal . ' Baru',
            'jadwal_updated' => 'Jadwal ' . $this->jadwal->jenis_jadwal . ' Diperbarui',
            'konfirmasi_kehadiran' => 'Konfirmasi Kehadiran',
            'reschedule_required' => 'Perlu Penjadwalan Ulang',
            'siap_klarifikasi' => 'Siap Melaksanakan Klarifikasi',
            'siap_mediasi' => 'Siap Melaksanakan Mediasi',
            'tidak_hadir' => 'Pihak Tidak Dapat Hadir',
            'semua_hadir' => 'Semua Pihak Hadir',
            default => 'Notifikasi Jadwal'
        };
    }

    /**
     * Get default message based on notification type
     */
    private function getDefaultMessage(): string
    {
        return match($this->type) {
            'jadwal_created' => 'Jadwal ' . $this->jadwal->jenis_jadwal . ' baru telah dibuat untuk pengaduan #' . $this->jadwal->pengaduan->nomor_pengaduan,
            'jadwal_updated' => $this->oldStatus 
                ? 'Status jadwal telah diubah dari ' . $this->oldStatus . ' menjadi ' . $this->jadwal->status_jadwal
                : 'Jadwal telah diperbarui',
            'konfirmasi_kehadiran' => 'Ada konfirmasi kehadiran baru untuk jadwal ' . $this->jadwal->jenis_jadwal,
            'reschedule_required' => 'Jadwal ' . $this->jadwal->jenis_jadwal . ' perlu dijadwalkan ulang karena ada pihak yang tidak dapat hadir',
            'siap_klarifikasi' => 'Para pihak siap melaksanakan klarifikasi sesuai jadwal yang ditentukan',
            'siap_mediasi' => 'Para pihak siap melaksanakan mediasi sesuai jadwal yang ditentukan',
            'tidak_hadir' => isset($this->data['pihak']) 
                ? $this->data['pihak'] . ' tidak dapat hadir pada jadwal yang ditentukan'
                : 'Ada pihak yang tidak dapat hadir pada jadwal yang ditentukan',
            'semua_hadir' => 'Semua pihak telah mengkonfirmasi kehadiran untuk jadwal ' . $this->jadwal->jenis_jadwal,
            default => 'Ada pembaruan pada jadwal ' . $this->jadwal->jenis_jadwal
        };
    }
}
