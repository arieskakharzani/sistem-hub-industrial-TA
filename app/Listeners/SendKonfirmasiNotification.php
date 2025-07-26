<?php

namespace App\Listeners;

use App\Events\KonfirmasiKehadiran;
use App\Services\JadwalNotificationService;
use App\Notifications\KonfirmasiKehadiranNotification;
use App\Notifications\MediatorInAppNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendKonfirmasiNotification implements ShouldQueue
{
    use InteractsWithQueue;

    protected $notificationService;

    public function __construct(JadwalNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle konfirmasi kehadiran event
     * Sends EMAIL + IN-APP NOTIFICATION to mediator only
     */
    public function handle(KonfirmasiKehadiran $event)
    {
        try {
            $jadwal = $event->jadwal;

            Log::info('🔔 Processing konfirmasi kehadiran notification', [
                'jadwal_id' => $jadwal->jadwal_id,
                'user_role' => $event->userRole,
                'konfirmasi' => $event->konfirmasi
            ]);

            // Get mediator for notification
            $mediator = $this->notificationService->getMediator($jadwal);

            if (!$mediator) {
                Log::warning('❌ Mediator not found for konfirmasi notification', [
                    'jadwal_id' => $jadwal->jadwal_id,
                    'mediator_id' => $jadwal->mediator_id
                ]);
                return;
            }

            Log::info('📧 Sending EMAIL + IN-APP notification to mediator', [
                'mediator_email' => $mediator['email'],
                'mediator_name' => $mediator['name'],
                'konfirmasi_from' => $event->userRole,
                'konfirmasi_status' => $event->konfirmasi
            ]);

            // Send EMAIL notification to mediator
            $mediator['user']->notify(new KonfirmasiKehadiranNotification(
                $jadwal,
                $event->userRole,
                $event->konfirmasi
            ));

            // Send IN-APP notification to mediator
            $mediator['user']->notify(new MediatorInAppNotification(
                $jadwal,
                $event->userRole,
                $event->konfirmasi,
                is_array($this->determineNotificationType($jadwal, $event->konfirmasi))
                    ? $this->determineNotificationType($jadwal, $event->konfirmasi)
                    : ['type' => $this->determineNotificationType($jadwal, $event->konfirmasi)]
            ));

            Log::info('✅ Konfirmasi kehadiran notifications sent successfully', [
                'jadwal_id' => $jadwal->jadwal_id,
                'mediator_email' => $mediator['email'],
                'user_role' => $event->userRole,
                'konfirmasi' => $event->konfirmasi,
                'notifications_sent' => ['email', 'in_app']
            ]);

            // Check if special action required (reschedule needed)
            $this->checkForSpecialActions($jadwal, $event);
        } catch (\Exception $e) {
            Log::error('❌ Failed to send konfirmasi kehadiran notification', [
                'error' => $e->getMessage(),
                'jadwal_id' => $event->jadwal->jadwal_id ?? null,
                'user_role' => $event->userRole ?? null,
                'trace' => $e->getTraceAsString()
            ]);

            // Re-throw to trigger job retry
            throw $e;
        }
    }

    /**
     * Check for special actions needed based on confirmation status
     */
    private function checkForSpecialActions($jadwal, $event)
    {
        // Reload jadwal to get latest confirmation status
        $jadwal->refresh();

        // Case 1: Someone can't attend - need reschedule
        if ($jadwal->adaYangTidakHadir()) {
            Log::info('⚠️ Reschedule needed - someone cannot attend', [
                'jadwal_id' => $jadwal->jadwal_id,
                'konfirmasi_pelapor' => $jadwal->konfirmasi_pelapor,
                'konfirmasi_terlapor' => $jadwal->konfirmasi_terlapor,
                'status_jadwal' => $jadwal->status_jadwal
            ]);

            // Additional notification for reschedule requirement
            $mediator = $this->notificationService->getMediator($jadwal);
            if ($mediator) {
                $mediator['user']->notify(new MediatorInAppNotification(
                    $jadwal,
                    'system',
                    'reschedule_needed',
                    ['type' => 'reschedule_required']
                ));
            }
        }

        // Case 2: Both parties confirmed attendance
        if ($jadwal->sudahDikonfirmasiSemua() && !$jadwal->adaYangTidakHadir()) {
            Log::info('✅ Both parties confirmed attendance', [
                'jadwal_id' => $jadwal->jadwal_id,
                'konfirmasi_pelapor' => $jadwal->konfirmasi_pelapor,
                'konfirmasi_terlapor' => $jadwal->konfirmasi_terlapor
            ]);

            // Additional notification for ready to proceed
            $mediator = $this->notificationService->getMediator($jadwal);
            if ($mediator) {
                $mediator['user']->notify(new MediatorInAppNotification(
                    $jadwal,
                    'system',
                    'both_confirmed',
                    ['type' => 'ready_to_proceed']
                ));
            }
        }
    }

    /**
     * Determine notification type based on confirmation
     */
    private function determineNotificationType($jadwal, $konfirmasi): string
    {
        if ($konfirmasi === 'tidak_hadir') {
            return 'absence_reported';
        }

        if ($konfirmasi === 'hadir') {
            // Check if this completes all confirmations
            $jadwal->refresh();
            if ($jadwal->sudahDikonfirmasiSemua() && !$jadwal->adaYangTidakHadir()) {
                return 'all_confirmed_present';
            }
            return 'attendance_confirmed';
        }

        return 'confirmation_received';
    }

    /**
     * Handle a job failure
     */
    public function failed(KonfirmasiKehadiran $event, $exception)
    {
        Log::error('❌ Konfirmasi kehadiran notification job failed permanently', [
            'jadwal_id' => $event->jadwal->jadwal_id ?? null,
            'user_role' => $event->userRole ?? null,
            'error' => $exception->getMessage()
        ]);
    }
}
