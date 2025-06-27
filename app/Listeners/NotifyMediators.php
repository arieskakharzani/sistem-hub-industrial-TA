<?php

namespace App\Listeners;

use App\Events\PengaduanCreated;
use App\Models\Mediator;
use App\Notifications\PengaduanBaruNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class NotifyMediators implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PengaduanCreated $event): void
    {
        // Ambil semua mediator yang aktif
        $mediators = Mediator::with('user')
            ->whereHas('user', function ($query) {
                $query->where('is_active', true);
            })
            ->get();

        // Kirim notifikasi ke semua mediator
        foreach ($mediators as $mediator) {
            if ($mediator->user) {
                $mediator->user->notify(new PengaduanBaruNotification($event->pengaduan));
            }
        }

        // Alternatif: Kirim notifikasi secara bulk (lebih efisien)
        // $users = $mediators->pluck('user')->filter();
        // Notification::send($users, new PengaduanBaruNotification($event->pengaduan));
    }

    /**
     * Handle a job failure.
     */
    public function failed(PengaduanCreated $event, $exception): void
    {
        // Log error atau handle failure
        \Log::error('Failed to notify mediators about new pengaduan', [
            'pengaduan_id' => $event->pengaduan->pengaduan_id,
            'error' => $exception->getMessage()
        ]);
    }
}
