<?php

namespace App\Listeners;

use App\Events\KlarifikasiProceedWithoutConfirmation;
use App\Services\JadwalNotificationService;
use App\Notifications\MediatorInAppNotification;
use App\Notifications\KlarifikasiProceedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class HandleKlarifikasiProceedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    protected $notificationService;

    public function __construct(JadwalNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle klarifikasi proceed without confirmation event
     * Sends notification to mediator that klarifikasi will proceed
     */
    public function handle(KlarifikasiProceedWithoutConfirmation $event)
    {
        try {
            $jadwal = $event->jadwal;

            Log::info('📋 Processing klarifikasi proceed notification', [
                'jadwal_id' => $jadwal->jadwal_id,
                'absent_party' => $event->absentParty,
                'reason' => $event->reason
            ]);

            // Get mediator for notification
            $mediator = $this->notificationService->getMediator($jadwal);

            if (!$mediator) {
                Log::warning('❌ Mediator not found for klarifikasi proceed notification', [
                    'jadwal_id' => $jadwal->jadwal_id,
                    'mediator_id' => $jadwal->mediator_id
                ]);
                return;
            }

            Log::info('📧 Sending notification to mediator for klarifikasi proceed', [
                'mediator_email' => $mediator['email'],
                'mediator_name' => $mediator['name'],
                'absent_party' => $event->absentParty,
                'jadwal_status' => $jadwal->status_jadwal
            ]);

            // Send EMAIL notification about klarifikasi proceeding
            $mediator['user']->notify(new KlarifikasiProceedNotification(
                $jadwal,
                $event->absentParty,
                $event->reason
            ));

            // Send IN-APP notification
            $mediator['user']->notify(new MediatorInAppNotification(
                $jadwal,
                'system',
                'klarifikasi_proceed',
                ['type' => 'klarifikasi_proceed']
            ));

            Log::info('✅ Klarifikasi proceed notifications sent successfully', [
                'jadwal_id' => $jadwal->jadwal_id,
                'mediator_email' => $mediator['email'],
                'absent_party' => $event->absentParty,
                'notifications_sent' => ['email', 'in_app']
            ]);

            // Log summary of situation
            $this->logKlarifikasiProceedSummary($jadwal, $event);
        } catch (\Exception $e) {
            Log::error('❌ Failed to send klarifikasi proceed notification', [
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
     * Log comprehensive klarifikasi proceed summary
     */
    private function logKlarifikasiProceedSummary($jadwal, $event)
    {
        $summary = [
            'jadwal_id' => $jadwal->jadwal_id,
            'pengaduan_id' => $jadwal->pengaduan_id,
            'original_date' => $jadwal->tanggal->format('Y-m-d'),
            'original_time' => $jadwal->waktu->format('H:i'),
            'status_jadwal' => $jadwal->status_jadwal,
            'absent_party' => $event->absentParty,
            'reason' => $event->reason,
            'konfirmasi_pelapor' => $jadwal->konfirmasi_pelapor,
            'konfirmasi_terlapor' => $jadwal->konfirmasi_terlapor,
            'action_required' => 'Mediator can proceed with klarifikasi and continue to mediasi',
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

        Log::info('📊 Klarifikasi proceed situation summary', $summary);
    }

    /**
     * Handle job failure
     */
    public function failed(KlarifikasiProceedWithoutConfirmation $event, $exception)
    {
        Log::error('❌ Klarifikasi proceed notification job failed permanently', [
            'jadwal_id' => $event->jadwal->jadwal_id ?? null,
            'absent_party' => $event->absentParty ?? null,
            'error' => $exception->getMessage()
        ]);
    }
}
