<?php

namespace App\Notifications;

use App\Models\Jadwal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JadwalNotification extends Notification implements ShouldQueue
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
            'icon' => $this->getIcon(),
        ], $this->data);
    }

    /**
     * Get default title based on notification type
     */
    private function getDefaultTitle(): string
    {
        return match ($this->type) {
            'jadwal_created' => 'Jadwal ' . $this->jadwal->jenis_jadwal . ' Baru',
            'jadwal_updated' => 'Jadwal ' . $this->jadwal->jenis_jadwal . ' Diperbarui',
            default => 'Notifikasi Jadwal'
        };
    }

    /**
     * Get default message based on notification type
     */
    private function getDefaultMessage(): string
    {
        return match ($this->type) {
            'jadwal_created' => 'Jadwal ' . $this->jadwal->jenis_jadwal . ' baru telah dibuat untuk pengaduan #' . $this->jadwal->pengaduan->nomor_pengaduan,
            'jadwal_updated' => $this->oldStatus
                ? 'Status jadwal telah diubah dari ' . $this->oldStatus . ' menjadi ' . $this->jadwal->status_jadwal
                : 'Jadwal telah diperbarui',
            default => 'Ada pembaruan pada jadwal ' . $this->jadwal->jenis_jadwal
        };
    }

    /**
     * Get icon based on notification type
     */
    private function getIcon(): string
    {
        return match ($this->type) {
            'jadwal_created' => 'calendar-plus',
            'jadwal_updated' => 'calendar-edit',
            default => 'calendar'
        };
    }
}
