<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TestQueue extends Command
{
    protected $signature = 'test:queue';
    protected $description = 'Test queue system';

    public function handle()
    {
        Log::info('🧪 Testing queue system...');

        // Test simple job
        dispatch(function () {
            Log::info('✅ Queue job executed successfully!');
        });

        $this->info('Test job dispatched. Check logs.');
    }
}
