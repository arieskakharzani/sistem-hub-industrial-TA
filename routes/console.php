<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule mediasi timeout handling - run daily at 6 AM
Schedule::command('mediasi:handle-timeout')->dailyAt('06:00');

// Schedule klarifikasi timeout handling - run daily at 6 AM
Schedule::command('klarifikasi:handle-timeout')->dailyAt('06:00');
