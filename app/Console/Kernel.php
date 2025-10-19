<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Command untuk menangani timeout mediasi
        $schedule->command('mediasi:handle-timeout')
            ->daily()
            ->at('00:00')
            ->description('Handle mediation timeout and auto-complete schedules');

        // Command untuk menangani timeout klarifikasi
        $schedule->command('klarifikasi:handle-timeout')
            ->daily()
            ->at('00:01')
            ->description('Handle clarification timeout and auto-complete schedules');

        // Command untuk mengirim reminder konfirmasi
        $schedule->command('jadwal:kirim-reminder')
            ->daily()
            ->at('09:00')
            ->description('Send confirmation reminders for upcoming schedules');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
