<?php
// app/Listeners/SendJadwalMediationNotification.php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use App\Events\JadwalMediasiUpdated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\JadwalMediasiStatusUpdated;
use App\Services\JadwalNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\JadwalMediasiNotification;

class SendJadwalMediasiNotification implements ShouldQueue
{
    use InteractsWithQueue;

    protected $notificationService;

    public function __construct(JadwalNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle jadwal created event
     */
    public function handle($event)
    {
        try {
            Log::info('🔔 Processing jadwal mediasi notification', [
                'jadwal_id' => $event->jadwal->jadwal_id,
                'event_type' => $event->eventType
            ]);
            // Get recipients (pelapor and terlapor)
            $recipients = $this->notificationService->getRecipients($event->jadwal);

            if (empty($recipients)) {
                Log::warning('No recipients found for jadwal mediation notification', [
                    'jadwal_id' => $event->jadwal->jadwal_id,
                    'event_type' => $event->eventType
                ]);
                return;
            }

            // Send emails to all recipients (pelapor dan terlapor)
            foreach ($recipients as $recipient) {
                Log::info('📤 Sending email', [
                    'to' => $recipient['email'],
                    'role' => $recipient['role'],
                    'event_type' => $event->eventType
                ]);

                Mail::to($recipient['email'])
                    ->send(new JadwalMediasiNotification(
                        $event->jadwal,
                        $recipient,
                        $event->eventType,
                        $this->getEventData($event)
                    ));

                Log::info('Jadwal mediasi email terkirim', [
                    'jadwal_id' => $event->jadwal->jadwal_id,
                    'recipient_email' => $recipient['email'],
                    'recipient_role' => $recipient['role'],
                    'event_type' => $event->eventType
                ]);
                // Log summary
                Log::info('📊 Jadwal notification summary', [
                    'jadwal_id' => $event->jadwal->jadwal_id,
                    'event_type' => $event->eventType,
                    'emails_sent' => count($recipients),
                    'in_app_notifications' => 0, // No in-app for this event
                    'recipients' => array_column($recipients, 'role')
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send jadwal mediation notification', [
                'jadwal_id' => $event->jadwal->jadwal_id,
                'event_type' => $event->eventType,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Re-throw to trigger job retry
            throw $e;
        }
    }

    /**
     * Get additional event data based on event type
     */
    private function getEventData($event): array
    {
        $data = [];

        if ($event instanceof JadwalMediasiUpdated) {
            $data['old_data'] = $event->oldData;
        }

        if ($event instanceof JadwalMediasiStatusUpdated) {
            $data['old_status'] = $event->oldStatus;
        }

        return $data;
    }

    /**
     * Handle job failure
     */
    public function failed($event, $exception)
    {
        Log::error('Jadwal mediation notification job failed permanently', [
            'jadwal_id' => $event->jadwal->jadwal_id ?? 'unknown',
            'event_type' => $event->eventType ?? 'unknown',
            'error' => $exception->getMessage()
        ]);
    }
}
