<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Mediator;

class DebugNotificationCommand extends Command
{
    protected $signature = 'debug:notification';
    protected $description = 'Debug notification issues';

    public function handle()
    {
        $this->info('Debugging notification issues...');

        try {
            // Cari Kepala Dinas
            $kepalaDinas = User::whereJsonContains('roles', 'kepala_dinas')
                ->where('is_active', true)
                ->first();

            if (!$kepalaDinas) {
                $this->error('No Kepala Dinas found!');
                return;
            }

            $this->info('Found Kepala Dinas: ' . $kepalaDinas->email);

            // Test 1: Cek apakah ada mediator real
            $realMediator = Mediator::with('user')->first();
            if ($realMediator) {
                $this->info('Found real mediator: ' . $realMediator->nama_mediator);
                $this->info('Mediator email: ' . $realMediator->user->email);

                // Test dengan mediator real
                $this->info('Sending notification with real mediator...');
                $kepalaDinas->notify(new \App\Notifications\NewMediatorRegistrationNotification($realMediator));
                $this->info('Real mediator notification sent!');
            } else {
                $this->warn('No real mediators found');
            }

            // Test 2: Cek apakah ada mediator pending
            $pendingMediator = Mediator::where('status', 'pending')->with('user')->first();
            if ($pendingMediator) {
                $this->info('Found pending mediator: ' . $pendingMediator->nama_mediator);
                $this->info('Pending mediator email: ' . $pendingMediator->user->email);

                // Test rejection notification
                $this->info('Sending rejection notification...');
                $pendingMediator->user->notify(new \App\Notifications\MediatorRejectedNotification($pendingMediator));
                $this->info('Rejection notification sent!');
            } else {
                $this->warn('No pending mediators found');
            }
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile() . ':' . $e->getLine());
            $this->error('Trace: ' . $e->getTraceAsString());
        }
    }
}
