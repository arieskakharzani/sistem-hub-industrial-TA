<?php

namespace App\Providers;

use App\Events\PengaduanCreated;
use App\Listeners\NotifyMediators;
use App\Events\KonfirmasiKehadiran;
use App\Events\JadwalCreated;
use App\Events\JadwalUpdated;
use App\Events\JadwalStatusUpdated;
use App\Events\JadwalRescheduleNeeded;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Listeners\SendKonfirmasiNotification;
use App\Listeners\HandleRescheduleNotification;
use App\Listeners\SendJadwalNotification;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        // Event untuk pengaduan baru
        PengaduanCreated::class => [
            NotifyMediators::class,
        ],

        //Event untuk pemberitahuan jadwal ke pelapor dan terlapor
        JadwalCreated::class => [
            SendJadwalNotification::class,
        ],
        JadwalUpdated::class => [
            SendJadwalNotification::class,
        ],
        JadwalStatusUpdated::class => [
            SendJadwalNotification::class,
        ],
        JadwalRescheduleNeeded::class => [
            HandleRescheduleNotification::class,
        ],

        // Event untuk konfirmasi kehadiran (EMAIL + IN-APP untuk mediator)
        KonfirmasiKehadiran::class => [
            SendKonfirmasiNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // Manual event binding sebagai fallback
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
