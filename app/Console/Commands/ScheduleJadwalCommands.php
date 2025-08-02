<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schedule;

class ScheduleJadwalCommands extends Command
{
    protected $signature = 'jadwal:schedule';
    protected $description = 'Schedule jadwal-related commands';

    public function handle()
    {
        // Schedule reminder command to run every hour
        Schedule::command('jadwal:send-reminder')
            ->hourly()
            ->description('Send confirmation reminders');

        // Schedule overdue handling command to run daily at 6 AM
        Schedule::command('jadwal:handle-overdue')
            ->dailyAt('06:00')
            ->description('Handle overdue jadwal');

        $this->info('Jadwal commands scheduled successfully!');
    }
}
