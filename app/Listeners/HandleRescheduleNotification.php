<?php

namespace App\Listeners;

use App\Events\JadwalMediasiRescheduleNeeded;
use App\Services\JadwalNotificationService;
use App\Notifications\MediatorInAppNotification;
use App\Notifications\RescheduleRequiredNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class HandleRescheduleNotification implements ShouldQueue
{
    use InteractsWithQueue;

    protected $notificationService;

    public function __construct(JadwalNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle reschedule needed event
     * Sends PRIORITY EMAIL + IN-APP NOTIFICATION to mediator
     */
    public function handle(JadwalMediasiRescheduleNeeded $event)
    {
        try {
            $jadwal = $event->jadwal;

            Log::info('🚨 Processing reschedule needed notification', [
                'jadwal_id' => $jadwal->jadwal_id,
                'absent_party' => $event->absentParty,
                'reason' => $event->reason
            ]);

            // Get mediator for notification
            $mediator = $this->notificationService->getMediator($jadwal);

            if (!$mediator) {
                Log::warning('❌ Mediator not found for reschedule notification', [
                    'jadwal_id' => $jadwal->jadwal_id,
                    'mediator_id' => $jadwal->mediator_id
                ]);
                return;
            }

            Log::info('📧 Sending PRIORITY EMAIL + IN-APP notification to mediator for reschedule', [
                'mediator_email' => $mediator['email'],
                'mediator_name' => $mediator['name'],
                'absent_party' => $event->absentParty,
                'jadwal_status' => $jadwal->status_jadwal
            ]);

            // Send PRIORITY EMAIL notification about reschedule requirement
            $mediator['user']->notify(new RescheduleRequiredNotification(
                $jadwal,
                $event->absentParty,
                $event->reason
            ));

            // Send HIGH PRIORITY IN-APP notification
            $mediator['user']->notify(new MediatorInAppNotification(
                $jadwal,
                'system',
                'reschedule_needed',
                'reschedule_required'
            ));

            Log::info('✅ Reschedule notifications sent successfully', [
                'jadwal_id' => $jadwal->jadwal_id,
                'mediator_email' => $mediator['email'],
                'absent_party' => $event->absentParty,
                'notifications_sent' => ['priority_email', 'high_priority_in_app']
            ]);

            // Log summary of situation
            $this->logRescheduleSummary($jadwal, $event);
        } catch (\Exception $e) {
            Log::error('❌ Failed to send reschedule notification', [
                'error' => $e->getMessage(),
                'jadwal_id' => $event->jadwal->jadwal_id ?? null,
                'absent_party' => $event->absentParty ?? null,
                'trace' => $e->getTraceAsString()
            ]);

            // Re-throw to trigger job retry
            throw $e;
        }
    }

    /**
     * Log comprehensive reschedule summary
     */
    private function logRescheduleSummary($jadwal, $event)
    {
        $summary = [
            'jadwal_id' => $jadwal->jadwal_id,
            'pengaduan_id' => $jadwal->pengaduan_id,
            'original_date' => $jadwal->tanggal_mediasi->format('Y-m-d'),
            'original_time' => $jadwal->waktu_mediasi->format('H:i'),
            'status_jadwal' => $jadwal->status_jadwal,
            'absent_party' => $event->absentParty,
            'reason' => $event->reason,
            'konfirmasi_pelapor' => $jadwal->konfirmasi_pelapor,
            'konfirmasi_terlapor' => $jadwal->konfirmasi_terlapor,
            'action_required' => 'Mediator needs to reschedule the mediation session',
            'timestamp' => now()->toISOString()
        ];

        // Get absent party details
        if ($event->absentParty === 'pelapor' || $event->absentParty === 'both') {
            $summary['pelapor_catatan'] = $jadwal->catatan_konfirmasi_pelapor;
            $summary['pelapor_konfirmasi_at'] = $jadwal->tanggal_konfirmasi_pelapor?->toISOString();
        }

        if ($event->absentParty === 'terlapor' || $event->absentParty === 'both') {
            $summary['terlapor_catatan'] = $jadwal->catatan_konfirmasi_terlapor;
            $summary['terlapor_konfirmasi_at'] = $jadwal->tanggal_konfirmasi_terlapor?->toISOString();
        }

        Log::info('📊 Reschedule situation summary', $summary);
    }

    /**
     * Handle job failure
     */
    public function failed(JadwalMediasiRescheduleNeeded $event, $exception)
    {
        Log::error('❌ Reschedule notification job failed permanently', [
            'jadwal_id' => $event->jadwal->jadwal_id ?? null,
            'absent_party' => $event->absentParty ?? null,
            'error' => $exception->getMessage()
        ]);
    }
}
