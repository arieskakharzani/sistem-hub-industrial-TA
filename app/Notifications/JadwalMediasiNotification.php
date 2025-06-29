<?php

namespace App\Notifications;

use App\Models\JadwalMediasi;
use App\Services\JadwalNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JadwalMediasiNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $jadwal;
    public $recipient;
    public $eventType;
    public $notificationData;

    /**
     * Create a new message instance.
     */
    public function __construct(
        JadwalMediasi $jadwal,
        array $recipient,
        string $eventType,
        array $additionalData = []
    ) {
        $this->jadwal = $jadwal;
        $this->recipient = $recipient;
        $this->eventType = $eventType;

        // Format notification data using service
        $notificationService = app(JadwalNotificationService::class);
        $this->notificationData = $notificationService->formatNotificationData(
            $jadwal,
            $eventType,
            $additionalData
        );
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = $this->getEmailSubject();

        return $this->view('emails.jadwal-mediasi')
            ->subject($subject)
            ->with([
                'recipient' => $this->recipient,
                'data' => $this->notificationData,
                'eventType' => $this->eventType,
            ]);
    }

    /**
     * Get email subject based on event type
     */
    private function getEmailSubject(): string
    {
        $baseSubject = "Notifikasi Jadwal Mediasi #{$this->jadwal->jadwal_id}";

        return match ($this->eventType) {
            'created' => "{$baseSubject} - Jadwal Baru Dibuat",
            'updated' => "{$baseSubject} - Perubahan Jadwal",
            'status_updated' => "{$baseSubject} - Update Status",
            default => $baseSubject
        };
    }
}
