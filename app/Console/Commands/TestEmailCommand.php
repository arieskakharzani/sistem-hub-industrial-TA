<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing email functionality...');

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

            // Test email sederhana
            Mail::raw('Test email dari sistem SIPPPHI - ' . now(), function ($message) use ($kepalaDinas) {
                $message->to($kepalaDinas->email)
                    ->subject('Test Email - SIPPPHI - ' . now()->format('Y-m-d H:i:s'));
            });

            $this->info('Email sent successfully to: ' . $kepalaDinas->email);

            // Test notification
            $this->info('Testing notification...');

            // Buat mediator dummy
            $mediator = new \App\Models\Mediator();
            $mediator->mediator_id = 'test-' . uniqid();
            $mediator->nama_mediator = 'Test Mediator';
            $mediator->nip = '123456789';
            $mediator->sk_file_name = 'test-sk.pdf';
            $mediator->created_at = now();

            // Buat user dummy untuk mediator
            $user = new \App\Models\User();
            $user->email = 'test@mediator.com';
            $mediator->setRelation('user', $user);

            // Kirim notification
            $kepalaDinas->notify(new \App\Notifications\NewMediatorRegistrationNotification($mediator));

            $this->info('Notification sent successfully!');
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile() . ':' . $e->getLine());
        }
    }
}
