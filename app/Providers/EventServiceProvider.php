<?php

namespace App\Providers;

use App\Events\PengaduanCreated;
use App\Listeners\NotifyMediators;
use App\Events\KonfirmasiKehadiran;
use App\Events\JadwalMediasiCreated;
use App\Events\JadwalMediasiUpdated;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Events\JadwalMediasiStatusUpdated;
use App\Events\JadwalMediasiRescheduleNeeded;
use App\Listeners\SendKonfirmasiNotification;
use App\Listeners\HandleRescheduleNotification;
use App\Listeners\SendJadwalMediasiNotification;
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

        //Event untuk pemberitahuan jadwal mediasi ke pelapor dan terlapor
        JadwalMediasiCreated::class => [
            SendJadwalMediasiNotification::class,
        ],

        JadwalMediasiUpdated::class => [
            SendJadwalMediasiNotification::class,
        ],

        JadwalMediasiStatusUpdated::class => [
            SendJadwalMediasiNotification::class,
        ],

        // Event untuk konfirmasi kehadiran (EMAIL + IN-APP untuk mediator)
        KonfirmasiKehadiran::class => [
            SendKonfirmasiNotification::class,
        ],

        // Event untuk situasi reschedule diperlukan (SPECIAL HANDLING)
        JadwalMediasiRescheduleNeeded::class => [
            HandleRescheduleNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // Manual event binding sebagai fallback
        Event::listen(
            \App\Events\JadwalMediasiCreated::class,
            [\App\Listeners\SendJadwalMediasiNotification::class, 'handle']
        );

        Event::listen(
            \App\Events\JadwalMediasiUpdated::class,
            [\App\Listeners\SendJadwalMediasiNotification::class, 'handle']
        );

        Event::listen(
            \App\Events\JadwalMediasiStatusUpdated::class,
            [\App\Listeners\SendJadwalMediasiNotification::class, 'handle']
        );
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
