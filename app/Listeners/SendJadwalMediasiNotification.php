<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use App\Events\JadwalCreated;
use App\Events\JadwalUpdated;
use App\Events\JadwalStatusUpdated;
use App\Events\JadwalRescheduleNeeded;
use App\Models\Jadwal;
use App\Notifications\JadwalNotification;
use App\Services\JadwalNotificationService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendJadwalNotification
{
    // use InteractsWithQueue;

    protected $notificationService;

    public function __construct(JadwalNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle jadwal created/updated events
     * ONLY sends EMAIL to pelapor and terlapor (no in-app notifications)
     */
    public function handle($event)
    {
        Log::info('🚀 [LISTENER] SendJadwalNotification.handle() CALLED!', [
            'event_class' => get_class($event),
            'jadwal_id' => $event->jadwal->jadwal_id ?? 'unknown'
        ]);

        try {
            Log::info('🔔 [JADWAL EMAIL] Processing jadwal notification', [
                'jadwal_id' => $event->jadwal->jadwal_id,
                'event_type' => $event->eventType ?? null
            ]);

            // Get recipients (pelapor and terlapor only)
            $recipients = $this->notificationService->getRecipients($event->jadwal);

            if (empty($recipients)) {
                Log::warning('❌ [JADWAL EMAIL] No recipients found for jadwal notification', [
                    'jadwal_id' => $event->jadwal->jadwal_id,
                    'event_type' => $event->eventType ?? null
                ]);
                return;
            }

            Log::info('👥 [JADWAL EMAIL] Recipients found', [
                'jadwal_id' => $event->jadwal->jadwal_id,
                'recipients_count' => count($recipients),
                'recipients' => array_map(function ($r) {
                    return [
                        'role' => $r['role'],
                        'email' => $r['email'],
                        'name' => $r['name']
                    ];
                }, $recipients)
            ]);

            $emailsSent = 0;
            $errors = [];

            // Send ONLY EMAIL to pelapor and terlapor
            foreach ($recipients as $recipient) {
                try {
                    Log::info('📧 [JADWAL EMAIL] Sending email', [
                        'jadwal_id' => $event->jadwal->jadwal_id,
                        'to' => $recipient['email'],
                        'role' => $recipient['role'],
                        'event_type' => $event->eventType ?? null
                    ]);

                    // Send email using mailable
                    Mail::to($recipient['email'])
                        ->send(new JadwalNotification(
                            $event->jadwal,
                            $recipient,
                            $event->eventType ?? null,
                            $this->getEventData($event)
                        ));

                    $emailsSent++;

                    Log::info('✅ [JADWAL EMAIL] Email sent successfully', [
                        'jadwal_id' => $event->jadwal->jadwal_id,
                        'recipient_email' => $recipient['email'],
                        'recipient_role' => $recipient['role'],
                        'event_type' => $event->eventType ?? null
                    ]);
                } catch (\Exception $emailError) {
                    $errors[] = [
                        'recipient' => $recipient['email'],
                        'error' => $emailError->getMessage()
                    ];

                    Log::error('❌ [JADWAL EMAIL] Failed to send email to recipient', [
                        'jadwal_id' => $event->jadwal->jadwal_id,
                        'recipient_email' => $recipient['email'],
                        'recipient_role' => $recipient['role'],
                        'error' => $emailError->getMessage()
                    ]);
                }
            }

            // Log final summary
            Log::info('📊 [JADWAL EMAIL] Notification summary', [
                'jadwal_id' => $event->jadwal->jadwal_id,
                'event_type' => $event->eventType ?? null,
                'total_recipients' => count($recipients),
                'emails_sent' => $emailsSent,
                'in_app_notifications' => 0, // No in-app for this event
                'errors_count' => count($errors),
                'errors' => $errors
            ]);

            // If there are errors but some emails were sent, log as warning
            if (count($errors) > 0 && $emailsSent > 0) {
                Log::warning('⚠️ [JADWAL EMAIL] Some emails failed to send', [
                    'jadwal_id' => $event->jadwal->jadwal_id,
                    'successful' => $emailsSent,
                    'failed' => count($errors)
                ]);
            }

            // If all emails failed, throw exception to trigger retry
            if ($emailsSent === 0 && count($recipients) > 0) {
                throw new \Exception('All email notifications failed to send');
            }
        } catch (\Exception $e) {
            Log::error('❌ [JADWAL EMAIL] Failed to send jadwal mediation notification', [
                'jadwal_id' => $event->jadwal->jadwal_id,
                'event_type' => $event->eventType ?? null,
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

        if ($event instanceof JadwalUpdated) {
            $data['old_data'] = $event->oldData;
        }

        if ($event instanceof JadwalStatusUpdated) {
            $data['old_status'] = $event->oldStatus;
        }

        return $data;
    }

    /**
     * Handle job failure
     */
    public function failed($event, $exception)
    {
        Log::error('❌ [JADWAL EMAIL] Job failed permanently', [
            'jadwal_id' => $event->jadwal->jadwal_id ?? 'unknown',
            'event_type' => $event->eventType ?? 'unknown',
            'error' => $exception->getMessage(),
            'failed_at' => now()->toISOString()
        ]);
    }
}
