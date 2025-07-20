<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use App\Events\JadwalCreated;
use App\Events\JadwalUpdated;
use App\Events\JadwalStatusUpdated;
use App\Events\JadwalRescheduleNeeded;
use App\Models\Jadwal;
use App\Mail\JadwalNotificationMail;
use App\Services\JadwalNotificationService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendJadwalNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public $tries = 3; // Jumlah percobaan jika gagal
    public $timeout = 60; // Timeout dalam detik

    protected $notificationService;

    public function __construct(JadwalNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle jadwal created/updated events
     * Sends EMAIL to pelapor and terlapor
     */
    public function handle($event)
    {
        Log::info('🚀 [LISTENER] SendJadwalNotification.handle() CALLED!', [
            'event_class' => get_class($event),
            'jadwal_id' => $event->jadwal->jadwal_id ?? 'unknown'
        ]);

        try {
            // Load necessary relationships
            $jadwal = $event->jadwal;
            $jadwal->load(['pengaduan.pelapor.user', 'pengaduan.terlapor', 'mediator.user']);

            Log::info('🔔 [JADWAL EMAIL] Processing jadwal notification', [
                'jadwal_id' => $jadwal->jadwal_id,
                'event_type' => $event->eventType ?? 'unknown',
                'jenis_jadwal' => $jadwal->jenis_jadwal
            ]);

            // Get recipients (pelapor and terlapor)
            $recipients = $this->notificationService->getRecipients($jadwal);

            if (empty($recipients)) {
                Log::warning('❌ [JADWAL EMAIL] No recipients found for jadwal notification', [
                    'jadwal_id' => $jadwal->jadwal_id,
                    'event_type' => $event->eventType ?? 'unknown'
                ]);
                return;
            }

            Log::info('👥 [JADWAL EMAIL] Recipients found', [
                'jadwal_id' => $jadwal->jadwal_id,
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

            // Send email to each recipient
            foreach ($recipients as $recipient) {
                try {
                    Log::info('📧 [JADWAL EMAIL] Sending email', [
                        'jadwal_id' => $jadwal->jadwal_id,
                        'to' => $recipient['email'],
                        'role' => $recipient['role'],
                        'event_type' => $event->eventType ?? 'unknown'
                    ]);

                    // Send email using mailable
                    Mail::to($recipient['email'])
                        ->queue(new JadwalNotificationMail(
                            $jadwal,
                            $recipient,
                            $event->eventType ?? 'unknown',
                            $this->getEventData($event)
                        ));

                    $emailsSent++;

                    Log::info('✅ [JADWAL EMAIL] Email queued successfully', [
                        'jadwal_id' => $jadwal->jadwal_id,
                        'recipient_email' => $recipient['email'],
                        'recipient_role' => $recipient['role']
                    ]);
                } catch (\Exception $emailError) {
                    $errors[] = [
                        'recipient' => $recipient['email'],
                        'error' => $emailError->getMessage()
                    ];

                    Log::error('❌ [JADWAL EMAIL] Failed to queue email to recipient', [
                        'jadwal_id' => $jadwal->jadwal_id,
                        'recipient_email' => $recipient['email'],
                        'recipient_role' => $recipient['role'],
                        'error' => $emailError->getMessage()
                    ]);
                }
            }

            // Log final summary
            Log::info('📊 [JADWAL EMAIL] Notification summary', [
                'jadwal_id' => $jadwal->jadwal_id,
                'event_type' => $event->eventType ?? 'unknown',
                'total_recipients' => count($recipients),
                'emails_queued' => $emailsSent,
                'errors_count' => count($errors),
                'errors' => $errors
            ]);

            // If there are errors but some emails were queued, log as warning
            if (count($errors) > 0 && $emailsSent > 0) {
                Log::warning('⚠️ [JADWAL EMAIL] Some emails failed to queue', [
                    'jadwal_id' => $jadwal->jadwal_id,
                    'successful' => $emailsSent,
                    'failed' => count($errors)
                ]);
            }

            // If all emails failed, throw exception to trigger retry
            if ($emailsSent === 0 && count($recipients) > 0) {
                throw new \Exception('All email notifications failed to queue');
            }
        } catch (\Exception $e) {
            Log::error('❌ [JADWAL EMAIL] Failed to send jadwal notification', [
                'jadwal_id' => $event->jadwal->jadwal_id ?? 'unknown',
                'event_type' => $event->eventType ?? 'unknown',
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
