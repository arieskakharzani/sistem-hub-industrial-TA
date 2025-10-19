<?php

namespace App\Listeners;

use App\Events\MediasiProceedWithoutConfirmation;
use App\Services\JadwalNotificationService;
use App\Notifications\MediatorInAppNotification;
use App\Notifications\MediasiProceedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class HandleMediasiProceedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    protected $notificationService;

    public function __construct(JadwalNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function handle(MediasiProceedWithoutConfirmation $event)
    {
        try {
            $jadwal = $event->jadwal;

            Log::info('📋 Processing mediasi proceed notification', [
                'jadwal_id' => $jadwal->jadwal_id,
                'absent_party' => $event->absentParty,
                'reason' => $event->reason
            ]);

            $mediator = $this->notificationService->getMediator($jadwal);
            if (!$mediator) {
                Log::warning('❌ Mediator not found for mediasi proceed notification', [
                    'jadwal_id' => $jadwal->jadwal_id,
                    'mediator_id' => $jadwal->mediator_id
                ]);
                return;
            }

            Log::info('📧 Sending EMAIL + IN-APP notification to mediator for mediasi proceed', [
                'mediator_email' => $mediator['email'],
                'mediator_name' => $mediator['name'],
                'absent_party' => $event->absentParty,
                'jadwal_status' => $jadwal->status_jadwal
            ]);

            // Send EMAIL notification to mediator
            $mediator['user']->notify(new MediasiProceedNotification(
                $jadwal,
                $event->absentParty,
                $event->reason
            ));

            // Send IN-APP notification to mediator
            $mediator['user']->notify(new MediatorInAppNotification(
                $jadwal,
                'system',
                'mediasi_proceed',
                ['type' => 'mediasi_proceed']
            ));

            Log::info('✅ Mediasi proceed notifications sent successfully', [
                'jadwal_id' => $jadwal->jadwal_id,
                'mediator_email' => $mediator['email'],
                'absent_party' => $event->absentParty,
                'notifications_sent' => ['email', 'in_app']
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Failed to send mediasi proceed notification', [
                'error' => $e->getMessage(),
                'jadwal_id' => $event->jadwal->jadwal_id ?? null,
                'absent_party' => $event->absentParty ?? null,
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function failed(MediasiProceedWithoutConfirmation $event, $exception)
    {
        Log::error('❌ Mediasi proceed notification job failed permanently', [
            'jadwal_id' => $event->jadwal->jadwal_id ?? null,
            'absent_party' => $event->absentParty ?? null,
            'error' => $exception->getMessage()
        ]);
    }
}

